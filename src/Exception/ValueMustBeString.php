<?php

namespace IlicMiljan\SecureProps\Exception;

use RuntimeException;
use Throwable;

class ValueMustBeString extends RuntimeException implements EncryptionServiceException
{
    public function __construct(
        private string $type,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            'The value must be a string.',
            0,
            $previous
        );
    }

    public function getType(): string
    {
        return $this->type;
    }
}
