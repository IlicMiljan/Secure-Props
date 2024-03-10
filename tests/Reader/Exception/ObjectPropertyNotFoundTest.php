<?php

namespace IlicMiljan\SecureProps\Tests\Reader\Exception;

use IlicMiljan\SecureProps\Reader\Exception\ObjectPropertyNotFound;
use IlicMiljan\SecureProps\Reader\Exception\ReaderException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class ObjectPropertyNotFoundTest extends TestCase
{
    private string $className;

    protected function setUp(): void
    {
        $this->className = 'TestClass';
    }

    public function testCanBeCreated(): void
    {
        $exception = new ObjectPropertyNotFound($this->className);

        $this->assertInstanceOf(ObjectPropertyNotFound::class, $exception);
    }

    public function testReturnsClassName(): void
    {
        $exception = new ObjectPropertyNotFound($this->className);

        $this->assertEquals($this->className, $exception->getClassName());
    }

    public function testPreviousExceptionIsStored(): void
    {
        $previous = new RuntimeException('Previous exception');
        $exception = new ObjectPropertyNotFound($this->className, $previous);

        $this->assertSame($previous, $exception->getPrevious());
    }

    public function testImplementsReaderExceptionInterface(): void
    {
        $exception = new ObjectPropertyNotFound($this->className);

        $this->assertInstanceOf(ReaderException::class, $exception);
    }
}
