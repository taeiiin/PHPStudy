<?php

namespace Test;

use PHPUnit\Framework\TestCase;

class PostMgrTest extends TestCase
{
    private $postMgr;

    protected function setUp(): void
    {
        $this->postMgr = new PostMgr();

        $this->postMgr->db->exec("
            IF OBJECT_ID('Post', 'U') IS NOT NULL
                DROP TABLE Post;
            CREATE TABLE Post (
                id INT IDENTITY(1,1) PRIMARY KEY,
                title NVARCHAR(255) NOT NULL,
                writer NVARCHAR(255) NOT NULL,
                posting NVARCHAR(MAX) NOT NULL,
                category NVARCHAR(255) NOT NULL,
                createdAt DATETIME NOT NULL DEFAULT GETDATE()
            );
        ");
    }

    protected function tearDown(): void
    {
        $this->postMgr->db->exec("
            IF OBJECT_ID('Post', 'U') IS NOT NULL
                DROP TABLE Post;
        ");
    }

    public function testSavePost()
    {
        $post = new Post([
            'title' => 'test title',
            'writer' => 'test writer',
            'posting' => 'test posting',
            'category' => '자유'
        ]);

        $this->postMgr->savePost($post);

        $result = $this->postMgr->db->query("SELECT * FROM Post")->fetchAll(\PDO::FETCH_ASSOC);

        $this->assertCount(1, $result);
        $this->assertSame('test title', $result[0]['title']);
        $this->assertSame('test writer', $result[0]['writer']);
        $this->assertSame('test posting', $result[0]['posting']);
        $this->assertSame('자유', $result[0]['category']);
    }

    public function testLoadPosts()
    {
        $this->postMgr->db->exec("
            INSERT INTO Post (title, writer, posting, category, createdAt)
            VALUES
                ('Title1', 'Writer1', 'Content1', N'자유', GETDATE()),
                ('Title2', 'Writer2', 'Content2', N'정보', GETDATE())
        ");

        $posts = $this->postMgr->loadPosts(PostMgr::ASC);

        $this->assertInstanceOf(Post::class, $posts[0], "Loaded data is not of type Post");
        $this->assertCount(2, $posts);
        $this->assertSame('Title1', $posts[0]->title);
        $this->assertSame('Writer1', $posts[0]->writer);
        $this->assertSame('Content2', $posts[1]->posting);
        $this->assertSame('정보', $posts[1]->category);
    }

    public function testDeletePost()
    {
        $this->postMgr->db->exec("
            INSERT INTO Post (title, writer, posting, category, createdAt)
            VALUES ('To Delete', 'Writer', 'Content', '기타', GETDATE())
        ");
        $id = $this->postMgr->db->lastInsertId();
        $this->postMgr->deletePost($id);

        $result = $this->postMgr->db->query("SELECT * FROM Post")->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertCount(0, $result);
    }

}
