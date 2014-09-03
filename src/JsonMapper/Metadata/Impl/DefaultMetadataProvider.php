<?php

namespace JsonMapper\Metadata\Impl;

use JsonMapper\Metadata;
use JsonMapper\Metadata\Api\MetadataProviderInterface;

/**
 * Default implementation of metadata provider
 */
class DefaultMetadataProvider implements MetadataProviderInterface
{
    /**
     * @inheritDoc
     */
    public function getTypeMetadata($type)
    {
        list($isArr, $arrayType) = self::extractArrayType($type);
        if ($isArr) {
            return new DefaultSimpleArrayTypeMetadata($type, $this->getTypeMetadata($arrayType));
        } elseif (class_exists($type)) {
            return new ClassSettersAndGettersPropertyHolderTypeMetadata($this, $type);
        } else {
            return new DefaultTypeMetadata($type);
        }
    }


    protected static function extractArrayType($type)
    {
        return substr($type, -2) === '[]' ? [true, substr($type, 0, -2)] : [false, null];
    }
}
