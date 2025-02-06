<?php

namespace ApiTest\Exception;

use Exception;

class NaverCaptchaException extends Exception
{
    private ?string $errorCode;

    public function __construct(string $message, int $statusCode = 500, ?string $errorCode = null)
    {
        $this->errorCode = $errorCode;
        $formattedMessage = $this->formatErrorMessage($message, $statusCode);
        parent::__construct($formattedMessage, $statusCode);
    }

    private function formatErrorMessage(string $message, ?string $errorCode): string
    {
        $captchaErrorMessages = [
            'CT001' => '잘못된 키 값',
            'CT002' => '이미지 미생성',
            'CT500' => '네이버 API 내부 시스템 오류'
        ];

        $defaultMessage = "정의되지 않은 캡차 오류 발생";
        $errorMessage = $captchaErrorMessages[$errorCode] ?? $defaultMessage;

        return sprintf("[Captcha 오류 %s] %s - %s", $errorCode, $errorMessage, $message);
    }

    private function getErrorCode(): ?string
    {
        return $this->errorCode;
    }
}