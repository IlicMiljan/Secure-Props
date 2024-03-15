<?php

namespace IlicMiljan\SecureProps\Cipher;

use IlicMiljan\SecureProps\Encoder\Base64Encoder;
use IlicMiljan\SecureProps\Encoder\Encoder;
use SensitiveParameter;

class TagAwareCipher implements Cipher
{
    private Encoder $encoder;

    public function __construct(
        private Cipher $cipher,
        ?Encoder $encoder = null
    ) {
        if ($encoder === null) {
            $this->encoder = new Base64Encoder();
        } else {
            $this->encoder = $encoder;
        }
    }

    public function encrypt(#[SensitiveParameter] string $string): string
    {
        $encryptedString = $this->cipher->encrypt($string);

        return $this->encoder->encode('<ENC>' . $encryptedString . '</ENC>');
    }

    public function decrypt(#[SensitiveParameter] string $string): string
    {
        $data = $this->encoder->decode($string);

        if (!$this->shouldDecrypt($data)) {
            return $string;
        }

        preg_match('/^<ENC>(.*)<\/ENC>$/', $data, $matches);

        return $this->cipher->decrypt($matches[1]);
    }

    private function shouldDecrypt(string $string): bool
    {
        return preg_match('/^<ENC>(.*)<\/ENC>$/', $string) === 1;
    }
}
