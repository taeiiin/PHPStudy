<?php

namespace ApiTest\Tests;

use ApiTest\CaptchaClient;
use ApiTest\CaptchaService;
use ApiTest\CaptchaResponse;
use PHPUnit\Framework\TestCase;
use Dotenv\Dotenv;

abstract class BaseTestCase extends TestCase
{
    protected CaptchaClient $captchaClient;
    protected CaptchaService $captchaService;
    protected array $validResponse;
    protected array $errorResponse;
    protected string $validImageData;
    protected string $invalidJsonResponse;

    protected function setUp(): void
    {
        parent::setUp();

        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        $clientId = $_ENV['NAVER_API_CLIENT_ID'];
        $clientSecret = $_ENV['NAVER_API_CLIENT_SECRET'];

        $this->captchaClient = new CaptchaClient($clientId, $clientSecret);
        $this->captchaService = new CaptchaService($this->captchaClient);

        $this->validResponse = ['key' => 'testKey'];
        $this->errorResponse = ['errorCode' => 'CT002', 'errorMessage' => '이미지 미생성'];
        $this->validImageData = 'testImageData';
        $this->invalidJsonResponse = '{invalid json}';
    }
}