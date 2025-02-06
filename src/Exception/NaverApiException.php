<?php

namespace ApiTest\Exception;

use Exception;

class NaverApiException extends Exception
{
    private ?int $statusCode;

    public function __construct(string $message, int $statusCode = 500)
    {
        $this->statusCode = $statusCode;
        $formattedMessage = $this->formatErrorMessage($message, $statusCode);
        parent::__construct($formattedMessage, $statusCode);
    }

    private function formatErrorMessage(string $message, int $statusCode): string
    {
        $httpErrorMessages = [
            400 => '잘못된 요청',
            401 => '인증 실패',
            403 => '권한 없음',
            404 => '리소스를 찾을 수 없음',
            500 => '서버 내부 오류',
        ];

        $defaultMessages = "정의되지 않은 API 오류 발생";
        $errorMessage = $httpErrorMessages[$statusCode] ?? $defaultMessages;

        return sprintf("[Naver API 오류 %d] %s - %s", $statusCode, $errorMessage, $message);
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }
}