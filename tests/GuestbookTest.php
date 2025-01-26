<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Carbon\Carbon;

class GuestbookTest extends TestCase
{
    public function testGuestbookInitialization(): void
    {
        $now = Carbon::now()->toDateTimeString();
        $guestbook = $this->generateGuest($now);

        $this->assertSame('test-id', $guestbook->getId());
        $this->assertSame('test-name', $guestbook->getName());
        $this->assertSame('test-msg', $guestbook->getMsg());
        $this->assertSame($now, $guestbook->getCreatedAt()->toDateTimeString());
    }

    public function testGuestbookDefaultValues(): void
    {
        $guestbook = new Guestbook();

        $this->assertNotEmpty($guestbook->getId());
        $this->assertSame('', $guestbook->getName());
        $this->assertSame('', $guestbook->getMsg());

        $now = Carbon::now()->toDateTimeString();
        $this->assertEquals($now, $guestbook->getCreatedAt()->toDateTimeString());
    }

    public function testGuestbookJsonSerialize(): void
    {
        $now = Carbon::now()->toDateTimeString();
        $guestbook = $this->generateGuest($now);

        $json = $guestbook->jsonSerialize();

        $this->assertIsArray($json);
        $this->assertSame('test-id', $json['id']);
        $this->assertSame('test-name', $json['name']);
        $this->assertSame('test-msg', $json['msg']);
        $this->assertSame($now, $json['createdAt']);
    }

    public function testGuestbookOfMethod(): void
    {
        $now = Carbon::now()->toDateTimeString();
        $guestbook = $this->generateGuest($now);

        $this->assertInstanceOf(Guestbook::class, $guestbook);
        $this->assertSame('test-id', $guestbook->getId());
        $this->assertSame('test-name', $guestbook->getName());
        $this->assertSame('test-msg', $guestbook->getMsg());
        $this->assertSame($now, $guestbook->getCreatedAt()->toDateTimeString());
    }

    public function testDynamicGetter(): void
    {
        $now = Carbon::now()->toDateTimeString();
        $guestbook = $this->generateGuest($now);

        $this->assertSame('test-id', $guestbook->getId());
        $this->assertSame('test-name', $guestbook->getName());
        $this->assertSame('test-msg', $guestbook->getMsg());
        $this->assertSame($now, $guestbook->getCreatedAt()->toDateTimeString());

        //$this->expectException(\Error::class);
        //$nonExistentProperty = $guestbook->nonExistentKey;
    }

    private function generateGuest(string $now): Guestbook
    {
        $data = [
            'id' => 'test-id',
            'name' => 'test-name',
            'msg' => 'test-msg',
            'created_at' => $now,
        ];

        return new Guestbook($data);
    }

}
