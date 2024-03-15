<?php

namespace IlicMiljan\SecureProps\Encoder;

use SensitiveParameter;

class NullEncoder implements Encoder
{
    /**
     * @inheritDoc
     */
    public function encode(#[SensitiveParameter] string $value): string
    {
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function decode(#[SensitiveParameter] string $value): string
    {
        return $value;
    }
}
