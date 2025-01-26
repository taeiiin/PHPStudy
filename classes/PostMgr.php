<?php

namespace Test;

class PostMgr extends DataMgr
{

    public function __construct()
    {
        parent::__construct('Post');
    }

    public function loadPosts(int $orderBy = self::DESC): array
    {
        return $this->loadContents(Post::class, $orderBy);
    }

    public function savePost(Post $post): void
    {
        $this->saveContents($post->jsonSerialize());
    }

    public function deletePost(string $id): void
    {
        $this->deleteContents($id);
    }

    function map($item): Post
    {
        if (!method_exists(Post::class, 'of')) {
            return Post::of($item);
        }
        return new Post($item);
    }


}