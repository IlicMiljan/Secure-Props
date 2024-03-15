<?php

namespace IlicMiljan\SecureProps\Encoder;

class Base64Encoder implements Encoder
{
    public function encode(string $value): string
    {
        return base64_encode($value);
    }

    public function decode(string $value): string
    {
        return base64_decode($value);
    }
}
