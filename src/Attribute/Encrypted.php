<?php

namespace IlicMiljan\SecureProps\Attribute;

use Attribute;

/**
 * Marks a property for encryption.
 *
 * This attribute indicates that a specific property within a class should be
 * encrypted when the class is saved to a database or other storage medium.
 *
 * Additionally, you can specify a nullable placeholder value to be used when
 * decryption fails. Depending on the configuration of the consuming code or
 * framework, an exception may be thrown during decryption failure if the
 * placeholder is null.
 *
 * For instance, a consuming application may throw an exception in production
 * environments to immediately highlight and address decryption issues. On the
 * other hand, it might be preferable to use a placeholder value to allow for
 * continued testing in development environments, even if the data is encrypted
 * with a different key.
 *
 * @example
 * ```php
 * class User {
 *     #[Encrypted(placeholder: "********")]
 *     private string $password;
 *
 *     #[Encrypted] // Behavior on decryption failure depends on configuration.
 *     private string $secret;
 * }
 * ```
 *
 * In the above examples, the `$password` property of the User class will be
 * marked for encryption, and "********" may be used as a placeholder if the
 * decryption process fails.
 * The behavior of the `$secret` property on decryption failure depends on the
 * configuration.
 *
 * @see Attribute::TARGET_PROPERTY Indicates that this attribute can only be
 *                                 applied to class properties.
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Encrypted
{
    /**
     * Constructs the Encrypted attribute.
     *
     * @param string|null $placeholder The placeholder value to use when
     *                                 decryption fails, or null to indicate
     *                                 that an exception may be thrown based
     *                                 on configuration.
     */
    public function __construct(
        private ?string $placeholder = null
    ) {
    }

    /**
     * Retrieves the placeholder value.
     *
     * @return string|null The placeholder value.
     */
    public function getPlaceholder(): ?string
    {
        return $this->placeholder;
    }
}
