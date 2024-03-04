<?php

namespace IlicMiljan\SecureProps\Reader\Exception;

use LogicException;
use Throwable;

class InvalidCacheKey extends LogicException
{
    public function __construct(
        private string $cacheKey,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            'Invalid cache key.',
            0,
            $previous
        );
    }

    /**
     * @return string
     */
    public function getCacheKey(): string
    {
        return $this->cacheKey;
    }
}
