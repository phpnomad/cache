# phpnomad/cache

[![Latest Version](https://img.shields.io/packagist/v/phpnomad/cache.svg)](https://packagist.org/packages/phpnomad/cache)
[![Total Downloads](https://img.shields.io/packagist/dt/phpnomad/cache.svg)](https://packagist.org/packages/phpnomad/cache)
[![PHP Version](https://img.shields.io/packagist/php-v/phpnomad/cache.svg)](https://packagist.org/packages/phpnomad/cache)
[![License](https://img.shields.io/packagist/l/phpnomad/cache.svg)](https://packagist.org/packages/phpnomad/cache)

`phpnomad/cache` defines the caching contract for PHPNomad applications. It gives you a backend-agnostic `CacheStrategy` interface, a `CachePolicy` contract for deciding what gets cached and for how long, a `CacheableService` that wires the two together with event broadcasting, and helpers for key generation and in-memory instance caching.

The package itself is an abstraction. The concrete backend comes from an integration package like `phpnomad/symfony-cache-integration`, which adapts Symfony's Cache component to this interface. Your application code depends only on the interface, so swapping the backend later never touches your business logic. The cache layer in this package powers [Siren](https://sirenaffiliates.com) and other production PHPNomad apps.

## Installation

```bash
composer require phpnomad/cache
```

You will also need a concrete cache backend such as `phpnomad/symfony-cache-integration` bound to `CacheStrategy` in your container.

## Quick Start

Inject `CacheStrategy` into any service that needs a read-through cache. The interface exposes `get`, `set`, `exists`, `delete`, and `clear`. A miss on `get` throws `CachedItemNotFoundException`, which you catch to fall back to your source of truth.

```php
<?php

namespace MyApp\Widgets;

use PHPNomad\Cache\Exceptions\CachedItemNotFoundException;
use PHPNomad\Cache\Interfaces\CacheStrategy;

class WidgetRepository
{
    protected const CACHE_TTL = 3600;

    protected CacheStrategy $cache;
    protected WidgetApiClient $api;

    public function __construct(CacheStrategy $cache, WidgetApiClient $api)
    {
        $this->cache = $cache;
        $this->api = $api;
    }

    public function findById(int $id): Widget
    {
        $key = 'widget_' . $id;

        try {
            return $this->cache->get($key);
        } catch (CachedItemNotFoundException $e) {
            $widget = $this->api->fetchWidget($id);
            $this->cache->set($key, $widget, self::CACHE_TTL);

            return $widget;
        }
    }

    public function invalidate(int $id): void
    {
        $this->cache->delete('widget_' . $id);
    }
}
```

The container resolves `CacheStrategy` to whichever concrete implementation you've bound at bootstrap time, so `WidgetRepository` never references Symfony, Redis, or any specific cache backend. If you want the read-through pattern pre-built with event broadcasting on misses, you can compose `CacheableService` instead of writing the try/catch yourself.

## Key Concepts

- `CacheStrategy` defines the backend contract with `get`, `set`, `delete`, `exists`, and `clear`.
- `CachePolicy` decides whether a given operation should be cached, computes the TTL, and flags when a write operation should invalidate the cache.
- `CacheableService` composes a `CacheStrategy` and a `CachePolicy` into a read-through helper that broadcasts a `CacheMissed` event on misses, letting event listeners react to cache behavior.
- `HashedCacheKeyFactory` generates cache keys from a context array using a configurable prefix and a hashed suffix, which keeps keys stable and short even when the context is large.
- `WithInstanceCache` is a trait for per-instance in-memory caching, useful for collapsing repeated lookups inside a single request.

## Documentation

Full documentation lives at [phpnomad.com](https://phpnomad.com).

## License

Licensed under the [MIT License](LICENSE.txt).
