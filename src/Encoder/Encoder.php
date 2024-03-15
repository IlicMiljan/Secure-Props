<?php

namespace IlicMiljan\SecureProps\Encoder;

interface Encoder
{
    public function encode(string $value): string;
    public function decode(string $value): string;
}
