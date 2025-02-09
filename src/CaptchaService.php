<?php
namespace ApiTest;

use ApiTest\Exception\NaverApiException;

class CaptchaService
{
    private CaptchaClient $captchaClient;

    public function __construct(CaptchaClient $captchaClient)
    {
        $this->captchaClient = $captchaClient;
    }

    public function generateCaptchaImage(): ?array
    {
        try {
            $captchaResponse = $this->captchaClient->requestCaptchaKey(0);
            $captchaImage = $this->captchaClient->requestCaptchaImage($captchaResponse->getKey());

            return [
                'key' => $captchaResponse->getKey(),
                'image' => $captchaImage,
            ];
        } catch (NaverApiException $e) {
            error_log("Captcha generation failed: " . $e->getMessage());
            return null;
        }
    }

    public function verifyCaptcha(string $key, string $value): bool
    {
        try {
            return $this->captchaClient->verifyCaptchaInput($key, $value)->getData()['result'] == 1;
        } catch (NaverApiException $e) {
            return false;
        }
    }

    public function verifyUserInput(string $key, string $value): array
    {
        return [
            'is_valid' => $this->isValidInput($key, $value) && $this->verifyCaptcha($key, $value),
            'message' => $this->isValidInput($key, $value) ? 'Valid Captcha' : 'Invalid Captcha',
        ];
    }

    private function isValidInput(?string $key, ?string $value): bool
    {
        return !empty($key) && !empty($value);
    }
}