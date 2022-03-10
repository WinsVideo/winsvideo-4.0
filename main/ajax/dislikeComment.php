<?php
require_once("../includes/config.php"); 
require_once("../includes/classes/Comment.php"); 
require_once("../includes/classes/User.php"); 

$username = $_SESSION["userLoggedIn"];
$videoUrl = $_POST["videoUrl"];
$commentUrl = $_POST["commentUrl"];

$userLoggedInObj = new User($con, $username);
$comment = new Comment($con, $commentUrl, $userLoggedInObj, $videoUrl);

echo $comment->dislike();
?>