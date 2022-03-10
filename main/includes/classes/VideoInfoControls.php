<?php
    require_once("ButtonProvider.php");
    class VideoInfoControls {

        private $video, $userLoggedInObj;

        public function __construct($video, $userLoggedInObj) {
            $this->video = $video;
            $this->userLoggedInObj = $userLoggedInObj;
        }

        public function create() {
            $likeButton = $this->createLikeButton();
            $dislikeButton = $this->createDislikeButton();

            return "<div class='controls'>
                        $likeButton
                        $dislikeButton
                    </div>";
        }

        private function createLikeButton() {
            $text = $this->video->getLikes();
            $videoUrl = $this->video->getVideoUrl();
            $videoId = $this->video->getVideoNumbericalId();
            $action = "likeVideo(this, \"$videoUrl\")";
            $class = "likeButton";

            $imageSrc = "assets/images/icons/thumb-up.png";

            if($this->video->wasLikedBy()) {
                $imageSrc = "assets/images/icons/thumb-up-active.png";
            }
            // return $text;
            return ButtonProvider::createButton($text, $imageSrc, $action, $class);
        }

        private function createDislikeButton() {
            $text = $this->video->getDislikes();
            $videoUrl = $this->video->getVideoUrl();
            $action = "dislikeVideo(this, \"$videoUrl\")";
            $class = "dislikeButton";

            $imageSrc = "assets/images/icons/thumb-down.png";

            if($this->video->wasDislikedBy()) {
                $imageSrc = "assets/images/icons/thumb-down-active.png";
            }

            return ButtonProvider::createButton($text, $imageSrc, $action, $class);
        }

    }
?>