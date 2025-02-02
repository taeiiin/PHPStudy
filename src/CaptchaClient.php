<?php

namespace ApiTest;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

class CaptchaClient
{
    private const BASE_URI = "https://openapi.naver.com";
    private const ENDPOINT_KEY = "/v1/captcha/nkey";
    private const ENDPOINT_IMAGE = "/v1/captcha/ncaptcha";

    private Client $client;
    private string $clientId;
    private string $clientSecret;

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

    public function sendAPIRequest(string $endpoint, array $query = [], bool $jsonParse = true): mixed
    {
        try {
            $response = $this->client->get($endpoint, ['query' => $query]);

            if (!$jsonParse) {
                return $response->getBody()->getContents();
            }

            $data = json_decode(
                $response->getBody()->getContents(),
                true,
            );

            if (isset($data['error_code'])) {
                ErrorHandler::logApiError($data['error_code'], $data['error_message'] ?? 'Unknown error');
                return null;
            }
            return $data;
        } catch (ClientException $e) {
            ErrorHandler::logHttpError($e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            ErrorHandler::logHttpError(500, $e->getMessage());
        }
        return null;
    }

    public function requestCaptchaKey(int $code): ?string
    {
        $data = $this->sendAPIRequest(self::ENDPOINT_KEY, ['code' => $code]);

        if (!$data || isset($data['errorCode'])) {
            ErrorHandler::logApiError($data['errorCode'], $data['errorMessage'] ?? 'Unknown error');
            return null;
        }

        return $data['key'] ?? null;
    }

    public function requestCaptchaImage(string $key): ?string
    {
        $imageData = $this->sendAPIRequest(self::ENDPOINT_IMAGE, ['key' => $key], false);

        if (!$imageData) {
            return null;
        }

        return sprintf("data:image/jpg;base64,%s", base64_encode($imageData));
    }

    public function verifyCaptchaInput(string $key, string $value): bool
    {
        $data = $this->sendAPIRequest(self::ENDPOINT_KEY, [
            'code' => 1,
            'key' => $key,
            'value' => $value,
        ]);

        if (!$data || isset($data['errorCode'])) {
            ErrorHandler::logApiError($data['errorCode'], $data['errorMessage'] ?? 'Unknown error');
            return false;
        }

        return $data['result'] ?? false;
    }
}