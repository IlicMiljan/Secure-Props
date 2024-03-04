<?php

namespace IlicMiljan\SecureProps\Reader\Exception;

use LogicException;
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
