<?php

namespace Phoenix\Cache\Factories;

use Phoenix\Cache\Interfaces\HasCacheKey;
use Phoenix\Cache\Interfaces\HasCacheKeyPrefix;
use Phoenix\Logger\Interfaces\LoggerStrategy;
use Phoenix\Utils\Helpers\Str;

class HashedCacheKeyFactory implements HasCacheKey
{
    protected HasCacheKeyPrefix $cacheKeyPrefixProvider;

    public function __construct(HasCacheKeyPrefix $cacheKeyPrefixProvider, LoggerStrategy $logger)
    {
        $this->cacheKeyPrefixProvider = $cacheKeyPrefixProvider;
        $this->logger = $logger;
    }

    /**
     * Gets the cache key, using a hash
     *
     * @param array $context
     * @return string
     */
    public function getCacheKey(array $context): string
    {
        try {
            return $this->cacheKeyPrefixProvider->getCacheKeyPrefix() . '_' . Str::createHash($context);
        } catch (\ReflectionException $e) {
            $this->logger->logException($e);
        }
    }
}