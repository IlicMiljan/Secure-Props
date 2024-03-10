<?php

namespace IlicMiljan\SecureProps\Cipher;

use IlicMiljan\SecureProps\Cipher\Exception\FailedDecryptingValue;
use IlicMiljan\SecureProps\Cipher\Exception\FailedEncryptingValue;
use SensitiveParameter;

class AsymmetricEncryptionCipher implements Cipher
{
    public function __construct(
        private string $publicKey,
        private string $privateKey
    ) {
    }

    /**
     * @param string $string
     * @return string
     *
     * @throws FailedEncryptingValue
     */
    public function encrypt(#[SensitiveParameter] string $string): string
    {
        if (!openssl_public_encrypt($string, $encrypted, $this->publicKey)) {
            throw new FailedEncryptingValue();
        }

        return base64_encode($encrypted);
    }

    /**
     * @param string $string
     * @return string
     *
     * @throws FailedDecryptingValue
     */
    public function decrypt(#[SensitiveParameter] string $string): string
    {
        $data = base64_decode($string);

        if (!openssl_private_decrypt($data, $decrypted, $this->privateKey)) {
            throw new FailedDecryptingValue();
        }

        return $decrypted;
    }
}
