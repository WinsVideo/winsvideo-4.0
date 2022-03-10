<?php
    class VideoGridItem {

        private $video, $largeMode;

        public function __construct($video, $largeMode) {
            $this->video = $video;
            $this->largeMode = $largeMode;
        }

        // main function 
        public function create() {
            $thumbnail = $this->createThumbnail();
            $details = $this->createDetails();
            $url = "watch?v=" . $this->video->getVideoUrl();

            return "<a href='$url'>
                        <div class='videoGridItem'>
                            $thumbnail
                            $details
                        </div>
                    </a>";
        }

        /* DETAILS */
        private function createDetails() {
            $title = $this->video->getVideoTitle();
            $views = $this->video->getVideoViews();
            $author = $this->video->getVideoAuthor();
            $authorDisplayName = $this->video->getVideoDisplayNameAuthor();
            $description = $this->video->getVideoDescription();
            $timestamp = $this->video->getVideoTimeStamp();

            $details = "<div class='details'>
                            <h3 class='title'>$title</h3>
                            <a href='channel?username=$author' class='username'>$authorDisplayName</a>
                            <div class='stats'>
                                <span class='viewCount'>$views views -</span>
                                <span class='timestamp'>$timestamp</span>
                            </div>
                        </div>";

            return $details;
        }

        /* THUMBNAIL */
        private function createThumbnail() {
            $thumbnail = $this->video->getThumbnail();
            $duration = $this->video->getVideoDuration();

            if($thumbnail == null) {
                $thumbnail = "assets/images/no_thumbnail.jpg";
            }

            return "<div class='thumbnail'>
                        <img src='$thumbnail'>
                        <div class='duration'>
                            <span>$duration</span>
                        </div>
                    </div>";
        }

        private function createThumbnailSuggestions() {
            $thumbnail = $this->video->getThumbnail();
            $duration = $this->video->getVideoDuration();

            return "<div class='suggestionThumbnail'>
                        <img width: 210px; height: 118px; src='$thumbnail'>
                        <div class='duration'>
                            <span>$duration</span>
                        </div>
                    </div>";
        }
    }
?>