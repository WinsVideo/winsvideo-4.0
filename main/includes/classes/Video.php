<?php
    class Video {

        private $con, $sqlData, $userLoggedInObj;

        public function __construct($con, $input, $userLoggedInObj) {
            $this->con = $con;
            $this->userLoggedInObj = $userLoggedInObj;
    
            if(is_array($input)) {
                $this->sqlData = $input;
            }
            else {
                $query = $this->con->prepare("SELECT * FROM videos WHERE video_url = :url");
                $query->bindParam(":url", $input);
                $query->execute();
    
                $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
            }
        }

        public function getVideoNumbericalId() {
            return $this->sqlData["video_id"];
        }

        public function getVideoUrl() {
            return $this->sqlData["video_url"];
        }

        public function getVideoTitle() {
            return htmlspecialchars(strip_tags($this->sqlData["video_title"]));
        }

        public function getVideoDescription() {
            return htmlspecialchars(strip_tags($this->sqlData["video_description"]));
        }

        public function getVideoTags() {
            return htmlspecialchars(strip_tags($this->sqlData["video_tags"]));
        }

        public function getVideoAuthor() {
            return htmlspecialchars(strip_tags($this->sqlData["video_author"]));
        }

        public function getVideoDisplayNameAuthor() {
            $authorUsername = $this->sqlData["video_author"];
            $query = $this->con->prepare("SELECT * FROM users WHERE user_username='$authorUsername'");    
            $query->execute();

            while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $name = htmlspecialchars(strip_tags($row["user_displayname"]));
                $html = "$name";
            }
            
            $html .= "";

            return $html;
        }

        public function getVideoPrivacy() {
            return $this->sqlData["video_privacy"];
        }

        public function getVideoFilePath() {
            return $this->sqlData["video_filePath"];
        }

        public function getVideoCategory() {
            $categoryID = $this->sqlData["video_category"];
            $query = $this->con->prepare("SELECT * FROM categories WHERE category_id='$categoryID'");    
            $query->execute();
    
            while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $categoryName = htmlspecialchars(strip_tags($row["category_name"]));
    
                $html = $categoryName;
            }
            
            $html .= "";
    
            return $html;
        }

        public function getVideoUploadDate() {
            $date = $this->sqlData["video_uploadDate"];
            $date = date("M j, Y", strtotime($date));

            return $date;
        }

        public function getVideoTimeStamp() {
            $date = $this->sqlData["video_uploadDate"];
            $date = date("M jS, Y", strtotime($date));

            return $date;
        }

        public function getVideoViews() {
            return $this->sqlData["video_views"];
        }

        public function getVideoDuration() {
            return $this->sqlData["video_duration"];
        }

        /* VIEWS */
        public function incrementViews() {
            $videoUrl = $this->getVideoUrl();
            $usernameLoggedIn = $this->userLoggedInObj->getUsername();

            // check if user has viewed the video
            $query = $this->con->prepare("SELECT * FROM viewing WHERE videoUrl=:videoUrl AND userViewed=:userViewed");
            $query->bindParam(":videoUrl", $videoUrl);
            $query->bindParam(":userViewed", $usernameLoggedIn);
            $query->execute();

            // if user has not viewed the video TODAY, increment views and add to viewing table
            if($query->rowCount() == 0) {
                // add to viewing table
                $query = $this->con->prepare("INSERT INTO viewing(videoUrl, userViewed) VALUES(:videoUrl, :userViewed)");
                $query->bindParam(":videoUrl", $videoUrl);
                $query->bindParam(":userViewed", $usernameLoggedIn);
                $query->execute();

                // increment views
                $query = $this->con->prepare("UPDATE videos SET video_views=video_views+1 WHERE video_url=:videoUrl");
                $query->bindParam(":videoUrl", $videoUrl);
                $query->execute();

                $this->sqlData["video_views"] = $this->sqlData["video_views"] + 1;

            // if the user has viewed the video more than 3 times, do not increment the views
            } else if($query->rowCount() > 3) {
                while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $userViewed = $row["userViewed"];
                    $videoUrl = $row["videoUrl"];
                    $dateWatched = $row["dateWatched"];

                    // check if $dateWatched is today
                    $dateWatched = date("Y-m-d", strtotime($dateWatched));
                    $today = date("Y-m-d");
                    
                    // if $dateWatched is today, do not increment the views
                    if($dateWatched == $today) {
                        // do nothing
                        
                    // else delete the row from viewing table and increment the views
                    } else {
                        // delete viewing row
                        $query = $this->con->prepare("DELETE FROM viewing WHERE videoUrl=:videoUrl AND userViewed=:userViewed");
                        $query->bindParam(":videoUrl", $videoUrl);
                        $query->bindParam(":userViewed", $userViewed);
                        $query->execute();
                    }
                }
            } else {
                $query = $this->con->prepare("INSERT INTO viewing(videoUrl, userViewed) VALUES(:videoUrl, :userViewed)");
                $query->bindParam(":videoUrl", $videoUrl);
                $query->bindParam(":userViewed", $usernameLoggedIn);
                $query->execute();
            }
        }

        /* GET THUMBNAIL */
        public function getThumbnail() {
            $query = $this->con->prepare("SELECT thumbnail_filePath FROM thumbnails WHERE thumbnail_videoUrl=:videoUrl AND thumbnail_selected=1");
            $query->bindParam(":videoUrl", $videoUrl);
            $videoUrl = $this->getVideoUrl();

            $query->execute();

            $data = $query->fetchColumn();
            return $data;
        }

        /* LIKES / DISLIKES STATS */
        public function getLikes() {
            $query = $this->con->prepare("SELECT count(*) as 'count' FROM likes WHERE like_videoUrl=:videoUrl");
            $query->bindParam(":videoUrl", $videoUrl);
            $videoUrl = $this->getVideoUrl();
            $query->execute();

            $data = $query->fetch(PDO::FETCH_ASSOC);
            return $data["count"];
        }

        public function getDislikes() {
            $query = $this->con->prepare("SELECT count(*) as 'count' FROM dislikes WHERE dislike_videoUrl=:videoUrl");
            $query->bindParam(":videoUrl", $videoUrl);
            $videoUrl = $this->getVideoUrl();
            $query->execute();

            $data = $query->fetch(PDO::FETCH_ASSOC);
            return $data["count"];
        }

        /* LIKES / DISLIKES ACTION */
        public function like() {
            $videoUrl = $this->getVideoUrl();
            $username = $this->userLoggedInObj->getUsername();

            if($this->wasLikedBy($username)) {
                $query = $this->con->prepare("DELETE FROM likes WHERE like_videoUrl=:videoUrl AND like_username=:username");
                $query->bindParam(":videoUrl", $videoUrl);
                $query->bindParam(":username", $username);
                $query->execute();

                $result = array(
                    "likes" => -1,
                    "dislikes" => 0
                );
                return json_encode($result);
            } else {
                $query = $this->con->prepare("DELETE FROM dislikes WHERE dislike_username=:username AND dislike_videoUrl=:videoUrl");
                $query->bindParam(":username", $username);
                $query->bindParam(":videoUrl", $videoUrl);
                $query->execute();
                $count = $query->rowCount();

                $query = $this->con->prepare("INSERT INTO likes(like_videoUrl, like_username) VALUES(:videoUrl, :username)");
                $query->bindParam(":videoUrl", $videoUrl);
                $query->bindParam(":username", $username);
                $query->execute();

                $result = array(
                    "likes" => 1,
                    "dislikes" => 0 - $count
                );
                return json_encode($result);
            }
        }

        public function dislike() {
            $videoUrl = $this->getVideoUrl();
            $username = $this->userLoggedInObj->getUsername();

            if($this->wasDislikedBy()) {
                // user has already liked this video
                $query = $this->con->prepare("DELETE FROM dislikes WHERE dislike_username=:username AND dislike_videoUrl=:videoUrl");
                $query->bindParam(":username", $username);
                $query->bindParam(":videoUrl", $videoUrl);
                $query->execute();

                $result = array(
                    "likes" => 0,
                    "dislikes" => -1
                );
                return json_encode($result);
            } else {
                $query = $this->con->prepare("DELETE FROM likes WHERE like_username=:username AND like_videoUrl=:videoUrl");
                $query->bindParam(":username", $username);
                $query->bindParam(":videoUrl", $videoUrl);
                $query->execute();
                $count = $query->rowCount();

                $query = $this->con->prepare("INSERT INTO dislikes(dislike_videoUrl, dislike_username) VALUES(:videoUrl, :username)");
                $query->bindParam(":videoUrl", $videoUrl);
                $query->bindParam(":username", $username);
                $query->execute();

                $result = array(
                    "likes" => 0 - $count,
                    "dislikes" => 1
                );
                return json_encode($result);
            }
        }

        /* LIKES / DISLIKES CHECKING */
        public function wasLikedBy() {
            $query = $this->con->prepare("SELECT * FROM likes WHERE like_username=:username AND like_videoUrl=:videoUrl");
            $query->bindParam(":username", $username);
            $query->bindParam(":videoUrl", $videoUrl);

            $username = $this->userLoggedInObj->getUsername();
            $videoUrl = $this->getVideoUrl();

            $query->execute();
            return $query->rowCount() > 0;
        }

        public function wasDislikedBy() {
            $query = $this->con->prepare("SELECT * FROM dislikes WHERE dislike_username=:username AND dislike_videoUrl=:videoUrl");
            $query->bindParam(":username", $username);
            $query->bindParam(":videoUrl", $videoUrl);

            $username = $this->userLoggedInObj->getUsername();
            $videoUrl = $this->getVideoUrl();

            $query->execute();
            return $query->rowCount();
        }

        /* COMMENTS */

        public function getNumberOfComments() {
            $query = $this->con->prepare("SELECT * FROM comments WHERE comment_videoUrl=:videoUrl");
            $query->bindParam(":videoUrl", $videoUrl);
    
            $videoUrl = $this->getVideoUrl();
    
            $query->execute();
    
            return $query->rowCount();
        }

        public function getComments() {
            $query = $this->con->prepare("SELECT * FROM comments WHERE comment_videoUrl=:videoUrl AND comment_responseTo='0' ORDER BY comment_datePosted DESC");
            $query->bindParam(":videoUrl", $videoUrl);
    
            $videoUrl = $this->getVideoUrl();
    
            $query->execute();
    
            $comments = array();
    
            while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $comment = new Comment($this->con, $row, $this->userLoggedInObj, $videoUrl);
                array_push($comments, $comment);
            }
    
            return $comments;
        }
    

    }
?>