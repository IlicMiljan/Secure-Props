<?php

namespace IlicMiljan\SecureProps\Cipher;

use IlicMiljan\SecureProps\Cipher\Exception\FailedDecryptingValue;
use IlicMiljan\SecureProps\Cipher\Exception\FailedEncryptingValue;
use IlicMiljan\SecureProps\Encoder\Base64Encoder;
use IlicMiljan\SecureProps\Encoder\Encoder;
use SensitiveParameter;

class AsymmetricEncryptionCipher implements Cipher
{
    private Encoder $encoder;

    public function __construct(
        private string $publicKey,
        private string $privateKey,
        ?Encoder $encoder = null
    ) {
        if ($encoder === null) {
            $this->encoder =  new Base64Encoder();
        } else {
            $this->encoder = $encoder;
        }
    }

    /**
     * @inheritDoc
     */
    public function encrypt(#[SensitiveParameter] string $string): string
    {
        if (!openssl_public_encrypt($string, $encrypted, $this->publicKey)) {
            throw new FailedEncryptingValue();
        }

        return $this->encoder->encode($encrypted);
    }

    /**
     * @inheritDoc
     */
    public function decrypt(#[SensitiveParameter] string $string): string
    {
        $data = $this->encoder->decode($string);

        if (!openssl_private_decrypt($data, $decrypted, $this->privateKey)) {
            throw new FailedDecryptingValue();
        }

        return $decrypted;
    }
}
