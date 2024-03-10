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

SecureProps provides two types of property readers to handle encrypted properties within your PHP objects efficiently: `RuntimeObjectPropertiesReader` and `CachingObjectPropertiesReader`.

### RuntimeObjectPropertiesReader

The `RuntimeObjectPropertiesReader` dynamically examines objects at runtime to identify properties decorated with the `#[Encrypted]` attribute. Utilizing PHP's reflection requires no additional setup for caching and offers straightforward inspection capabilities.

### CachingObjectPropertiesReader

For enhanced performance, especially in applications that frequently deal with the same types of objects, the `CachingObjectPropertiesReader` caches property reading results. This approach reduces the computational overhead associated with reflection.

It integrates seamlessly with `PSR-6` compliant caching solutions, allowing for customizable performance optimization.

#### Quick Start Example

Combining `CachingObjectPropertiesReader` with `RuntimeObjectPropertiesReader` and a `PSR-6` compliant cache implementation:

```php
// Initialize a PSR-6 cache pool
$cache = new FilesystemAdapter(...);

// Configure the caching reader
$reader = new CachingObjectPropertiesReader(
    new RuntimeObjectPropertiesReader(),
    new CacheItemPoolAdapter($cache)
);

// Set up the ObjectEncryptionService with the reader
$encryptionService = new ObjectEncryptionService($cipher, $reader);
```

## Contributing

Contributions to SecureProps are welcome. Please ensure that your code adheres to the project's coding standards and include tests for new features or bug fixes.

## License

SecureProps is open-sourced software licensed under the MIT license.
