<?php

namespace Test;

class PostMgr extends DataMgr
{
    public function loadPosts(int $order = 0): array
    {
        $posts = $this->loadContents(Post::class);
        return $order === 0 ? array_reverse($posts) : $posts;
    }

    public function savePost(Post $post): void
    {
        $posts = $this->loadPosts(1);
        $posts[] = $post;
        $this->saveContents($posts);
    }

    public function deletePost(string $id): void
    {
        $posts = array_filter($this->loadPosts(1), fn($post) => $post['id'] !== $id);
        $this->saveContents(array_values($posts));
    }
}