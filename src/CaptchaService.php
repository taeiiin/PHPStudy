<?php
namespace ApiTest;

use GuzzleHTTP\Exception\GuzzleException;

class CaptchaService
{
    private const CAPTCHA_IMAGE_URL_FORMAT = 'https://openapi.naver.com/v1/captcha/ncaptcha?key=%s';
    private CaptchaClient $captchaClient;

    public function __construct(CaptchaClient $captchaClient)
    {
        $this->captchaClient = $captchaClient;
    }

    public function generateCaptcha(): ?array
    {
        try {
            $key = $this->captchaClient->requestCaptchaKey(0);
            if ($key === null) {
                return null;
            }

            $base64Image = $this->captchaClient->requestCaptchaImage($key);
            if ($base64Image === null) {
                return null;
            }

            return [
                'key' => $key,
                'image' => $base64Image,
            ];
        } catch (GuzzleException $e) {
            ErrorHandler::logHttpError($e->getCode(), $e->getMessage());
            return null;
        } catch (\Exception $e) {
            error_log('Unexpected error: ' . $e->getMessage());
            return null;
        }
    }

    public function verifyUserInput(string $key, string $value): array
    {
        try {
            $isValid = $this->captchaClient->verifyCaptchaInput($key, $value);

            return [
                'is_valid' => $isValid,
                'message' => $isValid ? 'Valid Captcha' : 'Invalid Captcha',
            ];
        } catch (GuzzleException $e) {
            ErrorHandler::logHttpError($e->getCode(), $e->getMessage());
            return [
                'is_valid' => false,
                'message' => $e->getMessage(),
            ];
        } catch (\Exception $e) {
            error_log('Unexpected error: ' . $e->getMessage());
            return [
                'is_valid' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}