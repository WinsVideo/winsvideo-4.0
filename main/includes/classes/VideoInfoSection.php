<?php
    require_once("VideoInfoControls.php");
    class VideoInfoSection {

        private $con, $video, $userLoggedInObj;

        public function __construct($con, $video, $userLoggedInObj) {
            $this->con = $con;
            $this->video = $video;
            $this->userLoggedInObj = $userLoggedInObj;
        }

        public function create() {
            return $this->createPrimaryInfo() . $this->createSecondaryInfo();
        }

        public function createPrimaryInfo() {
            $videoUrl = $this->video->getVideoUrl();
            $videoTitle = $this->video->getVideoTitle();
            $videoViews = number_format($this->video->getVideoViews());
            
            $videoInfoControls = new VideoInfoControls($this->video, $this->userLoggedInObj);
            $controls = $videoInfoControls->create();

            if(!$videoTitle) {
                $videoTitle = "No title available / The video might have been deleted.";
            }

            return "<div class='videoInfo'>
                        <h1>$videoTitle</h1>
                        <div class='bottomSection'>
                            <span class='viewCount'>$videoViews views</span>
                            $controls
                        </div>
                    </div>";
        }

        public function createSecondaryInfo() {
            $loggedInUsername = $this->userLoggedInObj->getUsername();
            $videoDescription = $this->video->getVideoDescription();
            $videoUploadDate = $this->video->getVideoUploadDate();
            $videoAuthor = $this->video->getVideoAuthor();
            $videoAuthorFullname = $this->video->getVideoDisplayNameAuthor();
            $videoTags = $this->video->getVideoTags();
            $videoCategory = $this->video->getVideoCategory();
            $videoUrl = $this->video->getVideoUrl();

            $profileButton = ButtonProvider::createUserProfileButton($this->con, $videoAuthor);

            $descStyle = "";

            if($videoAuthor == $loggedInUsername) {
                $actionButton = ButtonProvider::createEditVideoButton($videoUrl);
            } else {
                $userToObject = new User($this->con, $videoAuthor);
                $actionButton = ButtonProvider::createSubscriberButton($this->con, $userToObject, $this->userLoggedInObj);
            }

            if(!$videoDescription) {
                $descStyle = "display: none;";
            }

            return "<div class='secondaryInfo'>
                        <div class='topRow'>
                            $profileButton

                                <div class='uploadInfo'>
                                    <span>
                                        <a href='channel?username=$videoAuthor'>
                                            $videoAuthorFullname
                                        </a>
                                    </span>
                                    <span class='date'>Published on: $videoUploadDate</span>
                                </div>
                            $actionButton
                        </div>

                        <div class='descriptionContainer'>
                            <span id='description'>$videoDescription</span>
                        </div>

                        <div class='category'>
                            <span>Category: $videoCategory</span>
                        </div>
                    </div>";

        }

    }
?>