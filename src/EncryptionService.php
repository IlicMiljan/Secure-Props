<?php

namespace IlicMiljan\SecureProps;

use SensitiveParameter;

interface EncryptionService
{
    public function encrypt(#[SensitiveParameter] mixed $value): mixed;
    public function decrypt(#[SensitiveParameter] mixed $value): mixed;
}
