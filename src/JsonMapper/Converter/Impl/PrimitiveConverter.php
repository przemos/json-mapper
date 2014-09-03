<?php

namespace JsonMapper\Converter\Impl;

use JsonMapper\Converter\Api\ConverterInterface;
use JsonMapper\Converter\Exception\ConversionFailedConverterException;
use JsonMapper\Context\Api\MapperContextInterface;
use JsonMapper\Metadata\Api\TypeMetadataInterface;

/**
 * Converts primitive types from/to JSON representation
 *
 * Class PrimitiveConverter
 *
 * @package JsonMapper\Converter\Impl
 */
class PrimitiveConverter implements ConverterInterface
{
    /**
     * @param MapperContextInterface $context
     * @param TypeMetadataInterface  $typeMetadata
     * @param object                 $value
     *
     * @return array|null|object
     * @SuppressWarnings(PHPMD.UnusedFormalParameters)
     */
    public function toJson(
        MapperContextInterface $context,
        TypeMetadataInterface $typeMetadata,
        $value
    ) {
        return $this->handleConversion($typeMetadata->getType(), $value);
    }

    /**
     * @param MapperContextInterface $context
     * @param TypeMetadataInterface  $typeMetadata
     * @param                        $value
     *
     * @return mixed
     * @SuppressWarnings(PHPMD.UnusedFormalParameters)
     */
    public function fromJson(MapperContextInterface $context, TypeMetadataInterface $typeMetadata, $value)
    {
        return $this->handleConversion($typeMetadata->getType(), $value);
    }

    /**
     * @param TypeMetadataInterface $type
     *
     * @return bool
     */
    public function supports(TypeMetadataInterface $type)
    {
        return in_array($type->getType(), ['NULL', 'string', 'integer', 'double', 'boolean']);
    }

    private function handleConversion($type, $value)
    {
        $ret = null;
        if (!is_null($value)) {
            if (!$this->isValidPrimitive($type, $value)) {
                throw new ConversionFailedConverterException($type, $value);
            }
            $ret = $value;
            settype($ret, $type);
        }
        return $ret;
    }

    private function isValidPrimitive($type, $value)
    {
        $ret = false;
        switch ($type) {
            case 'string':
                $ret = is_string($value);
                break;
            case 'integer':
                $ret = is_int($value);
                break;
            case 'boolean':
                $ret = is_bool($value);
                break;
            case 'double':
                $ret = is_double($value) || is_float($value) || is_int($value);
                break;
        }
        return $ret;
    }
}
