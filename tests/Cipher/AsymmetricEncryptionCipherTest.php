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
use OpenSSLAsymmetricKey;
use PHPUnit\Framework\TestCase;

class AsymmetricEncryptionCipherTest extends TestCase
{
    private AsymmetricEncryptionCipher $cipher;

    protected function setUp(): void
    {
        /** @var OpenSSLAsymmetricKey $asymmetricKey */
        $asymmetricKey = openssl_pkey_new();
        /** @var string[] $asymmetricKeyDetails */
        $asymmetricKeyDetails = openssl_pkey_get_details($asymmetricKey);

        openssl_pkey_export($asymmetricKey, $privateKey);

        $this->cipher = new AsymmetricEncryptionCipher($asymmetricKeyDetails['key'], $privateKey);
    }

    public function testEncryptAndDecryptSuccessfully(): void
    {
        $encryptedString = $this->cipher->encrypt('plainText');
        $decryptedString = $this->cipher->decrypt($encryptedString);

        $this->assertEquals('plainText', $decryptedString);
    }
}
