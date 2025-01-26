<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

use Test\GuestMgr;
use Test\Guestbook;

$guestMgr = new GuestMgr();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $msg = nl2br(htmlspecialchars($_POST['msg']));
    
    $guest = new Guestbook([
        'name' => $name,
        'msg' => $msg,
    ]);

    $guestMgr->saveGuests($guest);

    header("Location: guestb.php");
    exit;
}

$entries = $guestMgr->loadGuests();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title></title>
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
            width: 40%;
            margin: 0 auto;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .container {
            justify-content: center;
            padding: 0 10%;
            margin-left: 10px;
        }
        .input-grp {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            width: 100%;
        }
        .input-grp label {
            width: 10%;
            font-weight: bold;
            text-align: right;
            margin-right: 15px;
        }
        .input-grp input,
        .input-grp textarea {
            width: 85%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            height: 40px;
            margin-top: 10px;
        }
        button {
            padding: 8px 16px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        button:hover {
            background-color: #555;
        }
        p {
            margin: 10px auto;
            width: 78%;
            border: none;
        }
    </style>
</head>
<body>
    <h1>Guest Book</h1>
    <a href="index.php">Main</a>
    <hr>
    <form method="POST">
        <div class="input-grp">
            <label for="name">이름</label>
            <input type="text" name="name" id="name" required>
        </div>
        <div class="input-grp">
            <label for="msg">메시지</label>
            <textarea name="msg" id="msg" rows="5" required></textarea>
        </div>
        <button type="submit">등록</button>
    </form>
    <hr>
    <?php if (!empty($entries)): ?>
        <?php foreach ($entries as $entry): ?>
            <div class="container">
                <strong><?= htmlspecialchars($entry->getName()) ?></strong> :
                <?= nl2br(htmlspecialchars($entry->getMsg())) ?><br>
                <small><?= $entry->getCreatedAt() ?></small>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php else: ?>
        <p>방명록 없음</p>
    <?php endif; ?>
</body>
</html>