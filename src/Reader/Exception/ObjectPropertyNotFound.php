<?php

namespace IlicMiljan\SecureProps\Reader\Exception;

use RuntimeException;
use Throwable;

class ObjectPropertyNotFound extends RuntimeException implements ReaderException
{
    public function __construct(
        private string $className,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            'Object property not found in ReflectionObject.',
            0,
            $previous
        );
    }

    public function getClassName(): string
    {
        return $this->className;
    }
}
