<?php

namespace JsonMapper;

use JsonMapper\Context\Impl\MapperContextImpl;
use JsonMapper\Converter\Api\ConverterRegistryInterface;
use JsonMapper\Converter\Impl\DefaultConverterRegistry;
use JsonMapper\Exception\JsonMapperException;
use JsonMapper\Metadata\Api\MetadataProviderInterface;
use JsonMapper\Metadata\Impl\DefaultMetadataProvider;

/**
 * Automatically maps objects to JSON arrays and the other way round
 * An object has to follow rules specified below to be succesfully converted into JSON array:
 *
 * - for each property to be mapped there has to be a corresponding setter
 * - each property (or its setter) has to be annotated properly.
 *
 * Supported types:
 *
 * 1) primitive types:
 *      - int/integer
 *      - string
 *      - bool/boolean
 *      - double
 *
 * 2) arrays of primitive types:
 *      - int[]/integer[]
 *      - string []
 *      - etc.
 * 3) FQCN type e.g. \Namespace\MappedType
 * 4) Array of FQCN type: e.g. \Namespace\MappedType[]
 *
 * Each property of the object is required when mapping from JSON array
 * However, it is possible to mark a property as optional with @optional annotation on the property
 * The mapper supports type serializers @see TypeSerializer
 * which allow to convert a custom type to/back from string representation
 */
class JsonMapper
{
    /** @var  MetadataProviderInterface */
    private $metadataProvider;

    /** @var ConverterRegistryInterface */
    private $converterRegistry;

    public function __construct()
    {
        $this->metadataProvider = new DefaultMetadataProvider();
        $this->converterRegistry = new DefaultConverterRegistry();
    }

    /**
     * Converts object to json compatible array
     *
     * @param object      $object
     * @param string|null $typeHint
     *
     * @throws JsonMapperException
     *
     * @return array
     */
    public function toJson($object, $typeHint = null)
    {
        $context = new MapperContextImpl($this->converterRegistry);
        try {
            $typeMetadata = $this->metadataProvider->getTypeMetadata($typeHint ? : get_class($object));
            $converter = $this->converterRegistry->getConverterFor($typeMetadata);
            return $converter->toJson($context, $typeMetadata, $object);
        } catch (\Exception $e) {
            throw new JsonMapperException($e, $context->getContextPath());
        }
    }

    /**
     * Converts JSON compatible array into object.
     * JSON array must represent either a single JSON document or a collection of documents
     *
     * @param array  $json
     * @param string $type
     *
     * @return object
     * @throws JsonMapperException
     */
    public function fromJson(array $json, $type)
    {
        $context = new MapperContextImpl($this->converterRegistry, $this->metadataProvider);
        try {
            $typeMetadata = $this->metadataProvider->getTypeMetadata($type);
            $converter = $this->converterRegistry->getConverterFor($typeMetadata);
            return $converter->fromJson($context, $typeMetadata, $json);
        } catch (\Exception $e) {
            throw new JsonMapperException($e, $context->getContextPath());
        }
    }

    /**
     * @param \JsonMapper\Converter\Api\ConverterRegistryInterface $converterRegistry
     *
     * @return $this
     */
    public function setConverterRegistry($converterRegistry)
    {
        $this->converterRegistry = $converterRegistry;
        return $this;
    }

    /**
     * @param \JsonMapper\Metadata\Api\MetadataProviderInterface $metadataProvider
     *
     * @return $this
     */
    public function setMetadataProvider($metadataProvider)
    {
        $this->metadataProvider = $metadataProvider;
        return $this;
    }
}
