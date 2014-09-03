<?php

namespace JsonMapper\Metadata\Impl;

use JsonMapper\Metadata\Api\TypeMetadataInterface;

/**
 * Default implementation of generic type metadata
 */
class DefaultTypeMetadata implements TypeMetadataInterface
{
    private $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }
}
