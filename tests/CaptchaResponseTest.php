<?php

namespace ApiTest\Tests;

use ApiTest\CaptchaResponse;

class CaptchaResponseTest extends BaseTestCase
{
    public function testConstructResponse(): void
    {
        $captchaResponse = new CaptchaResponse($this->validResponse);

        $this->assertEquals('testKey', $captchaResponse->getKey());
        $this->assertNull($captchaResponse->getImageData());
    }

    public function testApiError(): void
    {
        $captchaResponse = new CaptchaResponse($this->errorResponse);
        $this->assertEmpty($captchaResponse->getKey());
    }

    public function testGetKey(): void
    {
        $captchaResponse = new CaptchaResponse($this->validResponse);
        $this->assertEquals('testKey', $captchaResponse->getKey());

        $captchaResponseError = new CaptchaResponse($this->errorResponse);
        $this->assertNull($captchaResponseError->getKey());
    }
}
