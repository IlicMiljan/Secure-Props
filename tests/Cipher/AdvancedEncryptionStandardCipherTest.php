<?php

namespace IlicMiljan\SecureProps\Tests\Cipher;

use IlicMiljan\SecureProps\Cipher\AdvancedEncryptionStandardCipher;
use IlicMiljan\SecureProps\Cipher\Exception\InvalidKeyLength;
use PHPUnit\Framework\TestCase;

class AdvancedEncryptionStandardCipherTest extends TestCase
{
    private AdvancedEncryptionStandardCipher $cipher;

    protected function setUp(): void
    {
        $this->cipher = new AdvancedEncryptionStandardCipher(openssl_random_pseudo_bytes(32));
    }

    public function testConstructWithInvalidKeyLengthThrowsException(): void
    {
        $this->expectException(InvalidKeyLength::class);

        new AdvancedEncryptionStandardCipher(openssl_random_pseudo_bytes(16));
    }

    public function testEncryptAndDecryptSuccessfully(): void
    {
        $encryptedString = $this->cipher->encrypt('plainText');
        $decryptedString = $this->cipher->decrypt($encryptedString);

        $this->assertEquals('plainText', $decryptedString);
    }
}
