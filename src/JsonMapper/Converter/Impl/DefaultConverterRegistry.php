<?php

namespace JsonMapper\Converter\Impl;

use JsonMapper\Converter\Api\ConverterInterface;
use JsonMapper\Converter\Api\ConverterRegistryInterface;
use JsonMapper\Metadata\Api\TypeMetadataInterface;

/**
 * Default converter registry used to convert objects from/to JSON
 */
class DefaultConverterRegistry implements ConverterRegistryInterface
{
    /** @var ConverterInterface[] */
    private $converters = [];

    public function __construct($registerDefaults = true)
    {
        if ($registerDefaults) {
            $this->converters[] = new ArrayLikeConverter();
            $this->converters[] = new PropertyHolderConverter();
            $this->converters[] = new PrimitiveConverter();
        }
        $this->converters[] = new NotSupportedConverter();
    }

    public function register(ConverterInterface $converter)
    {
        array_unshift($this->converters, $converter);
    }

    public function getConverterFor(TypeMetadataInterface $metadata)
    {
        $retConverter = null;
        foreach ($this->converters as $converter) {
            if ($converter->supports($metadata)) {
                $retConverter = $converter;
                break;
            }
        }
        return $retConverter;
    }
}
