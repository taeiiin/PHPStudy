<?php

namespace ApiTest;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use ApiTest\ErrorHandler;

class CaptchaClient
{
    private const BASE_URI = "https://openapi.naver.com";
    private const ENDPOINT_KEY = "/v1/captcha/nkey";
    private const ENDPOINT_IMAGE = "/v1/captcha/ncaptcha";

    private Client $client;
    private string $clientId;
    private string $clientSecret;

    //API 통신 클라이언트 초기화
    public function __construct(string $clientId, string $clientSecret)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->client = new Client([
            'base_uri' => self::BASE_URI,
            'headers' => [
                'X-Naver-Client-Id' => $this->clientId,
                'X-Naver-Client-Secret' => $this->clientSecret,
            ],
        ]);
    }

    //캡차 키 발급 요청
    public function requestCaptchaKey(int $code): ?string
    {
        $query = ['code' => $code];
        try {
            $response = $this->client->get(self::ENDPOINT_KEY, [
                'query' => $query,
            ]);

            $data = json_decode(
                $response->getBody()->getContents(),
                true,
            );

            if (isset($data['errorCode'])) {
                ErrorHandler::logApiError($data['errorCode'], $data['errorMessage']);
                return null;
            }

            return $data['key'];

        } catch (ClientException $e) {
            $statusCode = $e->getCode();
            ErrorHandler::logHttpError($statusCode, $e->getMessage());
            return null;
        } catch (RequestException $e) {
            error_log('Network Error : ' . $e->getMessage());
            return null;
        } catch (\Exception $e) {
            error_log('Unexpected Error : ' . $e->getMessage());
            return null;
        }
    }

    //캡차 이미지 요청
    public function requestCaptchaImage(string $key): ?string
    {
        $query = ['key' => $key];
        try {
            $response = $this->client->get(self::ENDPOINT_IMAGE, [
                'query' => $query,
            ]);

            if (isset($data['errorCode'])) {
                ErrorHandler::logApiError($data['errorCode'], $data['errorMessage']);
                return null;
            }

            return $response->getBody()->getContents();

        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            ErrorHandler::logHttpError($statusCode, $e->getMessage());
            return null;
        } catch (\Exception $e) {
            error_log('Unexpected Error : ' . $e->getMessage());
            return null;
        }
    }

    //캡차 입력 검증
    public function verifyCaptchaApi(string $key, string $value): bool
    {
        $query = [
            'code' => 1,
            'key' => $key,
            'value' => $value,
        ];
        try {
            $response = $this->client->get(self::ENDPOINT_KEY, [
                'query' => $query,
            ]);

            $data = json_decode(
                $response->getBody()->getContents(),
                true,
                flags: JSON_THROW_ON_ERROR
            );

            if (isset($data['errorCode'])) {
                ErrorHandler::logApiError($data['errorCode'], $data['errorMessage']);
                return false;
            }

            return $data['result'];

        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            ErrorHandler::logHttpError($statusCode, $e->getMessage());
            return false;
        } catch (\Exception $e) {
            error_log('Unexpected Error : ' . $e->getMessage());
            return false;
        }
    }
}