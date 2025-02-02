<?php

namespace ApiTest;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Exception\GuzzleException;
use Dotenv\Dotenv;

class CaptchaServiceTest extends TestCase
{
    private CaptchaService $captchaService;
    private CaptchaClient $captchaClient;

    protected function setUp(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        $clientId = $_ENV['NAVER_API_CLIENT_ID'];
        $clientSecret = $_ENV['NAVER_API_CLIENT_SECRET'];

        $this->captchaClient = new CaptchaClient($clientId, $clientSecret);
        $this->captchaService = new CaptchaService($this->captchaClient);
    }

    public function testGenerateCaptcha(): void
    {
        $captchaData = $this->captchaService->generateCaptcha();

        $this->assertIsArray($captchaData, 'Captcha data should be an array');
        $this->assertArrayHasKey('key', $captchaData, 'Captcha data should contain key');
        $this->assertArrayHasKey('image', $captchaData, 'Captcha data should contain image');
        $this->assertNotEmpty($captchaData['key'], 'Captcha key should not be empty');
        $this->assertNotEmpty($captchaData['image'], 'Captcha image should not be empty');
        $this->assertStringStartsWith('data:image/', $captchaData['image'], 'Captcha image should be a base64 encoded');
    }

    public function testVerifyUserInput(): void
    {
        $key = $this->captchaService->generateCaptcha()['key'];
        $invalidResponse = $this->captchaService->verifyUserInput($key, 'wrong_value');

        $this->assertFalse($invalidResponse['is_valid'], 'Incorrect captcha value');
        $this->assertEquals('Invalid Captcha', $invalidResponse['message'], 'Incorrect captcha message');
    }

    public function testHandleException(): void
    {
        $reflection = new \ReflectionClass(CaptchaService::class);
        $method = $reflection->getMethod('handleException');
        $method->setAccessible(true);

        $exception = new \Exception('Test exception message');
        $response = $method->invokeArgs($this->captchaService, [$exception, 'Test error']);

        $this->assertIsArray($response, 'Exception Handling should be an array');
        $this->assertFalse($response['is_valid'], 'Exception should return is_valid response');
        $this->assertStringContainsString('Test error', $response['message'], 'Exception message should be included in response');
        $this->assertStringContainsString('Test exception message', $response['message'], 'Exception message should be included in response');
    }

    public function testFormatResponse(): void
    {
        $reflection = new \ReflectionClass(CaptchaService::class);
        $method = $reflection->getMethod('formatResponse');
        $method->setAccessible(true);

        $validResponse = $method->invokeArgs($this->captchaService, [true]);
        $invalidResponse = $method->invokeArgs($this->captchaService, [false]);

        $this->assertTrue($validResponse['is_valid'], 'True input should return valid response');
        $this->assertEquals('Valid Captcha', $validResponse['message'], 'True input should return valid response');

        $this->assertFalse($invalidResponse['is_valid'], 'False input should return invalid response');
        $this->assertEquals('Invalid Captcha', $invalidResponse['message'], 'False input should return invalid response');
    }

}
