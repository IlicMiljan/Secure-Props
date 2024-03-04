<?php

namespace IlicMiljan\SecureProps\Attribute;

use Attribute;

/**
 * Marks a property for encryption.
 *
 * Use this attribute to indicate that a specific property within a class should
 * be encrypted when the class is persisted to a database or any other storage
 * medium.
 *
 * The actual encryption and decryption process is expected to be
 * handled by the consuming code or framework.
 *
 * @example
 * class User {
 *     #[Encrypted]
 *     private string $password;
 * }
 *
 * In the above example, the `$password` property of the User class will be
 * marked for encryption.
 *
 * @see Attribute::TARGET_PROPERTY Indicates that this attribute can only be
 *                                 applied to class properties.
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Encrypted
{
}
