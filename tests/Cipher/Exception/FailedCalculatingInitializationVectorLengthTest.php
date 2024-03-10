<?php

namespace IlicMiljan\SecureProps\Tests\Cipher\Exception;

use IlicMiljan\SecureProps\Cipher\Exception\CipherException;
use IlicMiljan\SecureProps\Cipher\Exception\FailedCalculatingInitializationVectorLength;
use LogicException;
use PHPUnit\Framework\TestCase;

class FailedCalculatingInitializationVectorLengthTest extends TestCase
{
    private string $cipher;

    protected function setUp(): void
    {
        $this->cipher = 'AES-256-GCM';
    }

    public function testCanBeCreated(): void
    {
        $exception = new FailedCalculatingInitializationVectorLength($this->cipher);

        $this->assertInstanceOf(FailedCalculatingInitializationVectorLength::class, $exception);
    }

    public function testReturnsCipher(): void
    {
        $exception = new FailedCalculatingInitializationVectorLength($this->cipher);

        $this->assertEquals($this->cipher, $exception->getCipher());
    }

    public function testPreviousExceptionIsStored(): void
    {
        $previous = new LogicException('Previous exception');
        $exception = new FailedCalculatingInitializationVectorLength($this->cipher, $previous);

        $this->assertSame($previous, $exception->getPrevious());
    }

    public function testImplementsCipherExceptionInterface(): void
    {
        $exception = new FailedCalculatingInitializationVectorLength($this->cipher);

        $this->assertInstanceOf(CipherException::class, $exception);
    }
}
