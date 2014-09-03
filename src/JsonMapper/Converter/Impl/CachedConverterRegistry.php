<?php

namespace JsonMapper\Converter\Impl;

use JsonMapper\Cache\Api\CacheProviderInterface;
use JsonMapper\Converter\Api\ConverterInterface;
use JsonMapper\Converter\Api\ConverterRegistryInterface;
use JsonMapper\Metadata\Api\TypeMetadataInterface;

/**
 * ConverterRegistry that decorates other converter registry implementations so that
 * each converter type for a choice is remembered
 */
class CachedConverterRegistry implements ConverterRegistryInterface
{
    /** @var ConverterRegistryInterface */
    private $wrappedRegistry;

    /** @var  CacheProviderInterface */
    private $cache;


    public function __construct(ConverterRegistryInterface $registry, CacheProviderInterface $cacheProvider)
    {
        $this->wrappedRegistry = $registry;
        $this->cache = $cacheProvider;
    }

    /**
     * @param TypeMetadataInterface $metadata
     *
     * @return ConverterInterface
     */
    public function getConverterFor(TypeMetadataInterface $metadata)
    {
        $key = serialize($metadata);
        $converter = $this->cache->get($key);
        if (is_null($converter)) {
            $converter = $this->wrappedRegistry->getConverterFor($metadata);
            $this->cache->put($key, $converter);
        }
        return $converter;
    }

    /**
     * @param ConverterInterface $converter
     */
    public function register(ConverterInterface $converter)
    {
        return $this->wrappedRegistry->register($converter);
    }
}
