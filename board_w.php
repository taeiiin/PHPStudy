<?php
require_once __DIR__ . '/vendor/autoload.php';
use Test\PostMgr;
use Test\Post;

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $title = htmlspecialchars($_POST['title']);
    $writer = htmlspecialchars($_POST['writer']);
    $content = htmlspecialchars($_POST['content']);
    $category = htmlspecialchars($_POST['category']);

    $post = new Post([
        'title' => $title,
        'writer' => $writer,
        'content' => $content,
        'category' => $category,
    ]);

    $postMgr = new PostMgr('data/board.json');
    $postMgr->savePost($post);

    header("Location: board.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>Writing</title>
    <link rel="stylesheet" href="https://unpkg.com/mvp.css">
    <style>
        h1 {
            text-align: center;
        }
        a {
            display: block;
            text-align: center;
            text-decoration: none;
            color: #333;
        }
        hr {
            margin: 10px auto;
            width: 80%;
            border: none;
            border-top: 1px solid #ccc;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 50%;
            margin: 0 auto;
            margin-top: 30px;
        }
        input, textarea, button {
            width: 100%;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>글 작성</h1>
    <a href="board.php">글 목록</a>
    <hr>
    <form method="POST">
        제목<input type="text" name="title" required><br>
        글쓴이<input type="text" name="writer" required><br>
        내용<textarea name="content" rows="15px" required></textarea><br>
        카테고리
        <select name="category" required>
            <option value="자유">자유</option>
            <option value="정보">정보</option>
            <option value="기타">기타</option>
        </select><br>
        <button type="submit">작성</button>
    </form>
</body>
</html>