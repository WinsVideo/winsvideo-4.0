<?php
class WatchEmbeds {

    private $con, $video, $userLoggedInObj;

    public function __construct($con, $video, $userLoggedInObj) {
        $this->con = $con;
        $this->video = $video;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create() {
        /* $title = $this->video->getTitle();
        $url = $this->video->getUniqueID();
        $thumbnail = $this->video->getThumbnail();
        $description = $this->video->getDescription();
        $uploadedBy = $this->video->getUploadedByFullName();
        $video = $this->video->getFilePath();
        $privacy = $this->video->getPrivacy(); */

        $title = $this->video->getVideoTitle();
        $description = $this->video->getVideoDescription();
        $url = $this->video->getVideoUrl();
        $thumbnail = $this->video->getThumbnail();
        $uploadedBy = $this->video->getVideoDisplayNameAuthor();
        $video = $this->video->getVideoFilePath();
        $privacy = $this->video->getVideoPrivacy();

        if($privacy == 0) {
            $title = "Gotem";
            $thumbnail = "assets/images/icons/no-video-available.png";
            $uploadedBy = "No Username";
            $video = " ";
        }

        if($title == null){
            $title = "No Title Available / The video might have been deleted.";
        }

        if($thumbnail == null){
            $thumbnail = "assets/images/icons/no-video-available.png";
        }

        if($uploadedBy == null){
            $uploadedBy = "No Username";
        }

        if($description == null) {
            $description = $this->video->getVideoDisplayNameAuthor();;
        }

        return "<title>$title - WinsVideo</title>

        <meta name='description' content='$uploadedBy' />

        <meta property='og:type' content='website' />
        <meta name='theme-color' content='#22b14c' />
        <meta property='og:title' content='$title | $uploadedBy' />
        <meta property='og:description' content='$title | $uploadedBy' />
        <meta property='og:site_name' content='WinsVideo' />

        <!-- Video -->
        <meta property='og:type' content='video.other' />
        <meta property='og:url' content='https://winsvideo.net/watch?v=$url' />
        <meta property='og:image' content='$thumbnail' />
        <meta property='og:image:width' content='1280' />
        <meta property='og:image:height' content='720' />
        <meta property='og:video' content='$video' />
        <meta property='og:video:width' content='1280' />
        <meta property='og:video:height' content='720' />

        <!-- Open Graph / Facebook -->
        <meta property='og:type' content='video.other'>
        <meta property='og:url' content='https://winsvideo.net/watch?v=$url'>
        <meta property='og:title' content='$title - WinsVideo'>
        <meta property='og:description' content='$description'>
        <meta property='og:video' content='$video' />
        <meta property='og:video:width' content='1280' />
        <meta property='og:video:height' content='720' />
        <meta property='og:video:type' content='application/mp4' />

        <!-- Twitter -->
        <meta property='twitter:card' content='player' />
        <meta property='twitter:url' content='https://winsvideo.net/watch?v=$url' />
        <meta property='twitter:title' content='$title - WinsVideo' />
        <meta property='twitter:description' content='$description' />
        <meta property='twitter:player' content='https://winsvideo.net/embed?v=$url' />
        <meta property='twitter:player:width' content='1280' />
        <meta property='twitter:player:height' content='720'/>
        <meta property='twitter:player:stream' content='$video'/>
        <meta property='twitter:player:stream:content_type' content='video/mp4'/>

        <meta name = 'keywords' content = 'WinsVideo, Video Sharing Platform, Video Sharing, Video Platform' />
        <meta name = 'description' content = '$description' />";
    }
}
?>
