<?php

namespace IlicMiljan\SecureProps\Tests\Reader;

use IlicMiljan\SecureProps\Reader\CachingObjectPropertiesReader;
use IlicMiljan\SecureProps\Reader\Exception\InvalidCacheKey;
use IlicMiljan\SecureProps\Reader\Exception\InvalidCacheValueDataType;
use IlicMiljan\SecureProps\Reader\Exception\ObjectPropertyNotFound;
use IlicMiljan\SecureProps\Reader\ObjectPropertiesReader;
use IlicMiljan\SecureProps\Tests\Attribute\TestAttribute;
use IlicMiljan\SecureProps\Tests\Reader\Exception\TestCacheException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use ReflectionException;

class CachingObjectPropertiesReaderTest extends TestCase
{
    /**
     * @var ObjectPropertiesReader&MockObject
     */
    private $objectPropertiesReader;
    /**
     * @var CacheItemPoolInterface&MockObject
     */
    private $cacheItemPool;
    /**
     * @var CacheItemInterface&MockObject
     */
    private $cacheItem;
    private CachingObjectPropertiesReader $reader;

    protected function setUp(): void
    {
        $this->objectPropertiesReader = $this->createMock(ObjectPropertiesReader::class);
        $this->cacheItemPool = $this->createMock(CacheItemPoolInterface::class);
        $this->cacheItem = $this->createMock(CacheItemInterface::class);
        $this->cacheItemPool->method('getItem')->willReturn($this->cacheItem);

        $this->reader = new CachingObjectPropertiesReader(
            $this->objectPropertiesReader,
            $this->cacheItemPool
        );
    }

    public function testGetPropertiesWithAttributeReturnsCachedValueOnHit(): void
    {
        $object = new class () {
            #[TestAttribute]
            /** @phpstan-ignore-next-line */
            private string $propertyWithAttribute;
        };

        $this->cacheItem->method('isHit')->willReturn(true);
        $this->cacheItem->method('get')->willReturn(['propertyWithAttribute']);

        $this->objectPropertiesReader->expects($this->never())->method('getPropertiesWithAttribute');

        $result = $this->reader->getPropertiesWithAttribute($object, TestAttribute::class);
        $this->assertNotEmpty($result);
    }

    public function testGetPropertiesWithAttributeFetchesFromDelegateOnCacheMiss(): void
    {
        $object = new class () {
        };

        $this->cacheItem->method('isHit')->willReturn(false);
        $this->objectPropertiesReader->method('getPropertiesWithAttribute')->willReturn([]);

        $result = $this->reader->getPropertiesWithAttribute($object, TestAttribute::class);
        $this->assertIsArray($result);
    }

    public function testGetPropertiesWithAttributeThrowsInvalidCacheKey(): void
    {
        $this->expectException(InvalidCacheKey::class);

        $this->cacheItemPool->method('getItem')->willThrowException(new TestCacheException());

        $this->reader->getPropertiesWithAttribute(new class () {
        }, TestAttribute::class);
    }

    public function testGetPropertiesWithAttributeThrowsInvalidCacheValueDataType(): void
    {
        $this->expectException(InvalidCacheValueDataType::class);

        $this->cacheItem->method('isHit')->willReturn(true);
        $this->cacheItem->method('get')->willReturn(null);

        $this->reader->getPropertiesWithAttribute(
            new class () {
            },
            TestAttribute::class
        );
    }

    public function testGetPropertiesWithAttributeThrowsObjectPropertyNotFound(): void
    {
        $this->expectException(ObjectPropertyNotFound::class);

        $this->cacheItem->method('isHit')->willReturn(true);
        $this->cacheItem->method('get')->willReturn(['nonExistentProperty']);

        // Configure the mocked ObjectPropertiesReader to throw a ReflectionException
        // This simulates the scenario where a property does not exist on the object
        $this->objectPropertiesReader->method('getPropertiesWithAttribute')
            ->willThrowException(new ReflectionException());

        $reader = new CachingObjectPropertiesReader($this->objectPropertiesReader, $this->cacheItemPool);
        $reader->getPropertiesWithAttribute(
            new class () {
            },
            TestAttribute::class
        );
    }
}
