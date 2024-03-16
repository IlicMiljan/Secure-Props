<?php

namespace IlicMiljan\SecureProps\Cipher;

use IlicMiljan\SecureProps\Encoder\Base64Encoder;
use IlicMiljan\SecureProps\Encoder\Encoder;
use SensitiveParameter;

class TagAwareCipher implements Cipher
{
    private const START_TAG = '<ENC>';
    private const END_TAG = '</ENC>';

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

        return $this->encoder->encode(self::START_TAG . $encryptedString . self::END_TAG);
    }

    public function decrypt(#[SensitiveParameter] string $string): string
    {
        $data = $this->encoder->decode($string);

        if (!$this->shouldDecrypt($data)) {
            return $string;
        }

        return $this->cipher->decrypt($this->extractTaggedValue($data));
    }

    private function shouldDecrypt(string $string): bool
    {
        return str_contains($string, self::START_TAG) && str_contains($string, self::END_TAG);
    }

    private function extractTaggedValue(string $string): string
    {
        $startPos = strpos($string, self::START_TAG);
        $endPos = strpos($string, self::END_TAG);

        $startPos += strlen(self::START_TAG);
        return substr($string, $startPos, $endPos - $startPos);
    }
}
