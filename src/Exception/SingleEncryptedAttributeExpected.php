<?php

namespace IlicMiljan\SecureProps\Exception;

use RuntimeException;
use Throwable;

class SingleEncryptedAttributeExpected extends RuntimeException implements EncryptionServiceException
{
    public function __construct(
        private int $count,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            'Each property must be annotated with a single instance of the Encrypted attribute.',
            0,
            $previous
        );
    }

    public function getCount(): int
    {
        return $this->count;
    }
}
