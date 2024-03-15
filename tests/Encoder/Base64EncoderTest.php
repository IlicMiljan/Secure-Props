<?php

namespace IlicMiljan\SecureProps\Tests\Encoder;

use IlicMiljan\SecureProps\Encoder\Base64Encoder;
use PHPUnit\Framework\TestCase;

class Base64EncoderTest extends TestCase
{
    private Base64Encoder $encoder;

    protected function setUp(): void
    {
        $this->encoder = new Base64Encoder();
    }

    public function testEncode(): void
    {
        $string = "Hello, World!";
        $expectedEncodedString = base64_encode($string);

        $this->assertEquals(
            $expectedEncodedString,
            $this->encoder->encode($string),
            "The encoded string does not match the expected output."
        );
    }

    public function testDecode(): void
    {
        $encodedString = "SGVsbG8sIFdvcmxkIQ==";
        $expectedDecodedString = base64_decode($encodedString);

        $this->assertEquals(
            $expectedDecodedString,
            $this->encoder->decode($encodedString)
        );
    }

    public function testEncodeDecode(): void
    {
        $originalString = "Test encode and decode!";

        $encodedString = $this->encoder->encode($originalString);
        $decodedString = $this->encoder->decode($encodedString);

        $this->assertEquals(
            $originalString,
            $decodedString
        );
    }
}
