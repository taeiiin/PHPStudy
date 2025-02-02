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

    public function generateCaptcha(): ?array
    {
        try {
            $key = $this->captchaClient->requestCaptchaKey(0);
            $captchaImage = $this->captchaClient->requestCaptchaImage($key);

            return ($key && $captchaImage) ? ['key' => $key, 'image' => $captchaImage] : null;
        } catch (\Exception $e) {
            return $this->handleException($e, 'Failed to generate captcha');
        }
    }

    public function verifyUserInput(string $key, string $value): array
    {
        try {
            $isValid = $this->captchaClient->verifyCaptchaInput($key, $value);
            return $this->formatResponse($isValid);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Captcha verification failed');
        }
    }

    private function handleException(\Exception $e, string $errorMessage): ?array
    {
        if ($e instanceof GuzzleException) {
            ErrorHandler::logHttpError($e->getCode(), $errorMessage);
        } else {
            ErrorHandler::logHttpError(500, "Unexpected error: " . $e->getMessage());
        }

        return [
            'is_valid' => false,
            'message' => $errorMessage . ": " . $e->getMessage(),
        ];
    }

    private function formatResponse(bool $isValid): array
    {
        return [
            'is_valid' => $isValid,
            'message' => $isValid ? 'Valid Captcha' : 'Invalid Captcha',
        ];
    }
}