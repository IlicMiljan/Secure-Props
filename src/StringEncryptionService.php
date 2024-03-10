<?php

namespace IlicMiljan\SecureProps;

use IlicMiljan\SecureProps\Cipher\Cipher;
use IlicMiljan\SecureProps\Cipher\Exception\CipherException;
use IlicMiljan\SecureProps\Exception\EncryptionServiceException;
use IlicMiljan\SecureProps\Exception\ValueMustBeString;
use SensitiveParameter;

class StringEncryptionService implements EncryptionService
{
    public function __construct(
        private Cipher $cipher
    ) {
    }

    /**
     * @param mixed $value
     *
     * @return string
     *
     * @throws EncryptionServiceException
     * @throws CipherException
     */
    public function encrypt(#[SensitiveParameter] mixed $value): string
    {
        if (!is_string($value)) {
            throw new ValueMustBeString(gettype($value));
        }

        return $this->cipher->encrypt($value);
    }

    /**
     * @param mixed $value
     * @return string
     *
     * @throws EncryptionServiceException
     * @throws CipherException
     */
    public function decrypt(#[SensitiveParameter] mixed $value): string
    {
        if (!is_string($value)) {
            throw new ValueMustBeString(gettype($value));
        }

        return $this->cipher->decrypt($value);
    }
}
