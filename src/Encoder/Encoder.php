<?php

namespace IlicMiljan\SecureProps\Encoder;

use SensitiveParameter;

/**
 * Encodes and decodes string values.
 *
 * This interface should be implemented by classes that provide mechanisms
 * for encoding and decoding string values.
 *
 * Implementations could, for example, apply base64 encoding/decoding, or any
 * other form of transformation that maintains the reversibility of the value.
 */
interface Encoder
{
    /**
     * Encodes the provided string value.
     *
     * Takes a string as input and returns an encoded version of the string.f
     *
     * @param string $value The string value to encode.
     * @return string The encoded string.
     */
    public function encode(#[SensitiveParameter] string $value): string;

    /**
     * Decodes the provided encoded string value.
     *
     * Takes an encoded string as input and returns the original unencoded
     * version of the string.
     *
     * @param string $value The encoded string to decode.
     * @return string The original, unencoded string.
     */
    public function decode(#[SensitiveParameter] string $value): string;
}
