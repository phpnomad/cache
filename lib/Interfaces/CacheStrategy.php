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
}