<?php

namespace IlicMiljan\SecureProps\Cipher\Exception;

use RuntimeException;
use Throwable;

class FailedDecryptingValue extends RuntimeException implements CipherException
{
    public function __construct(
        ?Throwable $previous = null
    ) {
        parent::__construct(
            'Decryption failed.',
            0,
            $previous
        );
    }
}
