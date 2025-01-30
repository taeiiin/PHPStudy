<?php

namespace ApiTest;

use PHPUnit\Framework\TestCase;
use ApiTest\CaptchaClient;

class CaptchaClientTest extends TestCase
{
    private CaptchaClient $captchaClient;

    protected function setUp(): void
    {
        $envFile = ".env";
        $clientId = 'O5RNj_QUem1aamQOLDfO';
        $clientSecret = '319LhOgaB1';

        $this->captchaClient = new CaptchaClient($clientId, $clientSecret);
    }

    public function testRequestCaptchaKey(): void
    {
        $key = $this->captchaClient->requestCaptchaKey(0);
        $this->assertNotEmpty($key, 'Captcha Key should not be empty');
    }

    public function testVerifyCaptchaApi(): void
    {
        $key = $this->captchaClient->requestCaptchaKey(0);
        $imageData = $this->captchaClient->requestCaptchaImage($key);
        $this->assertNotEmpty($imageData, 'Captcha Image should not be empty');

//        $value = 'test_value';
//        $isValid = $this->captchaClient->verifyCaptchaApi($key, $value);
//        $this->assertFalse($isValid, 'Incorrect captcha value');
    }

}