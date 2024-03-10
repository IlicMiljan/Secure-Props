<?php

namespace IlicMiljan\SecureProps;

use IlicMiljan\SecureProps\Attribute\Encrypted;
use IlicMiljan\SecureProps\Cipher\Cipher;
use IlicMiljan\SecureProps\Cipher\Exception\CipherException;
use IlicMiljan\SecureProps\Exception\EncryptionServiceException;
use IlicMiljan\SecureProps\Exception\ValueMustBeObject;
use IlicMiljan\SecureProps\Exception\ValueMustBeString;
use IlicMiljan\SecureProps\Reader\Exception\ReaderException;
use IlicMiljan\SecureProps\Reader\ObjectPropertiesReader;
use ReflectionProperty;
use SensitiveParameter;

class ObjectEncryptionService implements EncryptionService
{
    public function __construct(
        private Cipher $cipher,
        private ObjectPropertiesReader $objectPropertiesReader
    ) {
    }

    /**
     * @param mixed $value
     * @return object
     *
     * @throws EncryptionServiceException
     * @throws CipherException
     * @throws ReaderException
     */
    public function encrypt(#[SensitiveParameter] mixed $value): object
    {
        if (!is_object($value)) {
            throw new ValueMustBeObject(gettype($value));
        }

        $encryptedProperties = $this->objectPropertiesReader->getPropertiesWithAttribute($value, Encrypted::class);

        foreach ($encryptedProperties as $property) {
            $this->updatePropertyValue(
                $property,
                $value,
                fn(string $plainValue) => $this->cipher->encrypt($plainValue)
            );
        }

        return $value;
    }

    /**
     * @param mixed $value
     *
     * @return object
     *
     * @throws EncryptionServiceException
     * @throws CipherException
     * @throws ReaderException
     */
    public function decrypt(#[SensitiveParameter] mixed $value): object
    {
        if (!is_object($value)) {
            throw new ValueMustBeObject(gettype($value));
        }

        $encryptedProperties = $this->objectPropertiesReader->getPropertiesWithAttribute($value, Encrypted::class);

        foreach ($encryptedProperties as $property) {
            $this->updatePropertyValue(
                $property,
                $value,
                fn(string $encryptedValue) => $this->cipher->decrypt($encryptedValue)
            );
        }

        return $value;
    }

    private function updatePropertyValue(ReflectionProperty $property, object $object, callable $callback): void
    {
        $property->setAccessible(true);

        $currentValue = $property->getValue($object);

        if (!is_string($currentValue)) {
            throw new ValueMustBeString(gettype($currentValue));
        }

        $property->setValue($object, $callback($currentValue));
    }
}
