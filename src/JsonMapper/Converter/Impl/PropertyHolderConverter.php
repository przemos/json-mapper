<?php

namespace JsonMapper\Converter\Impl;

use JsonMapper\Converter\Api\ConverterInterface;
use JsonMapper\Converter\Exception\PropertyNotFoundConverterException;
use JsonMapper\Context\Api\MapperContextInterface;
use JsonMapper\Metadata\Api\PropertyHolderTypeMetadataInterface;
use JsonMapper\Metadata\Api\TypeMetadataInterface;

/**
 * Converts complex type object from/to JSON representation
 *
 * Class PropertyHolderConverter
 *
 * @package JsonMapper\Converter\Impl
 */
class PropertyHolderConverter implements ConverterInterface
{
    use ConverterHelperTrait;

    public function toJson(MapperContextInterface $context, TypeMetadataInterface $typeMetadata, $value)
    {
        $ret = [];
        /** @var $typeMetadata PropertyHolderTypeMetadataInterface */
        foreach ($typeMetadata->getProperties() as $propertyName) {
            $propType = $typeMetadata->getPropertyTypeMetadata($propertyName);
            $propertyConverter = $context->getConverterRegistry()->getConverterFor($propType);
            $propertyAccessor = $typeMetadata->getPropertyAccessor($propertyName);
            $propValue = $propertyAccessor($value, $propertyName);
            $context->pushPathProperty($propertyName);
            $ret[$propertyName] = $propertyConverter->toJson($context, $propType, $propValue);
            $context->pathPop();
        }
        return $ret;
    }

    public function fromJson(MapperContextInterface $context, TypeMetadataInterface $typeMetadata, $value)
    {
        $this->assertJsonToComplexPossible($typeMetadata->getType(), $value);
        /** @var $typeMetadata PropertyHolderTypeMetadataInterface */
        $object = $typeMetadata->instantiate();
        foreach ($value as $jsonKey => $jsonVal) {
            $propExists = $typeMetadata->hasProperty($jsonKey);
            $context->pushPathProperty($jsonKey);
            if (!$propExists) {
                throw new PropertyNotFoundConverterException();
            }
            $propertyTypeMetadata = $typeMetadata->getPropertyTypeMetadata($jsonKey);

            $propertyConverter = $context->getConverterRegistry()->getConverterFor($propertyTypeMetadata);
            $propertyValue = $propertyConverter->fromJson($context, $propertyTypeMetadata, $jsonVal);

            $propertyMutator = $typeMetadata->getPropertyMutator($jsonKey);
            $propertyMutator($object, $propertyValue);

            $context->pathPop();
        }

        return $object;
    }

    public function supports(TypeMetadataInterface $type)
    {
        return $type instanceof PropertyHolderTypeMetadataInterface;
    }
}
