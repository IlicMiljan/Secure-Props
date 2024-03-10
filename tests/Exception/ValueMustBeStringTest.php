<?php

namespace IlicMiljan\SecureProps\Tests\Exception;

use IlicMiljan\SecureProps\Exception\EncryptionServiceException;
use IlicMiljan\SecureProps\Exception\ValueMustBeString;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class ValueMustBeStringTest extends TestCase
{
    private string $type;

    protected function setUp(): void
    {
        $this->type = 'object';
    }

    public function testCanBeCreated(): void
    {
        $exception = new ValueMustBeString($this->type);

        $this->assertInstanceOf(ValueMustBeString::class, $exception);
    }

    public function testReturnsType(): void
    {
        $exception = new ValueMustBeString($this->type);

        $this->assertEquals($this->type, $exception->getType());
    }

    public function testPreviousExceptionIsStored(): void
    {
        $previous = new RuntimeException('Previous exception');
        $exception = new ValueMustBeString($this->type, $previous);

        $this->assertSame($previous, $exception->getPrevious());
    }

    public function testImplementsEncryptionServiceExceptionInterface(): void
    {
        $exception = new ValueMustBeString($this->type);

        $this->assertInstanceOf(EncryptionServiceException::class, $exception);
    }
}
