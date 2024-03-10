<?php

namespace IlicMiljan\SecureProps\Cipher;

use IlicMiljan\SecureProps\Cipher\Exception\CipherException;
use SensitiveParameter;

interface Cipher
{
    /**
     * Encrypts the given string.
     *
     * This method takes a plaintext string as input and returns an encrypted
     * string as output.
     *
     * The specific encryption algorithm used is dependent on the implementing
     * class.
     *
     * @param string $string The plaintext string to be encrypted.
     * @return string The encrypted string.
     *
     * @throws CipherException
     */
    public function encrypt(#[SensitiveParameter] string $string): string;

    /**
     * Decrypts the given string.
     *
     * This method takes an encrypted string as input and returns the decrypted
     * version of it as plaintext.
     *
     * The specific decryption algorithm used is dependent on the implementing
     * class.
     *
     * @param string $string The encrypted string to be decrypted.
     * @return string The decrypted string, in plaintext.
     *
     * @throws CipherException
     */
    public function decrypt(#[SensitiveParameter] string $string): string;
}
