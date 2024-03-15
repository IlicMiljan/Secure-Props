<?php

namespace IlicMiljan\SecureProps\Encoder;

class NullEncoder implements Encoder
{
    public function encode(string $value): string
    {
        return $value;
    }

    public function decode(string $value): string
    {
        return $value;
    }
}
