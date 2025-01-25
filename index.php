<?php
require_once __DIR__ . '/vendor/autoload.php';

use Test\PostMgr;
use Test\GuestMgr;

$postMgr = new PostMgr();
$guestMgr = new GuestMgr();
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
            <?php if (!empty($guests)): ?>
                <?php foreach ($guests as $guest): ?>
                    <div>
                        <strong><?= htmlspecialchars($guest->getName()) ?></strong> :
                        <?= nl2br(htmlspecialchars($guest->getMsg())) ?><br>
                        <small><?= $guest->getCreatedAt() ?></small>
                    </div>
                    <hr>
                <?php endforeach; ?>
            <?php else: ?>
                <p>방명록 없음</p>
            <?php endif; ?>
        </div>
        <div class="board">
            <h2>Board</h2>
            <a href="board.php">게시판 보기</a><br>
            <hr>
            <?php if (!empty($posts)): ?>
                <?php foreach ($posts as $post): ?>
                    <div>
                        <h3>
                            <?= htmlspecialchars($post->getTitle()) ?>
                            <small><?= htmlspecialchars($post->getWriter()) ?></small>
                        </h3>
                        <p><?= nl2br(htmlspecialchars($post->getPosting())) ?></p>
                        <small><?= htmlspecialchars($post->getCategory()) ?> | <?= $post->getCreatedAt() ?></small>
                    </div>
                    <hr>
                <?php endforeach; ?>
            <?php else: ?>
                <p>게시글 없음</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>