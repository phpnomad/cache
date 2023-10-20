<?php

namespace Phoenix\Cache\Interfaces;

use Phoenix\Cache\Exceptions\CachedItemNotFoundException;

interface CacheStrategy
{
    /**
     * Get an item from the cache.
     * 
     * @param string $key
     * @return mixed
     * @throws CachedItemNotFoundException
     */
    public function get(string $key);

    /**
     * Set the item to the cache
     * 
     * @param string $key the cache key
     * @param mixed $value The cache value
     * @param ?int $ttl The duration. If null, no expiration.
     */
    public function set(string $key, $value, ?int $ttl): void;

    /**
     * Delete an item from the cache.
     * 
     * @param string $key
     */
    public function delete(string $key): void;

    /**
     * Fetches an item from the cache or loads it using a callable.
     *
     * @param string $key The cache key
     * @param callable $setter The setter that sets the cache value
     * @param ?int $ttl Time to live for the cached item, null for default TTL
     *
     * @return mixed
     */
    public function load(string $key, callable $setter, ?int $ttl = null);

    /**
     * Returns true if the specified cache key exists.
     *
     * @param string $key
     * @return bool
     */
    public function exists(string $key): bool;

    /**
     * Completely clears the cache.
     *
     * @return void
     */
    public function clear(): void;
}