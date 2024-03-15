<?php

namespace IlicMiljan\SecureProps\Tests\Cipher;

use IlicMiljan\SecureProps\Cipher\AdvancedEncryptionStandardCipher;
use IlicMiljan\SecureProps\Cipher\Exception\CipherException;
use IlicMiljan\SecureProps\Cipher\Exception\InvalidKeyLength;
use IlicMiljan\SecureProps\Encoder\Encoder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AdvancedEncryptionStandardCipherTest extends TestCase
{
    /** @var Encoder&MockObject */
    private $encoder;
    private AdvancedEncryptionStandardCipher $cipher;
    private AdvancedEncryptionStandardCipher $cipherWithCustomEncoder;

    protected function setUp(): void
    {
        $this->encoder = $this->createMock(Encoder::class);

        $this->cipher = new AdvancedEncryptionStandardCipher(
            openssl_random_pseudo_bytes(32),
        );

        $this->cipherWithCustomEncoder = new AdvancedEncryptionStandardCipher(
            openssl_random_pseudo_bytes(32),
            $this->encoder
        );
    }

    public function testConstructWithInvalidKeyLengthThrowsException(): void
    {
        $this->expectException(InvalidKeyLength::class);

        new AdvancedEncryptionStandardCipher(openssl_random_pseudo_bytes(16));
    }

    /**
     * @throws CipherException
     */
    public function testEncryptAndDecryptSuccessfully(): void
    {
        $encryptedString = $this->cipher->encrypt('plainText');
        $decryptedString = $this->cipher->decrypt($encryptedString);

        $this->assertEquals('plainText', $decryptedString);
    }

    /**
     * @throws CipherException
     */
    public function testEncryptAndDecryptWithCustomEncoder(): void
    {
        $this->encoder->method('encode')->willReturnCallback(function ($data) {
            return $data;
        });

        $this->encoder->method('decode')->willReturnCallback(function ($data) {
            return $data;
        });

        $encryptedText = $this->cipherWithCustomEncoder->encrypt('plainText');
        $decryptedText = $this->cipherWithCustomEncoder->decrypt($encryptedText);

        $this->assertEquals('plainText', $decryptedText);
    }
}
