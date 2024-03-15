<?php

namespace IlicMiljan\SecureProps\Encoder;

use SensitiveParameter;

class Base64Encoder implements Encoder
{
    /**
     * @inheritDoc
     */
    public function encode(#[SensitiveParameter] string $value): string
    {
        return base64_encode($value);
    }

    /**
     * @inheritDoc
     */
    public function decode(#[SensitiveParameter] string $value): string
    {
        return base64_decode($value);
    }
}
