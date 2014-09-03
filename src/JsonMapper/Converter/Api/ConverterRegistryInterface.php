<?php

namespace JsonMapper\Converter\Api;

use JsonMapper\Metadata\Api\TypeMetadataInterface;

/**
 * Interface for converter registry used by JsonMapper
 */
interface ConverterRegistryInterface
{
    /**
     * @param TypeMetadataInterface $metadata
     *
     * @return ConverterInterface
     */
    public function getConverterFor(TypeMetadataInterface $metadata);

    /**
     * @param ConverterInterface $converter
     */
    public function register(ConverterInterface $converter);
}
