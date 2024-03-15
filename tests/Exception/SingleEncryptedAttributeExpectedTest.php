<?php

namespace IlicMiljan\SecureProps\Tests\Exception;

use IlicMiljan\SecureProps\Exception\EncryptionServiceException;
use IlicMiljan\SecureProps\Exception\SingleEncryptedAttributeExpected;
use IlicMiljan\SecureProps\Exception\ValueMustBeObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class SingleEncryptedAttributeExpectedTest extends TestCase
{
    private int $count;

    protected function setUp(): void
    {
        $this->count = 1;
    }

    public function testCanBeCreated(): void
    {
        $exception = new SingleEncryptedAttributeExpected($this->count);

        $this->assertInstanceOf(SingleEncryptedAttributeExpected::class, $exception);
    }

    public function testReturnsCount(): void
    {
        $exception = new SingleEncryptedAttributeExpected($this->count);

        $this->assertEquals($this->count, $exception->getCount());
    }

    public function testPreviousExceptionIsStored(): void
    {
        $previous = new RuntimeException('Previous exception');
        $exception = new SingleEncryptedAttributeExpected($this->count, $previous);

        $this->assertSame($previous, $exception->getPrevious());
    }

    public function testImplementsEncryptionServiceExceptionInterface(): void
    {
        $exception = new SingleEncryptedAttributeExpected($this->count);

        $this->assertInstanceOf(EncryptionServiceException::class, $exception);
    }
}
