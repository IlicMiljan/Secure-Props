<?php

namespace IlicMiljan\SecureProps\Tests\Cache;

use IlicMiljan\SecureProps\Cache\Exception\InvalidCacheKey;
use IlicMiljan\SecureProps\Cache\ItemPoolCompatibleCache;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

class ItemPoolCompatibleCacheTest extends TestCase
{
    /**
     * @var CacheItemPoolInterface&MockObject
     */
    private $cachePool;
    /**
     * @var CacheItemInterface&MockObject
     */
    private $cacheItem;
    private ItemPoolCompatibleCache $cache;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cachePool = $this->createMock(CacheItemPoolInterface::class);
        $this->cacheItem = $this->createMock(CacheItemInterface::class);
        $this->cache = new ItemPoolCompatibleCache($this->cachePool);
    }

    public function testGetWithCacheHit(): void
    {
        $key = 'testKey';
        $expectedValue = 'testValue';

        $this->cachePool->expects($this->once())
            ->method('getItem')
            ->with($key)
            ->willReturn($this->cacheItem);

        $this->cacheItem->expects($this->once())
            ->method('isHit')
            ->willReturn(true);
        $this->cacheItem->expects($this->once())
            ->method('get')
            ->willReturn($expectedValue);

        $result = $this->cache->get($key, function () {
            return 'valueFromCallable';
        });

        $this->assertEquals($expectedValue, $result);
    }

    public function testGetWithCacheMiss(): void
    {
        $key = 'testKey';
        $valueFromCallable = 'valueFromCallable';

        $this->cachePool->method('getItem')->willReturn($this->cacheItem);
        $this->cacheItem->method('isHit')->willReturn(false);

        // Ensure callable is executed to generate value on cache miss
        $this->cacheItem->expects($this->once())->method('set')->with($valueFromCallable)
            ->willReturnCallback(function () use ($valueFromCallable) {
                $this->cacheItem->method('get')->willReturn($valueFromCallable);
                return $this->cacheItem;
            });

        $this->cacheItem->expects($this->once())->method('expiresAfter')->with($this->isNull());
        $this->cachePool->expects($this->once())->method('save')->with($this->cacheItem);

        $result = $this->cache->get($key, function () use ($valueFromCallable) {
            return $valueFromCallable;
        });

        $this->assertEquals($valueFromCallable, $result);
    }

    public function testGetThrowsInvalidCacheKey(): void
    {
        $this->expectException(InvalidCacheKey::class);

        $this->cachePool->method('getItem')->willThrowException($this->createMock(InvalidArgumentException::class));

        $this->cache->get('invalidKey', function () {
            return 'value';
        });
    }

    public function testGetWithTTL(): void
    {
        $key = 'testKey';
        $ttl = 3600;
        $valueFromCallable = 'valueFromCallable';

        $this->cachePool->method('getItem')->willReturn($this->cacheItem);
        $this->cacheItem->method('isHit')->willReturn(false);

        $this->cacheItem->expects($this->once())->method('set')->with($valueFromCallable)
            ->willReturnCallback(function () use ($valueFromCallable) {
                $this->cacheItem->method('get')->willReturn($valueFromCallable);
                return $this->cacheItem;
            });

        $this->cacheItem->expects($this->once())->method('expiresAfter')->with($ttl);
        $this->cachePool->expects($this->once())->method('save')->with($this->cacheItem);

        $result = $this->cache->get($key, function () use ($valueFromCallable) {
            return $valueFromCallable;
        }, $ttl);

        $this->assertEquals($valueFromCallable, $result);
    }
}
