<?php

namespace ApiTest;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class CaptchaClient
{
    private Client $client;
    private string $clientId;
    private string $clientSecret;

    public function __construct(string $clientId, string $clientSecret)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->client = new Client([
            'base_uri' => 'https://openapi.naver.com',
            'headers' => [
                'X-Naver-Client-Id' => $this->clientId,
                'X-Naver-Client-Secret' => $this->clientSecret,
            ],
            'verify' => false,
        ]);
    }

    /**
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function requestCaptchaKey(int $code): string
    {
        $response = $this->client->get('/v1/captcha/nkey', [
            'query' => ['code' => $code],
        ]);

        $data = json_decode((string)$response->getBody()->getContents(), true, flags: JSON_THROW_ON_ERROR);

        return $data['key'] ?? throw new \RuntimeException('Invalid response');
    }

    public function requestCaptchaImage(string $key): string
    {
        $response = $this->client->get('/v1/captcha/ncaptcha', [
            'query' => ['key' => $key],
        ]);

        return $response->getBody()->getContents();
    }

    public function verifyCaptcha(string $key, string $value): bool
    {
        $response = $this->client->get('/v1/captcha/nkey', [
            'query' => [
                'code' => 1,
                'key' => $key,
                'value' => $value,
            ],
        ]);

        $data = json_decode((string)$response->getBody()->getContents(), true, flags: JSON_THROW_ON_ERROR);

        return $data['result'] ?? false;
    }
}