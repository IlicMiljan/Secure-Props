<?php

namespace IlicMiljan\SecureProps\Tests\Cipher;

use IlicMiljan\SecureProps\Cipher\AdvancedEncryptionStandardCipher;
use IlicMiljan\SecureProps\Cipher\AsymmetricEncryptionCipher;
use IlicMiljan\SecureProps\Cipher\Cipher;
use IlicMiljan\SecureProps\Reader\Exception\FailedCalculatingInitializationVectorLength;
use IlicMiljan\SecureProps\Reader\Exception\FailedDecryptingValue;
use IlicMiljan\SecureProps\Reader\Exception\FailedEncryptingValue;
use IlicMiljan\SecureProps\Reader\Exception\FailedGeneratingInitializationVector;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AsymmetricEncryptionCipherTest extends TestCase
{
    private AsymmetricEncryptionCipher $cipher;

    protected function setUp(): void
    {
        $asymmetricKey = openssl_pkey_new();

        $publicKey = openssl_pkey_get_details($asymmetricKey)['key'];

        $privateKey = '';
        openssl_pkey_export($asymmetricKey, $privateKey);

        $this->cipher = new AsymmetricEncryptionCipher($publicKey, $privateKey);
    }

    public function testEncryptAndDecryptSuccessfully()
    {
        $encryptedString = $this->cipher->encrypt('plainText');
        $decryptedString = $this->cipher->decrypt($encryptedString);

        $this->assertEquals('plainText', $decryptedString);
    }
}
