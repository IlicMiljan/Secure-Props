<?php

namespace IlicMiljan\SecureProps\Cache;

use IlicMiljan\SecureProps\Cache\Exception\InvalidCacheKey;
use Psr\Cache\CacheItemInterface;

interface Cache
{
    /**
     * Fetches a value from the cache or computes it if not found.
     *
     * @template T
     *
     * @param string   $key      The cache key.
     * @param (callable(CacheItemInterface):T) $callable A callable that
     *                           computes the value if it's not found in the
     *                           cache.
     * @param int|null $ttl      The time-to-live for the cache entry in seconds.
     *
     * @return T The cached or computed value.
     * @throws InvalidCacheKey When $key is not valid.
     */
    public function get(string $key, callable $callable, ?int $ttl = null): mixed;
}
