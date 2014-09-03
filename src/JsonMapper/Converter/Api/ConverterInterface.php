<?php

namespace JsonMapper\Converter\Api;

use JsonMapper\Context\Api\MapperContextInterface;
use JsonMapper\Metadata\Api\TypeMetadataInterface;

/**
 * An interface for converters to be used by JsonMapper
 */
interface ConverterInterface
{
    /**
     * @param \JsonMapper\Context\Api\MapperContextInterface $context
     * @param TypeMetadataInterface              $typeMetadata
     * @param object                             $value
     *
     * @return array|object
     */
    public function toJson(MapperContextInterface $context, TypeMetadataInterface $typeMetadata, $value);

    /**
     * @param MapperContextInterface $context
     * @param TypeMetadataInterface  $typeMetadata
     * @param                        $value
     *
     * @return mixed
     */
    public function fromJson(MapperContextInterface $context, TypeMetadataInterface $typeMetadata, $value);

    /**
     * Verifies converter handles given type
     *
     * @param TypeMetadataInterface $type
     *
     * @return boolean
     */
    public function supports(TypeMetadataInterface $type);
}
