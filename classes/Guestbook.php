<?php

namespace Test;

class Guestbook implements \JsonSerializable
{
    private array $data;

    public function __construct(array $data = [])
    {
        $this->data = [
            'id' => $data['id'] ?? uniqid(),
            'name' => $data['name'] ?? '',
            'msg' => $data['msg'] ?? '',
            'createdAt' => $data['createdAt'] ?? (new \DateTime())->format('Y-m-d H:i:s'),
        ];
    }

    //JSON -> 객체
    public static function of(array $item): Guestbook
    {
        return new Guestbook($item);
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