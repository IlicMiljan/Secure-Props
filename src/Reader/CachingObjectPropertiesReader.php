<?php

namespace IlicMiljan\SecureProps\Reader;

use IlicMiljan\SecureProps\Cache\Cache;
use IlicMiljan\SecureProps\Reader\Exception\ObjectPropertyNotFound;
use Psr\Cache\CacheItemInterface;
use ReflectionException;
use ReflectionProperty;

class CachingObjectPropertiesReader implements ObjectPropertiesReader
{
    private const CACHE_TTL_DEFAULT = null;

    public function __construct(
        private ObjectPropertiesReader $objectPropertiesReader,
        private Cache $cache,
        private ?int $cacheTtl = self::CACHE_TTL_DEFAULT
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getPropertiesWithAttribute(object $object, string $attributeClass): array
    {
        $propertyArray = $this->cache->get(
            $this->getCacheKey($object, $attributeClass),
            function (CacheItemInterface $cacheItem) use ($object, $attributeClass) {
                $propertiesWithAttribute = $this->objectPropertiesReader->getPropertiesWithAttribute(
                    $object,
                    $attributeClass
                );

                return $this->getCacheablePropertiesArray($propertiesWithAttribute);
            },
            $this->cacheTtl
        );

        return $this->loadRuntimeReflectionProperties($object, $propertyArray);
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
        try {
            return array_map(function ($propertyName) use ($object) {
                return new ReflectionProperty($object, $propertyName);
            }, $propertyNames);
        } catch (ReflectionException $e) {
            throw new ObjectPropertyNotFound($object::class, $e);
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
}
