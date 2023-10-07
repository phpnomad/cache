<?php

namespace Phoenix\Cache\Repository;

use Phoenix\Cache\Exceptions\CachedItemNotFoundException;
use Phoenix\Cache\Interfaces\CacheStrategy;

class Cache
{
    protected CacheStrategy $strategy;

    public function __construct(CacheStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * @param CacheStrategy $strategy
     * @return static
     */
    public static function use(CacheStrategy $strategy)
    {
        return new self($strategy);
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
            $this->strategy->set($key, $result, $ttl);
        }

        return $result;
    }
}