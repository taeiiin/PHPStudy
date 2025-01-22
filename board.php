<?php
require_once __DIR__ . '/vendor/autoload.php';
use Test\PostMgr;

$postMgr = new PostMgr('data/board.json');

$search = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : "";
$posts = $postMgr->loadPosts();

if (!empty($search)) {
    $posts = array_filter($posts, function ($post) use ($search) {
        return stripos($post->title, $search) !== false || stripos($post->content, $search) !== false;
    });
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" href="https://unpkg.com/mvp.css">
    <style>
        .link_container {
            display: flex;
            justify-content: center;
            gap: 20px;
            padding: 0 10%;
        }
        .container {
            justify-content: center;
            padding: 0 10%;
        }
        .link {
            text-decoration: none;
            color: #333;
        }
        .search {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            width: 70%;
            margin: 0 auto;
        }
        .search form {
            display: flex;
            align-items: center;
            width: 100%;
        }
        .s-box {
            width: 300px;
            height: 40px;
            padding: 0 10px;
            border: 1px solid #ccc;
            border-radius: 5px 0 0 5px;
            box-sizing: border-box;
            font-size: 14px;
            vertical-align: middle;
            margin-top: 15px;
        }
        .s-btn {
            padding: 0 15px;
            border: none;
            background-color: #333;
            color: white;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
            white-space: nowrap;
            box-sizing: border-box;
            font-size: 14px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .s-btn:hover {
            background-color: #555;
        }
        .none {
            text-align: center;
            margin-top: 50px;
        }
        .line {
            margin: 10px auto;
            width: 80%;
            border: none;
            border-top: 1px solid #ccc;
        }
        h1 {
            text-align: center;
        }
        a {
            text-decoration: none;
            /*color: #333;*/
        }
        div {
            margin: 20px;
        }
        hr {
            margin: 20px auto;
        }
    </style>
</head>
<body>
    <h1> Board </h1>
    <div class="link_container">
        <a href="index.php" class="link">Main</a>
        <a href="board_w.php" class="link">Write</a>
    </div>
    <hr class='line'>
    <div class="search">
        <form method="GET" action="board.php">
            <input type="text" name="search" class="s-box" placeholder="검색어 입력" value="<?= $search ?>">
            <button type="submit" class="s-btn">검색</button>
        </form>
    </div>
    <hr class="line">
    <?php
    if (!empty($posts)) {
        foreach($posts as $index => $post):
            echo "<div class='container'>";
            echo "<h3>" . htmlspecialchars($post->title) . " <small>" . htmlspecialchars($post->writer) . "</small></h3>";
            echo "<p>" . nl2br(htmlspecialchars($post->content)) . "</p>";
            echo "<small>" . htmlspecialchars($post->category) . " | " . $post->createdAt . "</small> ";
            echo "<small><a href='board_d.php?id=" . $post->id . "'>삭제</a></small>";
            echo "<hr></div>";
        endforeach;
    } else {
        echo "<p class='none'>작성된 글 없음</p>";
    }
    ?>
</body>
</html>