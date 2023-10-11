<?php

namespace Phoenix\Cache\Traits;

namespace Phoenix\Cache\Traits;

use Phoenix\Cache\Exceptions\CachedItemNotFoundException;

trait CanLoadCacheTrait
{
    /**
     * Get an item from the cache.
     *
     * @param string $key
     * @return mixed
     * @throws CachedItemNotFoundException
     */
    abstract public function get(string $key);

    /**
     * Set the item to the cache
     *
     * @param string $key the cache key
     * @param mixed $value The cache value
     * @param ?int $ttl The duration. If null, no expiration.
     */
    abstract public function set(string $key, $value, ?int $ttl): void;

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
            $result = $this->get($key);
        } catch (CachedItemNotFoundException $e) {
            $result = $setter();
            $this->set($key, $result, $ttl);
        }

        return $result;
    }
}