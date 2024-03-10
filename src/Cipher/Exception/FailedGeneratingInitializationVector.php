<?php

namespace IlicMiljan\SecureProps\Cipher\Exception;

use LogicException;
use RuntimeException;
use Throwable;

class FailedGeneratingInitializationVector extends RuntimeException implements CipherException
{
    public function __construct(
        private int $length,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            'Failed generating initialization vector.',
            0,
            $previous
        );
    }

    public function getLength(): int
    {
        return $this->length;
    }
}
