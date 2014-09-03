<?php

namespace JsonMapper\Cache\Impl;

use JsonMapper\Cache\Api\CacheProviderInterface;

/**
 * Cache based on simple array
 */
class ArrayCacheProvider implements CacheProviderInterface
{
    private $cache;

    /**
     * @param string $key
     * @param mixed $object
     */
    public function put($key, $object)
    {
        $this->cache[$key] = $object;
    }

    /**
     * @param string $type
     *
     * @return mixed
     */
    public function get($type)
    {
        return isset($this->cache[$type]) ? $this->cache[$type] : null;
    }
}
