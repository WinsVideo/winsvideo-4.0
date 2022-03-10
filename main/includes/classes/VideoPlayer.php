<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once("includes/classes/Video.php");

    class VideoPlayer {
        private $video;

        public function __construct($video) {
            $this->video = $video;
        }

        public function create($autoPlay) {
            if($autoPlay) {
                $autoPlay = "autoplay";
            } else {
                $autoPlay = "";
            }

            $filePath = $this->video->getVideoFilePath();
            $thumbnail = $this->video->getThumbnail();

            return "<div class='videoPlayer'>
                        <video autoplay id='video' tabindex='-1' class='video-js vjs-big-play-centered vjs-default-skin videoPlayer' poster='$thumbnail' controls controlslist='nodownload' style='flex: 1; object-fit: cover;' preload='auto' data-setup='{}'>
                            <source src='$filePath' type='video/mp4'>
                        </video>
                    </div>
                    
                    <script>
                        videojs('video', {
                            playbackRates: [0.25, 0.5, 0.75, 1, 1.25, 1.5, 1.75, 2],
                            fluid: true
                        });
                        (function() {
                            var vid1 = videojs('video');
                            
                            vid1.persistvolume({
                                namespace: 'video'
                            });
                        })(); 
                    </script>";

        }
    }
?>