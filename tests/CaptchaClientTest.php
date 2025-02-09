<?php

namespace ApiTest\Tests;

use ApiTest\CaptchaResponse;

class CaptchaClientTest extends BaseTestCase
{
    public function testRequestCaptchaKey(): void
    {
        $captchaResponse = $this->captchaClient->requestCaptchaKey(0);
        $this->assertInstanceOf(CaptchaResponse::class, $captchaResponse);
        $this->assertNotEmpty($captchaResponse->getKey());
    }

    public function testRequestCaptchaImage(): void
    {
        $captchaResponse = $this->captchaClient->requestCaptchaKey(0);
        $captchaKey = $captchaResponse->getKey();
        $this->assertNotEmpty($captchaKey);

        $captchaImage = $this->captchaClient->requestCaptchaImage($captchaKey);
        $this->assertNotEmpty($captchaImage);
    }

    public function testVerifyCaptchaInput(): void
    {
        $captchaResponse = $this->captchaClient->requestCaptchaKey(0);
        $captchaKey = $captchaResponse->getKey();
        $this->assertNotEmpty($captchaKey);

        $userInput = '~';
        $verifyInput = $this->captchaClient->verifyCaptchaInput($captchaKey, $userInput);
        $this->assertInstanceOf(CaptchaResponse::class, $verifyInput);
    }
}