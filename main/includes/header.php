<?php
    include("config.php");

    require_once("includes/classes/User.php"); 
    require_once("includes/classes/Video.php"); 
    require_once("includes/classes/VideoGrid.php"); 
    require_once("includes/classes/VideoGridItem.php");
    require_once("includes/classes/NavigationMenuProvider.php");
    require_once("includes/classes/ButtonProvider.php");

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $usernameLoggedIn = User::isLoggedIn() ? $_SESSION["userLoggedIn"] : "";
    $userLoggedInObj = new User($con, $usernameLoggedIn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WinsVideo - A video sharing website for fun!</title>

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
</head>

<style>
    html, body {
        font-family: 'Roboto', sans-serif;
    }
    
    @media (max-width : 320px) {
        #mobileSearch {
            display: block;
        }

        #searchForm {
            display: none;
        }
    }

    @import url('https://fonts.googleapis.com/css?family=Red+Hat+Display:900&display=swap');

    .black-lives-matter {
        font-size: 5vw;
        line-height: 5vw;
        margin: 0;
        font-family: 'Red Hat Display', sans-serif;
        font-weight: 900;
        -webkit-background-clip: text;
        /* color: rgba(0,0,0,0.08); */
        color: black;
        animation: zoomout 10s ease 500ms forwards;
    }
</style>

<?php

    // stuff
    /* background: url(https://raw.githubusercontent.com/s1mpson/-/master/codepen/black-lives-matter/victim-collage.png);
        background-size: 40%;
        background-position: 50% 50%; 
        
        @keyframes zoomout {
        from {
            background-size: 40%;
        }
        to {
            background-size: 10%;
        }
    }
        
        */

?>

<body>
<div id="pageContainer">
    <div id="mastHeadContainer">
        <button class="navShowHide">
            <span class="material-icons" style="color: #888888">
                menu
            </span>
        </button>

        <a class="logoContainer" href="https://winsvideo.net">
            <img src="assets/images/icons/logo.png" title="logo" alt="WinsVideo Logo">
        </a>

        <div class="searchBarContainer">
            <form action="search" method="GET" id="searchForm">
                <input type="text" class="searchBar" name="term" placeholder="Search">
                <button class="searchButton">
                    <img src="assets/images/icons/search.png">
                </button>
            </form>
        </div>

        <div class="rightIcons">
            <a href="search">
                <img id="mobileSearch" class="mobileSearch" src="assets/images/icons/search.png">
            </a>

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