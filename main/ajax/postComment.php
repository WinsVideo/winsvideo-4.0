<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../includes/config.php");
require_once("../includes/classes/User.php");
require_once("../includes/classes/Comment.php");

function generateUrl($length = 11) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$usernameLoggedIn = User::isLoggedIn() ? $_SESSION["userLoggedIn"] : "";
$userLoggedInObj = new User($con, $usernameLoggedIn);

if($usernameLoggedIn) {
    if(isset($_POST['commentText']) && isset($_POST['postedBy']) && isset($_POST['videoUrl'])) {
        // check if user exists
        $checkQuery = $con->prepare("SELECT `user_username` FROM users WHERE user_username=:userInput");
        $checkQuery->bindParam(":userInput", $usernameLoggedIn);

        if(!$checkQuery->execute()) {
            echo "Error: " . $query->errorInfo()[2];
        }

        $userFromDb = $checkQuery->fetch(PDO::FETCH_ASSOC)["user_username"] ?? NULL;

        if($userFromDb) {
            $query = $con->prepare("INSERT INTO comments(comment_url, comment_author, comment_videoUrl, comment_responseTo, comment_body) VALUES(:commentUrl, :postedBy, :videoUrl, :responseTo, :body)");
            $query->bindParam(":commentUrl", $commentUrl);
            $query->bindParam(":postedBy", $postedBy);
            $query->bindParam(":videoUrl", $videoUrl);
            $query->bindParam(":responseTo", $responseTo);
            $query->bindParam(":body", $commentText);
            
            $commentUrl = generateUrl();
            $postedBy = $usernameLoggedIn;
            $videoUrl = $_POST['videoUrl'];
            $responseTo = isset($_POST['responseTo']) ? $_POST['responseTo'] : 0;
            $commentText = htmlspecialchars(strip_tags($_POST['commentText']));
            
                
            if(!$query->execute()) {
                echo "Error: " . $query->errorInfo()[2];
            }
            
            $newComment = new Comment($con, $commentUrl, $userLoggedInObj, $videoUrl);
            echo $newComment->create();
            
        } else {
            exit("User not found");
        }    
    } else {
        exit("One or more parameters are not passed into postComment.php the file");
    }
} else {
    exit("Sign in first!");
}
?>