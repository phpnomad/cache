<?php

namespace Phoenix\Cache;

use Phoenix\Cache\Interfaces\CacheStrategy;
use Phoenix\Cache\Exceptions\CachedItemNotFoundException;

class Cache
{
    protected CacheStrategy $strategy;
    protected ?int $defaultTTL;

    public function __construct(CacheStrategy $strategy, ?int $defaultTTL = null)
    {
        $this->strategy = $strategy;
        $this->defaultTTL = $defaultTTL;
    }

    /**
     * Fetches an item from the cache or loads it using a callable.
     *
     * @param string $key The cache key
     * @param callable $setter The setter that sets the cache value
     * @param ?int $ttl Time to live for the cached item, null for default TTL
     *
     * @return mixed
     */
    public function load(string $key, callable $setter, ?int $ttl = null)
    {
        try {
            $result = $this->strategy->get($key);
        } catch (CachedItemNotFoundException $e) {
            $result = $setter();
            $this->strategy->set($key, $result, $ttl ?? $this->defaultTTL);
        }

        return $result;
    }
}