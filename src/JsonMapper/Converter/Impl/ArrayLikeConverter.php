<?php

namespace JsonMapper\Converter\Impl;

use JsonMapper\Converter\Api\ConverterInterface;
use JsonMapper\Context\Api\MapperContextInterface;
use JsonMapper\Metadata\Api\ArrayTypeMetadataInterface;
use JsonMapper\Metadata\Api\TypeMetadataInterface;

/**
 * Converts array-like types from/to JSON representation
 * Class ArrayLikeConverter
 *
 * @package JsonMapper\Converter\Impl
 */
class ArrayLikeConverter implements ConverterInterface
{
    use ConverterHelperTrait;

    public function toJson(MapperContextInterface $context, TypeMetadataInterface $typeMetadata, $value)
    {
        $output = [];
        /** @var $typeMetadata ArrayTypeMetadataInterface */
        $valueTypeMetadata = $typeMetadata->getValueTypeMetadata();
        foreach ($value as $arrayKey => $arrayValue) {
            $converter = $context->getConverterRegistry()->getConverterFor($valueTypeMetadata);
            $context->pushPathProperty("[$arrayKey]");
            $output[$arrayKey] = $converter->toJson($context, $valueTypeMetadata, $arrayValue);
            $context->pathPop();
        }

        return $output;
    }

    public function fromJson(MapperContextInterface $context, TypeMetadataInterface $typeMetadata, $value)
    {
        $this->assertJsonToComplexPossible($typeMetadata->getType(), $value);
        /** @var $typeMetadata ArrayTypeMetadataInterface */
        $objectArray = $typeMetadata->instantiate();
        $arrElemMetadata = $typeMetadata->getValueTypeMetadata();
        $converter = $context->getConverterRegistry()->getConverterFor($arrElemMetadata);
        foreach ($value as $jsonKey => $jsonValue) {
            $context->pushPathIndex($jsonKey);
            $result = $converter->fromJson($context, $arrElemMetadata, $jsonValue);
            $typeMetadata->append($objectArray, $result);
            $context->pathPop();
        }

        return $objectArray;
    }

    public function supports(TypeMetadataInterface $type)
    {
        return $type instanceof ArrayTypeMetadataInterface;
    }
}
