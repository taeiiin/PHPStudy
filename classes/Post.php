<?php

namespace Test;

use Carbon\Carbon;

class Post implements \JsonSerializable
{
    private string $id;
    private string $title;
    private string $writer;
    private string $posting;
    private string $category;
    private Carbon $createdAt;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? uniqid();
        $this->title = $data['title'] ?? '';
        $this->writer = $data['writer'] ?? '익명';
        $this->posting = $data['posting'] ?? '';
        $this->category = $data['category'] ?? '자유';
        $this->createdAt = isset($data['createdAt']) ? Carbon::parse($data['createdAt']) : Carbon::now();
    }

    //JSON -> 객체
    public static function of(array $item): Post
    {
        return new self($item);
    }

    //JSON 직렬화
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'writer' => $this->writer,
            'posting' => $this->posting,
            'category' => $this->category,
            'createdAt' => $this->createdAt->toDateTimeString(),
        ];
    }

    //동적 데이터 접근자
//    public function __get(string $key): string
//    {
//          if ($key === 'createdAt') {
//              return $this->createdAt->toDateTimeString();
//          }
//        return $this->$key ?? '';
//    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getWriter(): string
    {
        return $this->writer;
    }

    public function getPosting(): string
    {
        return $this->posting;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }
}