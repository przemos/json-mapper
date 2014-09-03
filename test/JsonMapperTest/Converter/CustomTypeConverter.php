<?php

namespace JsonMapperTest\Converter;

use JsonMapper\Context\Api\MapperContextInterface;
use JsonMapper\Converter\Api\ConverterInterface;
use JsonMapper\Metadata\Api\TypeMetadataInterface;
use JsonMapper\Utils\FqcnComparer;
use JsonMapperTest\Type\CustomType;

/**
 *
 */
class CustomTypeConverter implements ConverterInterface
{


    public function toJson(MapperContextInterface $context, TypeMetadataInterface $typeMetadata, $value)
    {
        return $value->toString();
    }

    /**
     * @param MapperContextInterface $context
     * @param TypeMetadataInterface  $typeMetadata
     * @param                        $value
     *
     * @return CustomType
     */
    public function fromJson(MapperContextInterface $context, TypeMetadataInterface $typeMetadata, $value)
    {
        return CustomType::fromString($value);
    }

    public function supports(TypeMetadataInterface $type)
    {
        return FqcnComparer::compare($type->getType(), CustomType::CLASS_PATH);
    }
}
