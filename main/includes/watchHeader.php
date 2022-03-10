<?php
    include("config.php");

    require_once("includes/classes/User.php"); 
    require_once("includes/classes/Video.php"); 
    require_once("includes/classes/VideoGrid.php"); 
    require_once("includes/classes/VideoGridItem.php");
    require_once("includes/classes/NavigationMenuProvider.php");
    require_once("includes/classes/ButtonProvider.php");
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
    <!-- METADATA -->
    <?php
        $videoEmbedsInfo = new WatchEmbeds($con, $video, $userLoggedInObj);
        echo $videoEmbedsInfo->create();
    ?>

    <!-- google font roboto -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans&family=Roboto&display=swap" rel="stylesheet"> 

    <!-- link css file -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <link href="assets/css/materia/bootstrap.min.css" rel="stylesheet">
    
    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- js file -->
    <script src="assets/js/commonActions.js"></script>
    <script src="assets/js/userActions.js"></script>

    <!-- material icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link href="https://vjs.zencdn.net/7.17.0/video-js.css" rel="stylesheet" />
    <script src="https://vjs.zencdn.net/7.17.0/video.min.js"></script>

    <script src="assets/js/videojs.persistvolume.js"></script>

    <link href="https://unpkg.com/@silvermine/videojs-quality-selector/dist/css/quality-selector.css" rel="stylesheet">
    <script src="https://unpkg.com/@silvermine/videojs-quality-selector/dist/js/silvermine-videojs-quality-selector.min.js"></script>
</head>

<style>
    html, body {
        font-family: 'Roboto', sans-serif;
    }
</style>

<body>
<div id="pageContainer">
    <div id="mastHeadContainer">
        <button class="navShowHide">
            <!-- <img src="assets/images/icons/menu.png" style="height: 15px; width: 15px;"> -->
            <span class="material-icons" style="color: #888888">
                menu
            </span>
        </button>

        <a class="logoContainer" href="https://winsvideo.net">
            <img src="assets/images/icons/logo.png" title="logo" alt="WinsVideo Logo">
        </a>

        <div class="searchBarContainer">
            
            <form action="search" method="GET">
                <input type="text" class="searchBar" name="term" placeholder="Search">
                <button class="searchButton">
                    <img src="assets/images/icons/search.png">
                </button>
                
            </form>
        </div>

        <div class="rightIcons">
            <a href="upload">
                <img class="upload" src="assets/images/icons/upload.png">                  
            </a>

            <?php // echo ButtonProvider::createDashboardButton($con, $userLoggedInObj->getUsername()); ?>
            <?php echo ButtonProvider::createUserProfileNavigationButton($con, $userLoggedInObj->getUsername()); ?>


        </div>
    </div>

<div id="sideNavContainer" style="">
    <?php
    $navigationProvider = new NavigationMenuProvider($con, $userLoggedInObj);
    echo $navigationProvider->create(); 
    ?>
</div>

<div id="mainSectionContainer" class="leftPadding">
    <div id="mainContentContainer">