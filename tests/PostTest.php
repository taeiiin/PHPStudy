<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Carbon\Carbon;

class PostTest extends TestCase
{
    public function testPostInitialization(): void
    {
        $now = Carbon::now()->toDateTimeString();
        $post = $this->generatePost($now);

        $this->assertSame('test id', $post->getId());
        $this->assertSame('test title', $post->getTitle());
        $this->assertSame('test writer', $post->getWriter());
        $this->assertSame('test posting', $post->getPosting());
        $this->assertSame('자유', $post->getCategory());
        $this->assertSame($now, $post->getCreatedAt()->toDateTimeString());
    }

    public function testPostDefaultValues(): void
    {
        $post = new Post();

        $this->assertNotEmpty($post->getId());
        $this->assertSame('', $post->getTitle());
        $this->assertSame('익명', $post->getWriter());
        $this->assertSame('', $post->getPosting());
        $this->assertSame('자유', $post->getCategory());

        $now = Carbon::now()->toDateTimeString();
        $this->assertEquals($now, $post->getCreatedAt()->toDateTimeString());
    }

    public function testPostJsonSerialize(): void
    {
        $now = Carbon::now()->toDateTimeString();
        $post = $this->generatePost($now);
        $json = $post->jsonSerialize();

        $this->assertIsArray($json);
        $this->assertSame('test id', $json['id']);
        $this->assertSame('test title', $json['title']);
        $this->assertSame($now, $json['createdAt']);
        //$this->assertArrayHasKey('invalidKey', $json, "The key does not exist");
    }

    public function testPostOfMethod(): void
    {
        $now = Carbon::now()->toDateTimeString();
        $post = $this->generatePost($now);

        $this->assertInstanceOf(Post::class, $post);
        $this->assertSame('test id', $post->getId());
        $this->assertSame('자유', $post->getCategory());
        $this->assertSame($now, $post->getCreatedAt()->toDateTimeString());
    }

    public function testDynamicGetter(): void
    {
        $now = Carbon::now()->toDateTimeString();
        $post = $this->generatePost($now);

        $this->assertSame('test id', $post->getId());
        $this->assertSame('test writer', $post->getWriter());
        $this->assertSame($now, $post->getCreatedAt()->toDateTimeString());

    }

    private function generatePost(string $now): Post
    {
        $data = [
            'id' => 'test id',
            'title' => 'test title',
            'writer' => 'test writer',
            'posting' => 'test posting',
            'category' => '자유',
            'created_at' => $now,
        ];

        return new Post($data);
    }
}
