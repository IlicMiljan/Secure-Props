<?php

namespace IlicMiljan\SecureProps\Tests\Cipher;

use IlicMiljan\SecureProps\Cipher\Cipher;
use IlicMiljan\SecureProps\Cipher\Exception\CipherException;
use IlicMiljan\SecureProps\Cipher\TagAwareCipher;
use IlicMiljan\SecureProps\Encoder\Encoder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TagAwareCipherTest extends TestCase
{
    /**
     * @var Cipher&MockObject
     */
    private $decoratedCipher;
    /**
     * @var Encoder&MockObject
     */
    private $encoder;
    private TagAwareCipher $cipher;

    protected function setUp(): void
    {
        $this->decoratedCipher = $this->createMock(Cipher::class);
        $this->encoder = $this->createMock(Encoder::class);

        $this->cipher = new TagAwareCipher($this->decoratedCipher, $this->encoder);
    }

    /**
     * @throws CipherException
     */
    public function testEncrypt(): void
    {
        $encryptedText = 'encryptedText';
        $encodedText = 'encodedText';

        $this->decoratedCipher->expects($this->once())
            ->method('encrypt')
            ->with('plainText')
            ->willReturn($encryptedText);

        $this->encoder->expects($this->once())
            ->method('encode')
            ->with($this->stringContains('<ENC>' . $encryptedText . '</ENC>'))
            ->willReturn($encodedText);


        $result = $this->cipher->encrypt('plainText');

        $this->assertEquals($encodedText, $result);
    }

    /**
     * @throws CipherException
     */
    public function testDecryptWithEncryptedTag(): void
    {
        $encodedTextWithEncTags = 'encodedTextWithTags';
        $decryptedText = 'plainText';

        $this->encoder->expects($this->once())
            ->method('decode')
            ->with($encodedTextWithEncTags)
            ->willReturn('<ENC>encryptedText</ENC>');

        $this->decoratedCipher->expects($this->once())
            ->method('decrypt')
            ->with($this->equalTo('encryptedText'))
            ->willReturn($decryptedText);

        $result = $this->cipher->decrypt($encodedTextWithEncTags);

        $this->assertEquals($decryptedText, $result);
    }


    /**
     * @throws CipherException
     */
    public function testDecryptWithoutEncryptedTag(): void
    {
        $this->encoder->expects($this->once())->method('decode');
        $this->decoratedCipher->expects($this->never())->method('decrypt');

        $result = $this->cipher->decrypt('plainText');

        $this->assertEquals('plainText', $result);
    }
}
