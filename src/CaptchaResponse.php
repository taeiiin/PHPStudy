<?php

namespace ApiTest;

use ApiTest\Exception\NaverApiException;
use ApiTest\Exception\NaverCaptchaException;

class CaptchaResponse
{
    private array $data = [];
    private int $statusCode;
    private ?string $errorCode = null;
    private string $errorMessage = '';
    private ?string $imageData = null;
    private bool $valid = true;

    public function __construct(array|string $responseData, int $statusCode = 200, ?string $contentType = null)
    {
        $this->statusCode = $statusCode;

        if ($responseData === false) {
            $this->valid = false;
            $this->errorMessage = "API 요청 실패";
            return;
        }

        if ($this->isImageResponse($contentType)) {
            $this->imageData = $this->processImageResponse($responseData);
            return;
        }

        $this->data = $this->decodeResponse($responseData);
        $this->valid = $this->handleErrorResponse();
    }

    private function isImageResponse(?string $contentType): bool
    {
        return $contentType && str_contains($contentType, 'image/');
    }

    private function processImageResponse(string $responseData): string
    {
        return sprintf('data:image/jpg;base64,%s', base64_encode($responseData));
    }

    private function decodeResponse(array|string $responseData): array
    {
        if (is_string($responseData)) {
            $decodedData = json_decode($responseData, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->valid= false;
                return [];
            }
            return $decodedData;
        }
        return $responseData;
    }

    private function handleErrorResponse(): bool
    {
        if (!isset($this->data['errorCode'])) {
            return true;
        }

        $this->errorCode = $this->data['errorCode'];
        $this->errorMessage = $this->data['errorMessage'] ?? 'Unknown error';

        $this->valid = false;
        return false;
    }

    private function isCaptchaError(string $errorCode): bool
    {
        return in_array($errorCode, ['CT001', 'CT002', 'CT500'], true);
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function getKey(): ?string
    {
        return $this->data['key'] ?? null;
    }

    public function getImageData(): ?string
    {
        return $this->imageData;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }
}
