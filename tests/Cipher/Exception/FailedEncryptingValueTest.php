<?php

namespace IlicMiljan\SecureProps\Tests\Cipher\Exception;

use IlicMiljan\SecureProps\Cipher\Exception\CipherException;
use IlicMiljan\SecureProps\Cipher\Exception\FailedEncryptingValue;
use LogicException;
use PHPUnit\Framework\TestCase;

class FailedEncryptingValueTest extends TestCase
{
    public function testCanBeCreated(): void
    {
        $exception = new FailedEncryptingValue();

        $this->assertInstanceOf(FailedEncryptingValue::class, $exception);
    }

    public function testPreviousExceptionIsStored(): void
    {
        $previous = new LogicException('Previous exception');
        $exception = new FailedEncryptingValue($previous);

        $this->assertSame($previous, $exception->getPrevious());
    }

    public function testImplementsCipherExceptionInterface(): void
    {
        $exception = new FailedEncryptingValue();

        $this->assertInstanceOf(CipherException::class, $exception);
    }
}
