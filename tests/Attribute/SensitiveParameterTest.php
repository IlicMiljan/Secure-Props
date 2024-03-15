<?php

namespace IlicMiljan\SecureProps\Tests\Attribute;

use IlicMiljan\SecureProps\Attribute\Encrypted;
use PHPUnit\Framework\TestCase;
use SensitiveParameter;

class SensitiveParameterTest extends TestCase
{

    protected function setUp(): void
    {
        if (PHP_VERSION_ID >= 80200) {
            $this->markTestSkipped(
                'All tests in this file have been skipped because they are do not apply to PHP 8.2 or higher.'
            );
        }
    }

    public function testCanBeCreated(): void
    {
        $encrypted = new SensitiveParameter();

        $this->assertInstanceOf(SensitiveParameter::class, $encrypted);
    }
}
