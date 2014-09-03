<?php

namespace JsonMapper\Metadata\Api;


/**
 * Interface for metadata providers
 */
interface MetadataProviderInterface
{
    /**
     * Returns type metadata for a given type
     *
     * @param string $type
     *
     * @return TypeMetadataInterface
     */
    public function getTypeMetadata($type);
}
