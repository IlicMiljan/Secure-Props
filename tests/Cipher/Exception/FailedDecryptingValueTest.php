<?php

namespace IlicMiljan\SecureProps\Tests\Cipher\Exception;

use IlicMiljan\SecureProps\Cipher\Exception\CipherException;
use IlicMiljan\SecureProps\Cipher\Exception\FailedDecryptingValue;
use LogicException;
use PHPUnit\Framework\TestCase;

class FailedDecryptingValueTest extends TestCase
{
    public function testCanBeCreated(): void
    {
        $exception = new FailedDecryptingValue();

        $this->assertInstanceOf(FailedDecryptingValue::class, $exception);
    }

    public function testPreviousExceptionIsStored(): void
    {
        $previous = new LogicException('Previous exception');
        $exception = new FailedDecryptingValue($previous);

        $this->assertSame($previous, $exception->getPrevious());
    }

    public function testImplementsCipherExceptionInterface(): void
    {
        $exception = new FailedDecryptingValue();

        $this->assertInstanceOf(CipherException::class, $exception);
    }
}
