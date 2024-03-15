<?php

namespace IlicMiljan\SecureProps;

use IlicMiljan\SecureProps\Attribute\Encrypted;
use IlicMiljan\SecureProps\Cipher\Cipher;
use IlicMiljan\SecureProps\Cipher\Exception\FailedDecryptingValue;
use IlicMiljan\SecureProps\Exception\SingleEncryptedAttributeExpected;
use IlicMiljan\SecureProps\Exception\ValueMustBeObject;
use IlicMiljan\SecureProps\Exception\ValueMustBeString;
use IlicMiljan\SecureProps\Reader\ObjectPropertiesReader;
use ReflectionAttribute;
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
     * @inheritDoc
     *
     * @return object
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
     * @inheritDoc
     *
     * @return object
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

        if ($currentValue === null) {
            return;
        }

        if (!is_string($currentValue)) {
            throw new ValueMustBeString(gettype($currentValue));
        }

        try {
            $property->setValue($object, $callback($currentValue));
        } catch (FailedDecryptingValue $e) {
            $this->handleDecryptionFailure($object, $property, $e);
        }
    }

    public function handleDecryptionFailure(
        object $object,
        ReflectionProperty $property,
        FailedDecryptingValue $e,
    ): void {
        $placeholderValue = $this->getPropertyPlaceholderValue($property);

        if ($placeholderValue === null) {
            throw $e;
        }

        $property->setValue($object, $placeholderValue);
    }

    private function getPropertyPlaceholderValue(ReflectionProperty $property): ?string
    {
        $encryptedAttributes = $property->getAttributes(Encrypted::class);

        if (count($encryptedAttributes) !== 1) {
            throw new SingleEncryptedAttributeExpected(count($encryptedAttributes));
        }

        /** @var ReflectionAttribute $encryptedAttribute */
        $encryptedAttribute = array_pop($encryptedAttributes);

        /** @var Encrypted $encryptedAttributeInstance */
        $encryptedAttributeInstance = $encryptedAttribute->newInstance();

        return $encryptedAttributeInstance->getPlaceholder();
    }
}
