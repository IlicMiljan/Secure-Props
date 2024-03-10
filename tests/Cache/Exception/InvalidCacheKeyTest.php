<?php

namespace IlicMiljan\SecureProps\Tests\Cache\Exception;

use IlicMiljan\SecureProps\Cache\Exception\InvalidCacheKey;
use LogicException;
use PHPUnit\Framework\TestCase;

class InvalidCacheKeyTest extends TestCase
{
    private string $cacheKey;

    protected function setUp(): void
    {
        $this->cacheKey = 'invalidKey';
    }

    public function testCanBeCreated(): void
    {
        $exception = new InvalidCacheKey($this->cacheKey);

        $this->assertInstanceOf(InvalidCacheKey::class, $exception);
    }

    public function testReturnsCacheKey(): void
    {
        $exception = new InvalidCacheKey($this->cacheKey);

        $this->assertEquals($this->cacheKey, $exception->getCacheKey());
    }

    public function testPreviousExceptionIsStored(): void
    {
        $previous = new LogicException('Previous exception');
        $exception = new InvalidCacheKey($this->cacheKey, $previous);

        $this->assertSame($previous, $exception->getPrevious());
    }
}
