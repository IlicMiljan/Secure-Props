<?php

namespace IlicMiljan\SecureProps\Tests\Cipher;

use IlicMiljan\SecureProps\Cipher\AsymmetricEncryptionCipher;
use IlicMiljan\SecureProps\Cipher\Exception\CipherException;
use IlicMiljan\SecureProps\Encoder\Encoder;
use OpenSSLAsymmetricKey;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AsymmetricEncryptionCipherTest extends TestCase
{
    /** @var Encoder&MockObject */
    private $encoder;
    private AsymmetricEncryptionCipher $cipher;
    private AsymmetricEncryptionCipher $cipherWithCustomEncoder;

    protected function setUp(): void
    {
        $this->encoder = $this->createMock(Encoder::class);

        /** @var OpenSSLAsymmetricKey $asymmetricKey */
        $asymmetricKey = openssl_pkey_new();
        /** @var string[] $asymmetricKeyDetails */
        $asymmetricKeyDetails = openssl_pkey_get_details($asymmetricKey);

        openssl_pkey_export($asymmetricKey, $privateKey);

        $this->cipher = new AsymmetricEncryptionCipher($asymmetricKeyDetails['key'], $privateKey);

        $this->cipherWithCustomEncoder = new AsymmetricEncryptionCipher(
            $asymmetricKeyDetails['key'],
            $privateKey,
            $this->encoder
        );
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
