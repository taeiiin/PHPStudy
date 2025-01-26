<?php

namespace Test;

use Carbon\Carbon;

class Guestbook implements \JsonSerializable
{
    private string $id;
    private string $name;
    private string $msg;
    private Carbon $createdAt;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? uniqid();
        $this->name = $data['name'] ?? '';
        $this->msg = $data['msg'] ?? '';
        $this->createdAt = isset($data['createdAt']) ? Carbon::parse($data['createdAt']) : Carbon::now();
    }

    public static function of(array $item): Guestbook
    {
        return new self($item);
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'msg' => $this->msg,
            'createdAt' => $this->createdAt->toDateTimeString(),
        ];
    }

//    //동적 데이터 접근자
//    public function __get(string $key): string
//    {
//        if ($key === 'createdAt') {
//            return $this->createdAt->toDateTimeString();
//        }
//        return $this->$key ?? '';
//    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMsg(): string
    {
        return $this->msg;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }
}