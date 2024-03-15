<?php

namespace IlicMiljan\SecureProps\Tests\Attribute;

use IlicMiljan\SecureProps\Attribute\Encrypted;
use PHPUnit\Framework\TestCase;

class EncryptedTest extends TestCase
{
    public function testCanBeCreated(): void
    {
        $encrypted = new Encrypted();

        $this->assertInstanceOf(Encrypted::class, $encrypted);
    }
    public function testCanRetrieveSpecifiedPlaceholder(): void
    {
        $placeholder = "********";
        $encryptedAttribute = new Encrypted(placeholder: $placeholder);

        $this->assertSame($placeholder, $encryptedAttribute->getPlaceholder());
    }

    public function testDefaultPlaceholderIsNull(): void
    {
        $encryptedAttribute = new Encrypted();

        $this->assertNull($encryptedAttribute->getPlaceholder());
    }
}
