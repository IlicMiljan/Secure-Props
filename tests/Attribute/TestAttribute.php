<?php

namespace IlicMiljan\SecureProps\Tests\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class TestAttribute
{
    public function __construct(
        private ?string $placeholder = null
    ) {
    }

    public function getPlaceholder(): ?string
    {
        return $this->placeholder;
    }
}
