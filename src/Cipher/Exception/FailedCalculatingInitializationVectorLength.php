<?php

namespace IlicMiljan\SecureProps\Cipher\Exception;

use RuntimeException;
use Throwable;

class FailedCalculatingInitializationVectorLength extends RuntimeException implements CipherException
{
    public function __construct(
        private string $cipher,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            'Failed calculating initialization vector length.',
            0,
            $previous
        );
    }

    public function getCipher(): string
    {
        return $this->cipher;
    }
}
