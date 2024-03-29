<?php

namespace IlicMiljan\SecureProps\Cipher\Exception;

use RuntimeException;
use Throwable;

class FailedEncryptingValue extends RuntimeException implements CipherException
{
    public function __construct(
        ?Throwable $previous = null
    ) {
        parent::__construct(
            'Encryption failed.',
            0,
            $previous
        );
    }
}
