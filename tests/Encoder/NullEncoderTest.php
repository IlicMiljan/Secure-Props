<?php

namespace IlicMiljan\SecureProps\Tests\Encoder;

use IlicMiljan\SecureProps\Encoder\NullEncoder;
use PHPUnit\Framework\TestCase;

class NullEncoderTest extends TestCase
{
    private NullEncoder $encoder;

    protected function setUp(): void
    {
        $this->encoder = new NullEncoder();
    }

    public function testEncode(): void
    {
        $string = "This is a test string.";

        $this->assertEquals(
            $string,
            $this->encoder->encode($string)
        );
    }

    public function testDecode(): void
    {
        $string = "Decoding test string.";

        $this->assertEquals(
            $string,
            $this->encoder->decode($string)
        );
    }

    public function testEncodeDecode(): void
    {
        $originalString = "Encode then decode this!";

        $encodedString = $this->encoder->encode($originalString);
        $decodedString = $this->encoder->decode($encodedString);

        /** @noinspection PhpConditionAlreadyCheckedInspection */
        $this->assertEquals(
            $originalString,
            $decodedString,
        );
    }
}
