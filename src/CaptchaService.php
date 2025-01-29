<?php
namespace ApiTest;

use GuzzleHTTP\Exception\GuzzleException;

class CaptchaService
{
    private CaptchaClient $captchaClient;

    public function __construct(CaptchaClient $captchaClient)
    {
        $this->captchaClient = $captchaClient;
    }

    public function generateCaptcha(): array
    {
        //echo "Generating Captcha Key...\n";
        $key = $this->captchaClient->requestCaptchaKey(0);
        //echo "Captcha Key: $key\n";

        //echo "Requesting Captcha Image...\n";
        $imageUrl = "https://openapi.naver.com/v1/captcha/ncaptcha?key=$key";
        //echo "Captcha generated!\n";

        return [
            'key' => $key,
            'image_url' => $imageUrl
        ];
    }

    public function verifyCaptcha(string $key, string $value): array
    {
        $isValid = $this->captchaClient->verifyCaptcha($key, $value);

        return [
            'success' => true,
            'is_valid' => $isValid,
            'message' => $isValid ? 'Valid captcha' : 'Invalid captcha',
        ];
    }
}