<?php

namespace IlicMiljan\SecureProps\Tests\Reader\Exception;

use Exception;
use Psr\Cache\InvalidArgumentException;

class TestCacheException extends Exception implements InvalidArgumentException
{
}
