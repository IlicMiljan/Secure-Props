<?php

namespace IlicMiljan\SecureProps\Tests\Cipher\Exception;

use IlicMiljan\SecureProps\Cipher\Exception\CipherException;
use IlicMiljan\SecureProps\Cipher\Exception\FailedGeneratingInitializationVector;
use LogicException;
use PHPUnit\Framework\TestCase;

class FailedGeneratingInitializationVectorTest extends TestCase
{
    private int $length;

    protected function setUp(): void
    {
        $this->length = 16;
    }

    public function testCanBeCreated(): void
    {
        $exception = new FailedGeneratingInitializationVector($this->length);

        $this->assertInstanceOf(FailedGeneratingInitializationVector::class, $exception);
    }

    public function testReturnsLength(): void
    {
        $exception = new FailedGeneratingInitializationVector($this->length);

        $this->assertEquals($this->length, $exception->getLength());
    }

    public function testPreviousExceptionIsStored(): void
    {
        $previous = new LogicException('Previous exception');
        $exception = new FailedGeneratingInitializationVector($this->length, $previous);

        $this->assertSame($previous, $exception->getPrevious());
    }

    public function testImplementsCipherExceptionInterface(): void
    {
        $exception = new FailedGeneratingInitializationVector($this->length);

        $this->assertInstanceOf(CipherException::class, $exception);
    }
}
