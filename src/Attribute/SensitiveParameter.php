<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

/**
 * Polyfill for the SensitiveParameter attribute introduced in PHP 8.2.
 *
 * This polyfill allows code targeting PHP versions earlier than 8.2 to use the
 * SensitiveParameter attribute without causing syntax errors. It's a no-op in
 * older PHP versions but enables forward compatibility with PHP 8.2 and newer.
 */

if (PHP_VERSION_ID < 80200) {
    #[Attribute(Attribute::TARGET_PARAMETER)]
    final class SensitiveParameter
    {
        public function __construct()
        {
        }
    }
}
