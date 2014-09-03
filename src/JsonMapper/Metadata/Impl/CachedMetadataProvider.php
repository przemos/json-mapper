<?php

namespace JsonMapper\Metadata\Impl;

use JsonMapper\Cache\Api\CacheProviderInterface;
use JsonMapper\Metadata\Api\MetadataProviderInterface;
use JsonMapper\Metadata\Api\TypeMetadataInterface;

/**
 * MetadataProvider that decorates other metadata provider implementations so that
 * each metadata is cached per type
 */
class CachedMetadataProvider implements MetadataProviderInterface
{
    /** @var  MetadataProviderInterface $wrappedProvider */
    private $wrappedProvider;

    /** @var  CacheProviderInterface $cacheProvider */
    private $cache;

    public function __construct(MetadataProviderInterface $provider, CacheProviderInterface $cacheProvider)
    {
        $this->wrappedProvider = $provider;
        $this->cache = $cacheProvider;
    }

    /**
     * Returns type metadata for a given type
     *
     * @param string $type
     *
     * @return TypeMetadataInterface
     */
    public function getTypeMetadata($type)
    {
        $metadata = $this->cache->get($type);
        if (is_null($metadata)) {
            $metadata = $this->wrappedProvider->getTypeMetadata($type);
            $this->cache->put($type, $metadata);
        }
        return $metadata;
    }
}
