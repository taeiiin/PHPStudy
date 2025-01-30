<?php

namespace ApiTest;

use PHPUnit\Framework\TestCase;
use ApiTest\CaptchaService;
use ApiTest\CaptchaClient;

class CaptchaServiceTest extends TestCase
{
    private CaptchaService $captchaService;

    protected function setUp(): void
    {
        $clientId = 'O5RNj_QUem1aamQOLDfO';
        $clientSecret = '319LhOgaB1';

        $captchaClient = new CaptchaClient($clientId, $clientSecret);
        $this->captchaService = new CaptchaService($captchaClient);
    }

    public function testGenerateCaptcha(): void
    {
        $captchaData = $this->captchaService->generateCaptcha();

        $this->assertArrayHasKey('key', $captchaData);
        $this->assertArrayHasKey('image_url', $captchaData);
        $this->assertNotEmpty($captchaData['key']);
        $this->assertStringContainsString('https://openapi.naver.com/v1/captcha/ncaptcha', $captchaData['image_url']);
    }

    public function testVerifyUserInputWithInvalidValue()
    {
        $captchaData = $this->captchaService->generateCaptcha();
        $key = $captchaData['key'];
        file_get_contents($captchaData['image_url']);

        $value = 'wrong_value';

        $result = $this->captchaService->verifyUserInput($key, $value);

        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('is_valid', $result);
        $this->assertArrayHasKey('message', $result);
        $this->assertTrue($result['success']);
        $this->assertFalse($result['is_valid']);
        $this->assertEquals('Invalid captcha', $result['message']);
    }

}
