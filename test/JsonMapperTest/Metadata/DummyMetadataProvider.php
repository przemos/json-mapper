<?php

namespace JsonMapperTest\Metadata;

use JsonMapper\Metadata\Api\MetadataProviderInterface;
use JsonMapper\Metadata\Api\TypeMetadataInterface;

/**
 *
 */
class DummyMetadataProvider implements MetadataProviderInterface
{

    /**
     * Returns type metadata for a given type
     *
     * @param string $type
     *
     * @return TypeMetadataInterface
     */
    public function getTypeMetadata($type)
    {
        return null;
    }
}