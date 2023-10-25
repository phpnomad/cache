<?php

namespace Phoenix\Cache\Interfaces;

interface HasDefaultTtl
{
    /**
     * Returns the default cache TTL
     *
     * @return int
     */
    public function getDefaultTtl(): ?int;
}