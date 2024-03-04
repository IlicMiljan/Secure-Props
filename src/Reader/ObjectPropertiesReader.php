<?php

namespace IlicMiljan\SecureProps\Reader;

use IlicMiljan\SecureProps\Reader\Exception\ReaderException;
use ReflectionProperty;

/**
 * Provides an interface for reading properties from objects based on a specific
 * attribute.
 *
 * It is useful in scenarios where you need to introspect objects for properties
 * that have been annotated in a particular way, such as for encryption purposes.
 */
interface ObjectPropertiesReader
{
    /**
     * Retrieves an array of ReflectionProperty objects for properties in the
     * given object that are annotated with the specified attribute class.
     *
     *
     * @param object $object The object to introspect for annotated properties.
     * @param string $attributeClass The fully qualified class name of the
     *                               attribute to look for.
     * @return ReflectionProperty[] An array of ReflectionProperty objects
     *                              representing the properties that have been
     *                              marked with the specified attribute.
     *
     * @throws ReaderException Thrown if an error occurs during the property
     *                         reading process.
     */
    public function getPropertiesWithAttribute(object $object, string $attributeClass): array;
}
