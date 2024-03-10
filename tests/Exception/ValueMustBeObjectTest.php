<?php

namespace IlicMiljan\SecureProps\Tests\Exception;

use IlicMiljan\SecureProps\Exception\EncryptionServiceException;
use IlicMiljan\SecureProps\Exception\ValueMustBeObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class ValueMustBeObjectTest extends TestCase
{
    private string $type;

    protected function setUp(): void
    {
        $this->type = 'string';
    }

    public function testCanBeCreated(): void
    {
        $exception = new ValueMustBeObject($this->type);

        $this->assertInstanceOf(ValueMustBeObject::class, $exception);
    }

    public function testReturnsType(): void
    {
        $exception = new ValueMustBeObject($this->type);

        $this->assertEquals($this->type, $exception->getType());
    }

    public function testPreviousExceptionIsStored(): void
    {
        $previous = new RuntimeException('Previous exception');
        $exception = new ValueMustBeObject($this->type, $previous);

        $this->assertSame($previous, $exception->getPrevious());
    }

    public function testImplementsEncryptionServiceExceptionInterface(): void
    {
        $exception = new ValueMustBeObject($this->type);

        $this->assertInstanceOf(EncryptionServiceException::class, $exception);
    }
}
