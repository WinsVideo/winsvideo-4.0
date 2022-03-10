<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../includes/config.php"); 
require_once("../includes/classes/Video.php"); 
require_once("../includes/classes/User.php"); 

$username = $_SESSION["userLoggedIn"];
$videoUrl = $_POST["videoUrl"];

$userLoggedInObj = new User($con, $username);
$video = new Video($con, $videoUrl, $userLoggedInObj);

echo $video->like();

?>