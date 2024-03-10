<?php

namespace IlicMiljan\SecureProps;

use IlicMiljan\SecureProps\Cipher\Exception\CipherException;
use IlicMiljan\SecureProps\Exception\EncryptionServiceException;
use IlicMiljan\SecureProps\Reader\Exception\ReaderException;
use SensitiveParameter;

/**
 * Defines the contract for encryption services.
 *
 * This interface outlines the methods required for implementing encryption and
 * decryption functionality.
 *
 * Implementations of this interface can be used to securely encrypt and decrypt
 * values, ensuring that sensitive information is protected during storage or
 * transmission.
 */
interface EncryptionService
{
    /**
     * Encrypts the given value.
     *
     * Encrypts a provided value using the implementation's specific encryption
     * algorithm. The value can be of any type, but implementations might
     * restrict the types they accept.
     *
     * @param mixed $value The value to encrypt. While mixed, specific
     *                     implementations may require certain types.
     *
     * @return mixed The encrypted value. The type of the return value might
     *               vary based on the implementation.
     *
     * @throws EncryptionServiceException If the value cannot be encrypted due
     *                                    to any reason.
     * @throws CipherException If there is an error with the encryption process.
     * @throws ReaderException If there is an error reading the properties for
     *                         encryption.
     */
    public function encrypt(#[SensitiveParameter] mixed $value): mixed;

    /**
     * Decrypts the given value.
     *
     * Decrypts a provided value using the implementation's specific decryption
     * algorithm. This is intended to reverse the encryption process and
     * retrieve the original value.
     *
     * @param mixed $value The value to decrypt. While mixed, specific
     *                     implementations may require certain types.
     *
     * @return mixed The decrypted value. The type of the return value might
     *               vary based on the implementation.
     *
     * @throws EncryptionServiceException If the value cannot be decrypted due
     *                                    to any reason.
     * @throws CipherException If there is an error with the decryption process.
     * @throws ReaderException If there is an error reading the properties for
     *                         decryption.
     */
    public function decrypt(#[SensitiveParameter] mixed $value): mixed;
}
