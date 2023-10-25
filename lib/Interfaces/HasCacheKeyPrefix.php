<?php

namespace Phoenix\Cache\Interfaces;

interface HasCacheKeyPrefix
{
    public function getCacheKeyPrefix(): string;
}