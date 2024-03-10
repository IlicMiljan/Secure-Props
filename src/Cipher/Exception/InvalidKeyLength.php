<?php

namespace IlicMiljan\SecureProps\Cipher\Exception;

use RuntimeException;
use Throwable;

class InvalidKeyLength extends RuntimeException implements CipherException
{
    public function __construct(
        private int $expectedLength,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            'The provided key is too short.',
            0,
            $previous
        );
    }

    public function getExpectedLength(): int
    {
        return $this->expectedLength;
    }
}
