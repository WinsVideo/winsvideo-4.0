<?php
    include("config.php");

    require_once("includes/classes/User.php"); 
    require_once("includes/classes/Video.php"); 
    require_once("includes/classes/VideoGrid.php"); 
    require_once("includes/classes/VideoGridItem.php");
    require_once("includes/classes/WatchEmbeds.php");

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $usernameLoggedIn = User::isLoggedIn() ? $_SESSION["userLoggedIn"] : "";
    $userLoggedInObj = new User($con, $usernameLoggedIn);

    $video = new Video($con, $_GET["v"], $userLoggedInObj);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WinsVideo - A video sharing website for fun!</title>

    <!-- METADATA -->
    <?php
        $videoEmbedsInfo = new WatchEmbeds($con, $video, $userLoggedInObj);
        echo $videoEmbedsInfo->create();
    ?>

    

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>

    <!-- link css file -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- js file -->
    <script src="assets/js/commonActions.js"></script>
    <script src="assets/js/userActions.js"></script>
</head>

<body>
    <header>
        <div align="center">
            <h1>WinsVideo</h1>
            <p style="color: red">THIS WEBSITE IS STILL INDEV. THERE MIGHT (WILL) BE SOME BUGS THAT ARE STILL NOT FIXED. PLEASE REPORT THEM TO <a href="mailto:">WinsDominoesOfficial@protonmail.com</a> </p>
            <!-- horizonal menu bar -->
            <div id="navbar"> 
                <ul> 
                    <li><a href="index.php">Videos</a></li> 
                    <li><a href="channels.php">Channels</a></li> 
                    <li><a href="categories.php">Categories</a></li> 
                    <li><a href="upload.php">Upload</a></li> 
                    <?php
                        if(!$userLoggedInObj->isLoggedIn()) {
                            echo "<li><a href='login.php'>Login</a></li>";
                        } else {
                            echo "<li><a href='channel.php'>Your Channel</a></li>";
                            echo "<li><a href='logout.php'>Logout</a></li>";
                        }
                    ?>
                    <li><a href="changes.php">Updates</a></li> 
                </ul> 
            </div>
        </div>

        <hr>
    </header>