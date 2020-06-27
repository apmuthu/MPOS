<?php
session_start();
function giveSucces($msg=''){
  $_SESSION['msg'] = '<i class="material-icons">check</i>  ' . $msg;
  header("Location:articles.php");
}
include 'inc/header.inc.php';
if (empty($_GET['action'])) {
  $_SESSION['msg'] = "An error has occurred. Please try again!";
  header("Location:articles.php");
}
if (empty($_GET['article_id'])) {
  $_SESSION['msg'] = "An error has occurred. Please try again!";
  header("Location:articles.php");
}
$action = $_GET['action'];
$article_id = $_GET['article_id'];
switch ($action) {
  case 'update_article':
  // TODO: !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    giveSucces("The Item has been successfully edited!");
    break;

  case 'delete_article':
    global $pdo;
    $statement = $pdo->prepare( "DELETE FROM `articles` WHERE `id` = $article_id");
		$result    = $statement->execute();
    giveSucces("The Item was deleted successfully!");
    break;

  case 'update_quantity':
  // TODO: !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    giveSucces("The amount has been added successfully!");
    break;

  default:
    $_SESSION['msg'] = "An error has occurred. Please try again!";
    header("Location:articles.php");
    break;
  }
 ?>
