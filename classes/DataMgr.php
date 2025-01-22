<?php

namespace Test;

abstract class DataMgr
{
    protected string $filePath;
    
    public function __construct(string $filePath) 
    {
        $this->filePath = $filePath;
    }

    protected function loadContents(string $class): array
    {
        if (!file_exists($this->filePath) || filesize($this->filePath) === 0) {
            return [];
        }
        $data = json_decode(file_get_contents($this->filePath), true);
        return array_map(fn($item) => $class::of($item), $data);
    }

    protected function saveContents(array $data): void
    {
        file_put_contents($this->filePath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}