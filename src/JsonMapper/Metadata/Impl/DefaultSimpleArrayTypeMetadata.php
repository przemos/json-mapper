<?php

namespace JsonMapper\Metadata\Impl;

use JsonMapper\Metadata\Api\ArrayTypeMetadataInterface;
use JsonMapper\Metadata\Api\TypeMetadataInterface;

/**
 * Default implementation of array-like type metadata
 */
class DefaultSimpleArrayTypeMetadata implements ArrayTypeMetadataInterface
{
    private $type;

    private $valueType;

    public function __construct($type, TypeMetadataInterface $valueType)
    {
        $this->type = $type;
        $this->valueType = $valueType;
    }

    public function getValueTypeMetadata()
    {
        return $this->valueType;
    }

    public function append(&$array, &$value)
    {
        $array[] = $value;
    }

    public function getType()
    {
        return $this->type;
    }

    public function instantiate()
    {
        return [];
    }
}
