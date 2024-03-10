<?php

namespace IlicMiljan\SecureProps\Reader;

use ReflectionObject;
use ReflectionProperty;

class RuntimeObjectPropertiesReader implements ObjectPropertiesReader
{
    /**
     * @inheritDoc
     */
    public function getPropertiesWithAttribute(object $object, string $attributeClass): array
    {
        $propertiesWithAttribute = [];

        foreach ($this->getObjectProperties($object) as $property) {
            if (!$this->propertyHasAttribute($property, $attributeClass)) {
                continue;
            }

            $propertiesWithAttribute[] = $property;
        }

        return $propertiesWithAttribute;
    }

    /**
     * @param object $object
     * @return ReflectionProperty[]
     */
    private function getObjectProperties(object $object): array
    {
        $reflection = new ReflectionObject($object);

        return $reflection->getProperties();
    }

    private function propertyHasAttribute(ReflectionProperty $property, string $attributeClass): bool
    {
        return count($property->getAttributes($attributeClass)) > 0;
    }
}
