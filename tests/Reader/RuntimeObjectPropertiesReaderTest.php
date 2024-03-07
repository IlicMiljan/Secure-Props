<?php

namespace IlicMiljan\SecureProps\Tests\Reader;

use Attribute;
use IlicMiljan\SecureProps\Reader\RuntimeObjectPropertiesReader;
use IlicMiljan\SecureProps\Tests\Attribute\TestAttribute;
use PHPUnit\Framework\TestCase;

class RuntimeObjectPropertiesReaderTest extends TestCase
{
    private RuntimeObjectPropertiesReader $reader;

    protected function setUp(): void
    {
        $this->reader = new RuntimeObjectPropertiesReader();
    }

    public function testGetPropertiesWithAttributeReturnsEmptyArrayWhenNoPropertiesHaveAttribute(): void
    {
        $object = new class {
            /** @phpstan-ignore-next-line */
            private string $propertyWithoutAttribute;
        };

        $properties = $this->reader->getPropertiesWithAttribute($object, TestAttribute::class);

        $this->assertEmpty($properties);
    }

    public function testGetPropertiesWithAttributeIdentifiesPropertiesWithAttribute(): void
    {
        $object = new class {
            #[TestAttribute]
            /** @phpstan-ignore-next-line */
            private string $propertyWithAttribute;

            /** @phpstan-ignore-next-line */
            private string $propertyWithoutAttribute;
        };

        $properties = $this->reader->getPropertiesWithAttribute($object, TestAttribute::class);

        $this->assertCount(1, $properties);
        $this->assertEquals('propertyWithAttribute', $properties[0]->getName());
    }

    public function testGetPropertiesWithAttributeHandlesObjectsWithNoProperties(): void
    {
        $object = new class {
        };

        $properties = $this->reader->getPropertiesWithAttribute($object, TestAttribute::class);

        $this->assertEmpty($properties);
    }
}
