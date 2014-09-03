<?php

namespace JsonMapper\Cache\Api;

/** Generalised interface for cache */
interface CacheProviderInterface
{
    /**
     * @param string $key
     * @param mixed $object
     */
    public function put($key, $object);

    /**
     * @param string $type
     *
     * @return mixed
     */
    public function get($type);
}
