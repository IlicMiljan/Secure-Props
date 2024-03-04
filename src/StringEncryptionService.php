<?php

namespace IlicMiljan\SecureProps;

use IlicMiljan\SecureProps\Cipher\Cipher;
use InvalidArgumentException;
use SensitiveParameter;

class StringEncryptionService implements EncryptionService
{
    public function __construct(
        private Cipher $cipher
    ) {
    }

    /**
     * @param mixed $value
     * @return string
     * @throws Reader\Exception\CipherException
     */
    public function encrypt(#[SensitiveParameter] mixed $value): string
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException('Value must be string.');
        }

        return $this->cipher->encrypt($value);
    }

    /**
     * @param mixed $value
     * @return string
     * @throws Reader\Exception\CipherException
     */
    public function decrypt(#[SensitiveParameter] mixed $value): string
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException('Value must be string.');
        }

        return $this->cipher->decrypt($value);
    }
}
