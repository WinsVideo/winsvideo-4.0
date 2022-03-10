<?php

    $videoUniqUrl = $_GET["v"];

    require_once("includes/watchHeader.php");
    require_once("includes/classes/VideoPlayer.php");
    require_once("includes/classes/VideoInfoSection.php");
    require_once("includes/classes/Comment.php"); 
    require_once("includes/classes/CommentSection.php"); 

    if(!isset($_GET["v"])) {
        echo "No url passed into page";
        exit();
    }

    // echo $usernameLoggedIn;

    if(($video->getVideoAuthor() != $usernameLoggedIn) && ($video->getVideoPrivacy() == 0)) {
        echo "<div class='column'>
                    <div class='alert alert-danger' role='alert'>
                        Video Unavailable.
                    </div>
                    <span>Go back to <a href='index.php'>homepage</a></span>
              </div>";
        header("Location: https://winsvideo.net");
        exit(); 
    } else if (($video->getVideoAuthor() == $usernameLoggedIn) && ($video->getVideoPrivacy() == 0)) {
        // do nothing
    }
?>

<script src="assets/js/videoPlayerActions.js"></script>
<script src="assets/js/commentActions.js"></script>

<div class="container">      
        <div class="videoPlayerContainer">
            <?php
		        if($usernameLoggedIn) {
                    $video->incrementViews();
                } else;

                $videoPlayer = new VideoPlayer($video);
                echo $videoPlayer->create(true);
            ?>
        </div>

        <div class="videoInfoContainer">
            <?php
                $videoInfo = new VideoInfoSection($con, $video, $userLoggedInObj);
                echo $videoInfo->create();    
            ?>
        </div>

        <div class="videoCommentContainer">
            <?php
                $commentSection = new CommentSection($con, $video, $userLoggedInObj);
                echo $commentSection->create();
            ?>
        </div>
</div>