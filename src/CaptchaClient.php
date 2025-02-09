<?php
namespace ApiTest;

use ApiTest\Exception\NaverApiException;
use ApiTest\Exception\NaverCaptchaException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

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

    private function sendAPIRequest(string $endpoint, array $query = [], bool $jsonParse = true): array|string
    {
        try {
            $response = $this->client->get($endpoint, ['query' => $query]);
            $responseBody = $response->getBody()->getContents();
            return $jsonParse ? json_decode($responseBody, true) : $responseBody;
        } catch (GuzzleException | \Exception $e) {
            $apiException = new NaverApiException($e->getMessage(), $e->getCode());
            return [];
        }
    }

    public function requestCaptchaKey(int $code): ?CaptchaResponse
    {
        $response = $this->sendAPIRequest(self::ENDPOINT_KEY, ['code' => $code]);
        return empty($response) ? null : new CaptchaResponse($response);
    }

    public function requestCaptchaImage(string $key): string
    {
        $imageData = $this->sendAPIRequest(self::ENDPOINT_IMAGE, ['key' => $key], false);
        error_log("Generated Captcha Image: " . substr(base64_encode($imageData), 0, 50) . "..."); // 앞부분만 출력

        return base64_encode($imageData);
    }

    public function verifyCaptchaInput(string $key, string $value): CaptchaResponse
    {
        return new CaptchaResponse($this->sendAPIRequest(self::ENDPOINT_KEY, [
            'code' => 1,
            'key' => $key,
            'value' => $value,
        ]));
    }
}