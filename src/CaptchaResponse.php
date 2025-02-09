<?php

namespace ApiTest;

use ApiTest\Exception\NaverApiException;
use ApiTest\Exception\NaverCaptchaException;

class CaptchaResponse
{
    private array $data = [];
    public ?string $imageData = null;

    public function __construct(array|string|false $responseData, ?string $contentType = null)
    {
        if ($responseData === false) {
            throw new NaverCaptchaException("API 요청 실패", 500);
        }

        if ($this->isImageResponse($contentType)) {
            $this->imageData = base64_encode($responseData);
            return;
        }

        $this->data = $this->decodeResponse($responseData);
    }

    private function isImageResponse(?string $contentType): bool
    {
        return $contentType && str_contains($contentType, 'image/');
    }

    private function decodeResponse(array|string $responseData): array
    {
        if (is_string($responseData)) {
            $decodedData = json_decode($responseData, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new NaverApiException('Invalid JSON', 500);
            }
            return $decodedData;
        }
        return $responseData;
    }

    public function getKey(): ?string
    {
        return $this->data['key'] ?? null;
    }

    public function getImageData(): ?string
    {
        return $this->imageData;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
