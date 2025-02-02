<?php

namespace ApiTest;

class ErrorHandler
{
    public static function logHttpError(int $statusCode, string $message): void
    {
        $errorMessages = [
            400 => '400 Bad Request: 잘못된 검증 요청입니다.',
            401 => '401 Unauthorized: 인증을 실패했습니다.',
            403 => '403 Forbidden: 권한이 없습니다.',
            404 => '404 Not Found: 요청한 리소스를 찾을 수 없습니다.',
            500 => '500 Internal Server Error: 서버 내부 오류 발생'
        ];

        $logMessage = $errorMessages[$statusCode] ?? "HTTP $statusCode Error: $message";
        error_log($logMessage);
    }

    public static function logApiError(?int $errorCode, ?string $message): void
    {
        $errorMessages = [
            'CT001' => 'CT001: Invalid key',
            'CT002' => 'CT002: Unissued Image',
            'CT0500' => 'CT500: System Error'
        ];

        $errorCode = $errorCode ?? 'UNKNOWN_ERROR';
        $logMessage = $errorMessages[$errorCode] ?? "API Error $errorCode: $message";

        if (!$message) {
            $logMessage .= ' (No additional message provided)';
        }

        error_log($logMessage);
    }
}