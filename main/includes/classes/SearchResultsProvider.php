<?php
    class SearchResultsProvider {

        private $con, $userLoggedInObj;

        public function __construct($con, $userLoggedInObj) {
            $this->con = $con;
            $this->userLoggedInObj = $userLoggedInObj;
        }

        public function getVideos($term, $orderBy) {
            $query = $this->con->prepare("SELECT * FROM `videos` WHERE `video_privacy` = '1' AND(`video_title` LIKE CONCAT('%', :term, '%') OR `video_author` LIKE CONCAT('%', :term, '%') OR `video_tags` LIKE CONCAT('%', :term, '%')) ORDER BY $orderBy DESC");

            $term = htmlspecialchars(strip_tags($term));
            $query->bindParam(":term", $term);
            $query->execute();

            $videos = array();
            while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $video = new Video($this->con, $row, $this->userLoggedInObj);
                array_push($videos, $video);
            }

            return $videos;
        }

    }
?>