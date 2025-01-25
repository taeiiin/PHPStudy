<?php
require_once __DIR__ . '/vendor/autoload.php';
use Test\PostMgr;

if(isset($_GET['id'])) {
  $id = htmlspecialchars($_GET['id']);
  $postMgr = new PostMgr();
  $postMgr->deletePost($id);

  header("Location: board.php");
  exit;
}