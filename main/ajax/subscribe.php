<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../includes/config.php");
require_once("../includes/classes/User.php");

$usernameLoggedIn = User::isLoggedIn() ? $_SESSION["userLoggedIn"] : "";
$userLoggedInObj = new User($con, $usernameLoggedIn);

function checkIfUserExists($con, $input) {
    $checkQuery = $con->prepare("SELECT `user_username` FROM users WHERE user_username=:userInput");
    $checkQuery->bindParam(":userInput", $input);

    if(!$checkQuery->execute()) {
        return "Error: " . $query->errorInfo()[2];
    }

    $checkDb = $checkQuery->fetch(PDO::FETCH_ASSOC)["user_username"] ?? NULL;
    if($checkDb) {
        return [true, $checkDb];
    } else {
        return [false];
    }
}


if($usernameLoggedIn) {
    if(isset($_POST['userTo'])) {
        $userCheckArray = checkIfUserExists($con, $_POST['userTo']);
        if($userCheckArray[0]) {
            
            $userTo = $userCheckArray[1];
            $userFrom = $usernameLoggedIn;
            
            if($userTo != $userFrom) {
                
                // check if the user is subbed
                $query = $con->prepare("SELECT * FROM subscribers WHERE userTo=:userTo AND userFrom=:userFrom");
                $query->bindParam(":userTo", $userTo);
                $query->bindParam(":userFrom", $userFrom);
                $query->execute();
            
                if($query->rowCount() == 0) {
                    // Insert
                    $query = $con->prepare("INSERT INTO subscribers(userTo, userFrom) VALUES(:userTo, :userFrom)");
                    $query->bindParam(":userTo", $userTo);
                    $query->bindParam(":userFrom", $userFrom);
                    $query->execute();
                }
                else {
                    // Delete
                    $query = $con->prepare("DELETE FROM subscribers WHERE userTo=:userTo AND userFrom=:userFrom");
                    $query->bindParam(":userTo", $userTo);
                    $query->bindParam(":userFrom", $userFrom);
                    $query->execute();
                }
            
                $query = $con->prepare("SELECT * FROM subscribers WHERE userTo=:userTo");
                $query->bindParam(":userTo", $userTo);
                $query->execute();
            
                echo $query->rowCount(); 
            } else {
                exit("You can't subscribe to yourself!");
            }
        } else {
            exit("The user who you are subscribing to does not exist!");
        }
    } else {
        exit("One or more parameters are not passed into subscribe.php the file");
    }
} else {
    exit("Sign in first!");
}
?>