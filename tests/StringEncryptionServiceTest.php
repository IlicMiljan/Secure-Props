<?php

namespace IlicMiljan\SecureProps\Tests;

use IlicMiljan\SecureProps\Cipher\Cipher;
use IlicMiljan\SecureProps\Cipher\Exception\CipherException;
use IlicMiljan\SecureProps\Exception\ValueMustBeString;
use IlicMiljan\SecureProps\StringEncryptionService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class StringEncryptionServiceTest extends TestCase
{
    /**
     * @var Cipher&MockObject
     */
    private $cipherMock;
    private StringEncryptionService $service;

    protected function setUp(): void
    {
        $this->cipherMock = $this->createMock(Cipher::class);
        $this->service = new StringEncryptionService($this->cipherMock);
    }

    /**
     * @throws CipherException
     */
    public function testEncryptSuccess(): void
    {
        $this->cipherMock
            ->expects($this->once())
            ->method('encrypt')
            ->with($this->equalTo('plainText'))
            ->willReturn('encryptedText');

        $result = $this->service->encrypt('plainText');
        $this->assertEquals('encryptedText', $result);
    }

    /**
     * @throws CipherException
     */
    public function testDecryptSuccess(): void
    {
        $this->cipherMock
            ->expects($this->once())
            ->method('decrypt')
            ->with($this->equalTo('encryptedText'))
            ->willReturn('plainText');

        $result = $this->service->decrypt('encryptedText');
        $this->assertEquals('plainText', $result);
    }

    /**
     * @throws CipherException
     */
    public function testEncryptThrowsValueMustBeStringExceptionForNonString(): void
    {
        $this->expectException(ValueMustBeString::class);

        $this->service->encrypt(['plainText']);
    }

    /**
     * @throws CipherException
     */
    public function testDecryptThrowsValueMustBeStringExceptionForNonString(): void
    {
        $this->expectException(ValueMustBeString::class);

        $this->service->decrypt(['encryptedText']);
    }
}
