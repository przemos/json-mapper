<?php

namespace JsonMapper\Converter\Impl;

use JsonMapper\Converter\Api\ConverterInterface;
use JsonMapper\Converter\Exception\UnsupportedTypeMetadataException;
use JsonMapper\Context\Api\MapperContextInterface;
use JsonMapper\Metadata\Api\TypeMetadataInterface;

/**
 * A fallback converter to handle unsupported types
 */
class NotSupportedConverter implements ConverterInterface
{

    /**
     * @param MapperContextInterface $context
     * @param TypeMetadataInterface  $typeMetadata
     * @param object                 $value
     *
     * @return array|object|void
     * @throws \JsonMapper\Converter\Exception\UnsupportedTypeMetadataException
     * @SuppressWarnings(PHPMD.UnusedFormalParameters)
     */
    public function toJson(MapperContextInterface $context, TypeMetadataInterface $typeMetadata, $value)
    {
        throw new UnsupportedTypeMetadataException($typeMetadata->getType());
    }

    /**
     * @param MapperContextInterface $context
     * @param TypeMetadataInterface  $typeMetadata
     * @param                        $value
     *
     * @return mixed|void
     * @throws \JsonMapper\Converter\Exception\UnsupportedTypeMetadataException
     * @SuppressWarnings(PHPMD.UnusedFormalParameters)
     */
    public function fromJson(MapperContextInterface $context, TypeMetadataInterface $typeMetadata, $value)
    {
        throw new UnsupportedTypeMetadataException($typeMetadata->getType());
    }

    /**
     * Catch-all converter that supports every type
     *
     * @param TypeMetadataInterface $type
     *
     * @return bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameters)
     */
    public function supports(TypeMetadataInterface $type)
    {
        return true;
    }
}
