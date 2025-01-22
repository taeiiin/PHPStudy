<?php

namespace Test;

class Post implements \JsonSerializable
{
    private array $data;

    public function __construct(array $data = [])
    {
        $this->data = [
            'id' => $data['id'] ?? uniqid(),
            'title' => $data['title'] ?? '',
            'writer' => $data['writer'] ?? '익명',
            'content' => $data['content'] ?? '',
            'category' => $data['category'] ?? '자유',
            'createdAt' => $data['createdAt'] ?? (new \DateTime())->format('Y-m-d H:i:s'),
        ];
    }

    //JSON -> 객체
    public static function of(array $item): Post
    {
        return new Post($item);
    }

    //JSON 직렬화
    public function jsonSerialize(): array
    {
        return $this->data;
    }

    //동적 데이터 접근자
    public function __get(string $key): string
    {
        return $this->data[$key] ?? '';
    }
}