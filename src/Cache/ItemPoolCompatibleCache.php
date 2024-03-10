<?php

namespace IlicMiljan\SecureProps\Cache;

use IlicMiljan\SecureProps\Cache\Exception\InvalidCacheKey;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

class ItemPoolCompatibleCache implements Cache
{
    private CacheItemPoolInterface $cachePool;

    public function __construct(CacheItemPoolInterface $cachePool)
    {
        $this->cachePool = $cachePool;
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, callable $callable, ?int $ttl = null): mixed
    {
        try {
            $cacheItem = $this->cachePool->getItem($key);
        } catch (InvalidArgumentException $e) {
            throw new InvalidCacheKey($key, $e);
        }

        if (!$cacheItem->isHit()) {
            $cacheItem->set($callable($cacheItem));
            $cacheItem->expiresAfter($ttl);

            $this->cachePool->save($cacheItem);
        }

        return $cacheItem->get();
    }
}
