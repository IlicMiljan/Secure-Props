<?php

namespace IlicMiljan\SecureProps\Tests\Cipher\Exception;

use IlicMiljan\SecureProps\Cipher\Exception\CipherException;
use IlicMiljan\SecureProps\Cipher\Exception\InvalidKeyLength;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class InvalidKeyLengthTest extends TestCase
{
    private int $expectedLength;

    protected function setUp(): void
    {
        $this->expectedLength = 32;
    }

    public function testCanBeCreated(): void
    {
        $exception = new InvalidKeyLength($this->expectedLength);

        $this->assertInstanceOf(InvalidKeyLength::class, $exception);
    }

    public function testReturnsExpectedLength(): void
    {
        $exception = new InvalidKeyLength($this->expectedLength);

        $this->assertEquals($this->expectedLength, $exception->getExpectedLength());
    }

    public function testPreviousExceptionIsStored(): void
    {
        $previous = new RuntimeException('Previous exception');
        $exception = new InvalidKeyLength($this->expectedLength, $previous);

        $this->assertSame($previous, $exception->getPrevious());
    }

    public function testImplementsCipherExceptionInterface(): void
    {
        $exception = new InvalidKeyLength($this->expectedLength);

        $this->assertInstanceOf(CipherException::class, $exception);
    }
}
