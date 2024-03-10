<?php

namespace IlicMiljan\SecureProps;

use IlicMiljan\SecureProps\Cipher\Cipher;
use IlicMiljan\SecureProps\Exception\ValueMustBeString;
use SensitiveParameter;

class StringEncryptionService implements EncryptionService
{
    public function __construct(
        private Cipher $cipher
    ) {
    }

    /**
     * @inheritDoc
     *
     * @return string
     */
    public function encrypt(#[SensitiveParameter] mixed $value): string
    {
        if (!is_string($value)) {
            throw new ValueMustBeString(gettype($value));
        }

        return $this->cipher->encrypt($value);
    }

    /**
     * @inheritDoc
     *
     * @return string
     */
    public function decrypt(#[SensitiveParameter] mixed $value): string
    {
        if (!is_string($value)) {
            throw new ValueMustBeString(gettype($value));
        }

        return $this->cipher->decrypt($value);
    }
}
