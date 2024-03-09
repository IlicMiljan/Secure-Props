<?php

namespace IlicMiljan\SecureProps\Reader;

use IlicMiljan\SecureProps\Cache\Exception\InvalidCacheKey;
use IlicMiljan\SecureProps\Reader\Exception\InvalidCacheValueDataType;
use IlicMiljan\SecureProps\Reader\Exception\ObjectPropertyNotFound;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use ReflectionException;
use ReflectionObject;
use ReflectionProperty;

class CachingObjectPropertiesReader implements ObjectPropertiesReader
{
    public function __construct(
        private ObjectPropertiesReader $objectPropertiesReader,
        private CacheItemPoolInterface $cache
    ) {
    }

    /**
     * @throws InvalidCacheKey
     * @throws InvalidCacheValueDataType
     * @throws ObjectPropertyNotFound
     */
    public function getPropertiesWithAttribute(object $object, string $attributeClass): array
    {
        $cachedProperties = $this->getCacheItem($this->getCacheKey($object, $attributeClass));

        if ($cachedProperties->isHit()) {
            $this->ensureCacheItemValueIsArray($cachedProperties);

            /** @var string[] $cachedPropertiesValue */
            $cachedPropertiesValue = $cachedProperties->get();

            return $this->loadRuntimeReflectionProperties($object, $cachedPropertiesValue);
        }

        $propertiesWithAttribute = $this->objectPropertiesReader->getPropertiesWithAttribute(
            $object,
            $attributeClass
        );

        $this->updateCache(
            $cachedProperties,
            $this->getCacheablePropertiesArray($propertiesWithAttribute)
        );

        return $propertiesWithAttribute;
    }

    private function getCacheKey(object $object, string $attributeClass): string
    {
        return hash('md5', sprintf("%s|%s", $object::class, $attributeClass));
    }

    /**
     * @param object $object
     * @param string[] $propertyNames
     *
     * @return ReflectionProperty[]
     *
     * @throws ObjectPropertyNotFound
     */
    private function loadRuntimeReflectionProperties(object $object, array $propertyNames): array
    {
        $reflection = new ReflectionObject($object);

        try {
            return array_map(function ($propertyName) use ($reflection) {
                return $reflection->getProperty($propertyName);
            }, $propertyNames);
        } catch (ReflectionException $e) {
            throw new ObjectPropertyNotFound($object::class, $e);
        }
    }

    /**
     * @param string $cacheKey
     * @return CacheItemInterface
     *
     * @throws InvalidCacheKey
     */
    private function getCacheItem(string $cacheKey): CacheItemInterface
    {
        try {
            return $this->cache->getItem($cacheKey);
        } catch (InvalidArgumentException $e) {
            throw new InvalidCacheKey($cacheKey, $e);
        }
    }

    /**
     * @param ReflectionProperty[] $properties
     * @return string[]
     */
    private function getCacheablePropertiesArray(array $properties): array
    {
        return  array_map(function ($property) {
            return $property->getName();
        }, $properties);
    }

    /**
     * @param CacheItemInterface $cacheItem
     * @return void
     *
     * @throws InvalidCacheValueDataType
     */
    private function ensureCacheItemValueIsArray(CacheItemInterface $cacheItem): void
    {
        $cachedValue = $cacheItem->get();

        if (is_array($cachedValue)) {
            return;
        }

        throw new InvalidCacheValueDataType(gettype($cachedValue), 'array');
    }

    private function updateCache(CacheItemInterface $cacheItem, mixed $data): void
    {
        $cacheItem->set($data);
        $cacheItem->expiresAfter(3600);

        $this->cache->save($cacheItem);
    }
}
