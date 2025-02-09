<?php

namespace ApiTest\Tests;

class CaptchaServiceTest extends BaseTestCase
{
    public function testGenerateCaptcha(): void
    {
        $captchaData = $this->captchaService->generateCaptchaImage();

        $this->assertIsArray($captchaData);
        $this->assertArrayHasKey('key', $captchaData);
        $this->assertArrayHasKey('image', $captchaData);
        $this->assertNotEmpty($captchaData['key']);
        $this->assertNotEmpty($captchaData['image']);
    }

    public function testVerifyUserInputInvalid(): void
    {
        $captchaData = $this->captchaService->generateCaptchaImage();
        $captchaKey = $captchaData['key'];
        $this->assertNotEmpty($captchaKey);

        $userInput = 'wrong_input';
        $response = $this->captchaService->verifyUserInput($captchaKey, $userInput);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('is_valid', $response);
        $this->assertArrayHasKey('message', $response);
        $this->assertFalse($response['is_valid']);
    }
}
