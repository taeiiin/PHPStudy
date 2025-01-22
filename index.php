<?php
require_once __DIR__ . '/vendor/autoload.php';

use Test\PostMgr;
use Test\GuestMgr;

$postMgr = new PostMgr('data/board.json');
$guestMgr = new GuestMgr('data/guest.json');
$posts = $postMgr->loadPosts();
$guests = $guestMgr->loadGuests();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>Test</title>
    <link rel="stylesheet" href="https://unpkg.com/mvp.css">
    <style>
        .container {
            display: flex;
            width: 100%;
            height: 80vh;
        }
        .guestb {
            width: 20%;
            padding: 10px;
            margin: 10px;
            border-right: 1px solid #ccc;
            overflow-y: auto;
        }
        .board {
            width: 80%;
            padding: 10px;
            margin: 10px;
            overflow-y: auto;
        }
        .title {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        hr {
            margin-top: 10px;
            margin-bottom: 10px;
        }
        a {
            display: block;
            text-align: center;
            text-decoration: none;
            color: #333;
        }
        h2 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1 class="title">Test Page</h1>
    <div class="container">
        <div class="guestb">
            <h2>Guest Book</h2>
            <a href="guestb.php">방명록 보기</a><br>
            <hr>
            <?php
            if (!empty($guests)) {
                foreach($guests as $guest) {
                    echo "<div><strong>" . htmlspecialchars($guest->name) . "</strong> : ";
                    echo nl2br(htmlspecialchars($guest->msg))."<br><small>" . $guest->createdAt . "</small></div><hr>";
                }
            } else {
                echo "<p>방명록 없음</p>";
            }
            ?>
        </div>
        <div class="board">
            <h2>Board</h2>
            <a href="board.php">게시판 보기</a><br>
            <hr>
            <?php
            if (!empty($posts)) {
                foreach($posts as $post) {
                    echo "<div><h3>" . htmlspecialchars($post->title) . " <small>" . htmlspecialchars($post->writer) . "</small></h3>";
                    echo "<p>" . nl2br(htmlspecialchars($post->content)) . "</p>";
                    echo "<small>" . $post->category . " | " . $post->createdAt . "</small>";
                    echo "</div><hr>";
                }
            } else {
                echo "<p>게시글 없음</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>