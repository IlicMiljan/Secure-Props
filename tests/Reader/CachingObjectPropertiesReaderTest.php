<?php

namespace IlicMiljan\SecureProps\Tests\Reader;
namespace Tests\IlicMiljan\SecureProps\Reader;

use IlicMiljan\SecureProps\Cache\Cache;
use IlicMiljan\SecureProps\Cache\Exception\InvalidCacheKey;
use IlicMiljan\SecureProps\Reader\CachingObjectPropertiesReader;
use IlicMiljan\SecureProps\Reader\Exception\ObjectPropertyNotFound;
use IlicMiljan\SecureProps\Reader\ObjectPropertiesReader;
use IlicMiljan\SecureProps\Tests\Attribute\TestAttribute;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use ReflectionException;
use ReflectionProperty;

class CachingObjectPropertiesReaderTest extends TestCase
{
    /**
     * @var ObjectPropertiesReader&MockObject
     */
    private $objectPropertiesReader;
    /**
     * @var Cache&MockObject
     */
    private $cache;
    /**
     * @var CacheItemInterface&MockObject
     */
    private $cacheItem;
    private CachingObjectPropertiesReader $reader;

    protected function setUp(): void
    {
        $this->objectPropertiesReader = $this->createMock(ObjectPropertiesReader::class);
        $this->cache = $this->createMock(Cache::class);
        $this->cacheItem = $this->createMock(CacheItemInterface::class);

        $this->reader = new CachingObjectPropertiesReader(
            $this->objectPropertiesReader,
            $this->cache
        );
    }

    public function testGetPropertiesWithAttributeFromCache(): void
    {
        $object = new class {
            #[TestAttribute]
            /** @phpstan-ignore-next-line */
            private string $propertyName;
        };

        $this->cache->expects($this->once())
            ->method('get')
            ->willReturn(['propertyName']);

        $result = $this->reader->getPropertiesWithAttribute($object, TestAttribute::class);

        $this->assertCount(1, $result);
        $this->assertInstanceOf(ReflectionProperty::class, $result[0]);
    }

    /**
     * @throws ReflectionException
     */
    public function testGetPropertiesWithAttributeFromSource(): void
    {
        $object = new class {
            #[TestAttribute]
            /** @phpstan-ignore-next-line */
            private string $propertyName;
        };

        $properties = [new ReflectionProperty($object, 'propertyName')];

        $this->cache->expects($this->once())
            ->method('get')
            ->willReturnCallback(function ($key, $callback) {
                return $callback($this->cacheItem);
            });

        $this->objectPropertiesReader->expects($this->once())
            ->method('getPropertiesWithAttribute')
            ->willReturn($properties);

        $result = $this->reader->getPropertiesWithAttribute($object, TestAttribute::class);

        $this->assertCount(1, $result);
        $this->assertEquals('propertyName', $result[0]->getName());
    }

    public function testGetPropertiesWithAttributeThrowsInvalidCacheKey(): void
    {
        $this->expectException(InvalidCacheKey::class);

        $object = new class {
        };

        $this->cache->method('get')->willThrowException(new InvalidCacheKey('s'));

        $this->reader->getPropertiesWithAttribute($object, TestAttribute::class);
    }

    public function testGetPropertiesWithAttributeThrowsObjectPropertyNotFound(): void
    {
        $this->expectException(ObjectPropertyNotFound::class);

        $object = new class {
        };

        $propertyNames = ['nonExistentProperty'];

        $this->cache->expects($this->once())
            ->method('get')
            ->willReturn($propertyNames);

        $this->reader->getPropertiesWithAttribute($object, TestAttribute::class);
    }
}
