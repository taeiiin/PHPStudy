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
            ErrorHandler::logHttpError($e->getCode(), $e->getMessage());
            return null;
        } catch (RequestException $e) {
            error_log('Network Error : ' . $e->getMessage());
            return null;
        } catch (\Exception $e) {
            error_log('Unexpected Error : ' . $e->getMessage());
            return null;
        }
    }

    public function requestCaptchaImage(string $key): ?string
    {
        $query = ['key' => $key];
        try {
            $response = $this->client->get(self::ENDPOINT_IMAGE, [
                'query' => $query,
            ]);

            $imageData = $response->getBody()->getContents();
            $base64Image = base64_encode($imageData);

            return "data:image/jpg;base64," . $base64Image;

        } catch (ClientException $e) {
            ErrorHandler::logHttpError($e->getCode(), $e->getMessage());
            return null;
        } catch (\Exception $e) {
            error_log('Unexpected Error : ' . $e->getMessage());
            return null;
        }
    }

    public function verifyCaptchaInput(string $key, string $value): bool
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
            ErrorHandler::logHttpError($e->getCode(), $e->getMessage());
            return false;
        } catch (\Exception $e) {
            error_log('Unexpected Error : ' . $e->getMessage());
            return false;
        }
    }
}