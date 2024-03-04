<?php

namespace IlicMiljan\SecureProps\Reader\Exception;

use LogicException;
use Throwable;

class InvalidCacheValueDataType extends LogicException
{
    public function __construct(
        private string $dataType,
        private string $expectedDataType,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            'Invalid cache value data type.',
            0,
            $previous
        );
    }

    public function getDataType(): string
    {
        return $this->dataType;
    }

    public function getExpectedDataType(): string
    {
        return $this->expectedDataType;
    }
}
