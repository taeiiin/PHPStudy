<?php

namespace ApiTest;

use PHPUnit\Framework\TestCase;
use Dotenv\Dotenv;

class CaptchaClientTest extends TestCase
{
    private CaptchaClient $captchaClient;

    protected function setUp(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        $clientId = $_ENV['NAVER_API_CLIENT_ID'];
        $clientSecret = $_ENV['NAVER_API_CLIENT_SECRET'];

        $this->captchaClient = new CaptchaClient($clientId, $clientSecret);
    }

    public function testSendAPIRequest(): void
    {
        $response = $this->captchaClient->sendAPIRequest('/invalid-endpoint', []);
        $this->assertNull($response, 'Invalid endpoint response should return null');
    }


    public function testRequestCaptchaKey(): void
    {
        $key = $this->captchaClient->requestCaptchaKey(0);
        $this->assertNotEmpty($key, 'Captcha Key should not be empty');
        $this->assertIsString($key, 'Captcha Key should be a string');
    }

    public function testRequestCaptchaImage(): void
    {
        $key = $this->captchaClient->requestCaptchaKey(0);
        $this->assertNotEmpty($key, 'Failed to get Captcha Key');

        $imageData = $this->captchaClient->requestCaptchaImage($key);
        $this->assertNotEmpty($imageData, 'Captcha Image should not be empty');
        $this->assertMatchesRegularExpression('/^data:image\/jpg;base64,/', $imageData);
    }

    public function testVerifyCaptchaInput(): void
    {
        $key = $this->captchaClient->requestCaptchaKey(0);
        $this->assertNotEmpty($key, 'Captcha Key should not be empty');

        //$validInput = 'correct_value';
        $invalidInput = 'wrong_value';

        //$validResult = $this->captchaClient->verifyCaptchaInput($key, $validInput);
        //$this->assertTrue($validInput, 'Correct Input');

        $invalidResult = $this->captchaClient->verifyCaptchaInput($key, $invalidInput);
        $this->assertFalse($invalidResult, 'Incorrect input');
    }

}