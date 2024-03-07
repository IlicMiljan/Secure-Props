# SecureProps - PHP Encryption Library

[![stability-release-candidate](https://img.shields.io/badge/stability-pre--release-48c9b0.svg)](https://github.com/mkenney/software-guides/blob/master/STABILITY-BADGES.md#release-candidate)
![GitHub Workflow Status (with branch)](https://img.shields.io/github/actions/workflow/status/ilicmiljan/secure-props/php-ci.yaml)
[![codecov](https://codecov.io/github/IlicMiljan/Secure-Props/branch/main/graph/badge.svg?token=7EQBUFHJKR)](https://codecov.io/github/IlicMiljan/Secure-Props)
![Packagist PHP Version](https://img.shields.io/packagist/dependency-v/ilicmiljan/secure-props/php)
![GitHub](https://img.shields.io/github/license/ilicmiljan/secure-props)

SecureProps is a powerful PHP library designed to simplify the encryption and decryption of property data in objects. 

Utilizing the power of PHP attributes, SecureProps allows developers to easily secure sensitive data within their applications. The library supports both asymmetric and symmetric encryption methods, providing flexibility in securing your application data.

## Features

- Easy-to-use encryption and decryption of properties within PHP objects.
- Support for asymmetric encryption using RSA keys.
- Support for symmetric encryption using Advanced Encryption Standard (AES-256-GCM).

## Requirements

- PHP 8.0 or higher.
- OpenSSL extension enabled in your PHP installation.

## Installation

You can install SecureProps via Composer by running the following command:

```bash
composer require ilicmiljan/secure-props
```

Ensure that your `composer.json` file is updated and the library is included in your project's dependencies.

## Usage

### Marking Properties for Encryption

Use the `#[Encrypted]` attribute to mark properties within your classes that you wish to encrypt or decrypt. For example:

```php
use IlicMiljan\SecureProps\Attribute\Encrypted;

class User
{
    #[Encrypted]
    private string $socialSecurityNumber;

    // Other properties and methods...
}
```

### Encrypting and Decrypting Objects

To encrypt or decrypt objects, you will need to use the `ObjectEncryptionService`. Here is an example:

```php
use IlicMiljan\SecureProps\ObjectEncryptionService;
use IlicMiljan\SecureProps\Cipher\AdvancedEncryptionStandardCipher;

// Create a cipher instance (AES in this example)
$cipher = new AdvancedEncryptionStandardCipher('256-BIT-KEY-HERE');

// Initialize the encryption service with a runtime object properties reader
$encryptionService = new ObjectEncryptionService($cipher, new RuntimeObjectPropertiesReader());

$user = new User();
$user->setSocialSecurityNumber('123-45-6789');

// Encrypt properties
$encryptedUser = $encryptionService->encrypt($user);

// Decrypt properties
$decryptedUser = $encryptionService->decrypt($encryptedUser);
```

### Asymmetric Encryption

To use asymmetric encryption, initialize the `AsymmetricEncryptionCipher` with your public and private keys:

```php
use IlicMiljan\SecureProps\Cipher\AsymmetricEncryptionCipher;

$cipher = new AsymmetricEncryptionCipher($publicKey, $privateKey);

// Then, pass this cipher to the ObjectEncryptionService as shown above.
```

## Property Readers

SecureProps comes with two implementations of property readers: a runtime one and a decorator for caching.

### RuntimeObjectPropertiesReader

This reader inspects objects at runtime to find properties marked with the `#[Encrypted]` attribute. It uses PHP's reflection capabilities to perform its duties without requiring any caching mechanism.

### CachingObjectPropertiesReader

This reader wraps another `ObjectPropertiesReader` implementation and caches the results to improve performance. It's particularly useful for applications that repeatedly process the same object types, reducing the overhead of reflection operations. The `PSR-6` caching standard provides a flexible framework for integrating various caching backends, offering developers the freedom to choose a solution that best fits their application's scaling and performance requirements.

## Contributing

Contributions to SecureProps are welcome. Please ensure that your code adheres to the project's coding standards and include tests for new features or bug fixes.

## License

SecureProps is open-sourced software licensed under the MIT license.
