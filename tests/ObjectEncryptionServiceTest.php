<?php

namespace IlicMiljan\SecureProps\Tests;

use IlicMiljan\SecureProps\Attribute\Encrypted;
use IlicMiljan\SecureProps\Cipher\Cipher;
use IlicMiljan\SecureProps\Cipher\Exception\CipherException;
use IlicMiljan\SecureProps\ObjectEncryptionService;
use IlicMiljan\SecureProps\Reader\Exception\ReaderException;
use IlicMiljan\SecureProps\Reader\ObjectPropertiesReader;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionProperty;

class ObjectEncryptionServiceTest extends TestCase
{
    /**
     * @var Cipher&MockObject
     */
    private Cipher $cipherMock;
    /**
     * @var ObjectPropertiesReader&MockObject
     */
    private $objectPropertiesReaderMock;
    private ObjectEncryptionService $service;

    protected function setUp(): void
    {
        $this->cipherMock = $this->createMock(Cipher::class);
        $this->objectPropertiesReaderMock = $this->createMock(ObjectPropertiesReader::class);

        $this->service = new ObjectEncryptionService(
            $this->cipherMock,
            $this->objectPropertiesReaderMock
        );
    }

    /**
     * @throws ReflectionException
     * @throws CipherException
     * @throws ReaderException
     */
    public function testEncryptSuccess(): void
    {
        $object = new class {
            #[Encrypted]
            public string $sensitive = 'plainText';
        };

        $reflectionProperty = new ReflectionProperty($object, 'sensitive');
        $reflectionProperty->setAccessible(true);

        $this->objectPropertiesReaderMock
            ->expects($this->once())
            ->method('getPropertiesWithAttribute')
            ->with($object, Encrypted::class)
            ->willReturn([$reflectionProperty]);

        $this->cipherMock
            ->expects($this->once())
            ->method('encrypt')
            ->with('plainText')
            ->willReturn('encryptedText');

        $encryptedObject = $this->service->encrypt($object);

        /** @phpstan-ignore-next-line */
        $this->assertEquals('encryptedText', $encryptedObject->sensitive);
    }

    /**
     * @throws ReflectionException
     * @throws ReaderException
     * @throws CipherException
     */
    public function testDecryptSuccess(): void
    {
        $object = new class {
            #[Encrypted]
            public string $sensitive = 'encryptedText';
        };

        $reflectionProperty = new ReflectionProperty($object, 'sensitive');
        $reflectionProperty->setAccessible(true);

        $this->objectPropertiesReaderMock
            ->expects($this->once())
            ->method('getPropertiesWithAttribute')
            ->with($object, Encrypted::class)
            ->willReturn([$reflectionProperty]);

        $this->cipherMock
            ->expects($this->once())
            ->method('decrypt')
            ->with('encryptedText')
            ->willReturn('plainText');

        $decryptedObject = $this->service->decrypt($object);

        /** @phpstan-ignore-next-line */
        $this->assertEquals('plainText', $decryptedObject->sensitive);
    }

    /**
     * @throws ReflectionException
     * @throws CipherException
     * @throws ReaderException
     */
    public function testEncryptSkipsNullProperties(): void
    {
        $object = new class {
            #[Encrypted]
            /** @phpstan-ignore-next-line */
            public ?string $sensitive = null;
        };

        $reflectionProperty = new ReflectionProperty($object, 'sensitive');
        $reflectionProperty->setAccessible(true);

        $this->objectPropertiesReaderMock
            ->expects($this->once())
            ->method('getPropertiesWithAttribute')
            ->with($object, Encrypted::class)
            ->willReturn([$reflectionProperty]);

        $this->cipherMock
            ->expects($this->never()) // Ensure encrypt is never called since the property is null
            ->method('encrypt');

        $encryptedObject = $this->service->encrypt($object);

        // Assert the property remains null, indicating it was skipped
        /** @phpstan-ignore-next-line */
        $this->assertNull($encryptedObject->sensitive);
    }

    /**
     * @throws ReflectionException
     * @throws CipherException
     * @throws ReaderException
     */
    public function testDecryptSkipsNullProperties(): void
    {
        $object = new class {
            #[Encrypted]
            /** @phpstan-ignore-next-line */
            public ?string $sensitive = null;
        };

        $reflectionProperty = new ReflectionProperty($object, 'sensitive');
        $reflectionProperty->setAccessible(true);

        $this->objectPropertiesReaderMock
            ->expects($this->once())
            ->method('getPropertiesWithAttribute')
            ->with($object, Encrypted::class)
            ->willReturn([$reflectionProperty]);

        $this->cipherMock
            ->expects($this->never()) // Ensure decrypt is never called since the property is null
            ->method('decrypt');

        $decryptedObject = $this->service->decrypt($object);

        // Assert the property remains null, indicating it was skipped
        /** @phpstan-ignore-next-line */
        $this->assertNull($decryptedObject->sensitive);
    }

    /**
     * @throws ReaderException
     * @throws CipherException
     */
    public function testEncryptThrowsInvalidArgumentExceptionForNonObject(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->service->encrypt('notAnObject');
    }

    /**
     * @throws CipherException
     * @throws ReaderException
     */
    public function testDecryptThrowsInvalidArgumentExceptionForNonObject(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->service->decrypt('notAnObject');
    }

    /**
     * @throws ReflectionException
     * @throws ReaderException
     * @throws CipherException
     */
    public function testEncryptThrowsInvalidArgumentExceptionForNonString(): void
    {
        $object = new class {
            #[Encrypted]
            public int $sensitive = 123;
        };

        $reflectionProperty = new ReflectionProperty($object, 'sensitive');
        $reflectionProperty->setAccessible(true);

        $this->objectPropertiesReaderMock
            ->method('getPropertiesWithAttribute')
            ->willReturn([$reflectionProperty]);

        $this->expectException(InvalidArgumentException::class);

        $this->service->encrypt($object);
    }

    /**
     * @throws ReflectionException
     * @throws CipherException
     * @throws ReaderException
     */
    public function testDecryptThrowsInvalidArgumentExceptionForNonString(): void
    {
        $object = new class {
            #[Encrypted]
            public int $sensitive = 123;
        };

        $reflectionProperty = new ReflectionProperty($object, 'sensitive');
        $reflectionProperty->setAccessible(true);

        $this->objectPropertiesReaderMock
            ->method('getPropertiesWithAttribute')
            ->willReturn([$reflectionProperty]);

        $this->expectException(InvalidArgumentException::class);

        $this->service->decrypt($object);
    }
}
