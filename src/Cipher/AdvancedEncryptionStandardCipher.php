<?php

namespace IlicMiljan\SecureProps\Cipher;

use IlicMiljan\SecureProps\Reader\Exception\FailedCalculatingInitializationVectorLength;
use IlicMiljan\SecureProps\Reader\Exception\FailedDecryptingValue;
use IlicMiljan\SecureProps\Reader\Exception\FailedEncryptingValue;
use IlicMiljan\SecureProps\Reader\Exception\FailedGeneratingInitializationVector;
use InvalidArgumentException;
use SensitiveParameter;

class AdvancedEncryptionStandardCipher implements Cipher
{
    private const CIPHER = 'AES-256-GCM';
    private const TAG_LENGTH = 16;

    public function __construct(
        #[SensitiveParameter]
        private string $key
    ) {
        if (strlen($key) !== 32) {
            throw new InvalidArgumentException('Key must be 32 bytes (256 bits) long.');
        }
    }

    /**
     * @param string $string
     * @return string
     *
     * @throws FailedCalculatingInitializationVectorLength
     * @throws FailedGeneratingInitializationVector
     * @throws FailedEncryptingValue
     */
    public function encrypt(#[SensitiveParameter] string $string): string
    {
        $iv = $this->generateInitializationVector(self::CIPHER);

        $encryptedString = openssl_encrypt($string, self::CIPHER, $this->key, 0, $iv, $tag, '', self::TAG_LENGTH);

        if ($encryptedString === false) {
            throw new FailedEncryptingValue();
        }

        return base64_encode($iv . $encryptedString . $tag);
    }

    /**
     * @param string $string
     * @return string
     *
     * @throws FailedCalculatingInitializationVectorLength
     * @throws FailedDecryptingValue
     */
    public function decrypt(#[SensitiveParameter] string $string): string
    {
        $data = base64_decode($string);

        $ivLength = $this->calculateInitializationVectorLength(self::CIPHER);

        $iv = substr($data, 0, $ivLength);
        $tag = substr($data, -self::TAG_LENGTH);
        $encryptedString = substr($data, $ivLength, -self::TAG_LENGTH);

        $decryptedString = openssl_decrypt($encryptedString, self::CIPHER, $this->key, 0, $iv, $tag);

        if ($decryptedString === false) {
            throw new FailedDecryptingValue();
        }

        return $decryptedString;
    }

    /**
     * @param string $cipher
     * @return string
     *
     * @throws FailedCalculatingInitializationVectorLength
     * @throws FailedGeneratingInitializationVector
     */
    public function generateInitializationVector(string $cipher): string
    {
        $ivLength = $this->calculateInitializationVectorLength($cipher);
        $cryptoStrong = false;

        $iv = openssl_random_pseudo_bytes($ivLength, $cryptoStrong);

        if (!$cryptoStrong) {
            throw new FailedGeneratingInitializationVector($ivLength);
        }

        return $iv;
    }

    public function calculateInitializationVectorLength(string $cipher): int
    {
        $ivLength = openssl_cipher_iv_length($cipher);

        if ($ivLength === false) {
            throw new FailedCalculatingInitializationVectorLength($cipher);
        }

        return $ivLength;
    }
}
