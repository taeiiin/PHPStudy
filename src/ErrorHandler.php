<?php

namespace ApiTest;

class ErrorHandler
{
    public static function logHttpError(int $statusCode, string $message): void
    {
        switch ($statusCode) {
            case 400:
                error_log('400 Bad Request: 잘못된 검증 요청입니다.');
                break;
            case 401:
                error_log('401 Unauthorized: 인증을 실패했습니다.');
                break;
            case 403:
                error_log('403 Forbidden: 권한이 없습니다.');
                break;
            case 404:
                error_log('404 Not Found: 요청한 리소스를 찾을 수 없습니다.');
                break;
            default:
                error_log("HTTP $statusCode Error". $e->getMessage());
                break;
        }
    }

    public static function logApiError(int $errorCode, string $message): void
    {
        switch ($errorCode) {
            case 'CT001':
                error_log('CT001: Invalid key');
                break;
            case 'CT002':
                error_log('CT002: Unissued Image');
                break;
            case 'CT500':
                error_log('CT500: System Error');
                break;
            default:
                error_log("API Error $errorCode: $message");
                break;
        }

    }
}