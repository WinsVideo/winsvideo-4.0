<?php
    require_once("ButtonProvider.php");
    require_once("CommentControls.php");

    class Comment {

        private $con, $sqlData, $userLoggedInObj, $videoUrl;

        public function __construct($con, $input, $userLoggedInObj, $videoUrl) {

            if(!is_array($input)) {
                $query = $con->prepare("SELECT * FROM comments WHERE comment_url=:comment_url");
                $query->bindParam(":comment_url", $input);
                $query->execute();

                $input = $query->fetch(PDO::FETCH_ASSOC);
            }

            $this->sqlData = $input;
            $this->con = $con;
            $this->userLoggedInObj = $userLoggedInObj;
            $this->videoUrl = $videoUrl;

        }

        public function create() {
            $url = $this->sqlData["comment_url"];
            $videoUrl = $this->getVideoUrl();
            $commentBody = htmlspecialchars(strip_tags($this->sqlData["comment_body"]));
            $commentAuthor = $this->sqlData["comment_author"];
            $profileButton = ButtonProvider::createUserProfileButton($this->con, $commentAuthor);
            $timespan = $this->time_elapsed_string($this->sqlData["comment_datePosted"]);
            $deleteButton = "";

            $commentControlsObj = new CommentControls($this->con, $this, $this->userLoggedInObj);
            $commentControls = $commentControlsObj->create();

            $numResponses = $this->getNumberOfReplies();

            if($numResponses > 0) {
                $viewRepliesText = "<span class='repliesSection viewReplies' onclick='getReplies(\"$url\", this, \"$videoUrl\")'>
                                        View all $numResponses replies</span>";
            }
            else {
                $viewRepliesText = "<div class='repliesSection'></div>";
            }

            if($this->userLoggedInObj->getUsername() == $commentAuthor) {
                $deleteButton = "<input type='submit' class='btn btn-danger' name='deleteButtonButton' value='Delete'></input>";
            }

            return "<div class='itemContainer'>
                        <div class='comment'>
                            $profileButton

                            <div class='mainContainer'>
                                <div class='commentHeader'>
                                    <a href='channel?username=$commentAuthor'>
                                        <span class='username'>$commentAuthor</span>
                                    </a>
                                    <span class='timestamp'>$timespan</span>
                                </div>

                                <div class='body'>
                                    $commentBody
                                </div>
                            </div>
                        </div>
                        $commentControls
                        $viewRepliesText
                    </div>";

        }

        public function createReply() {
            $url = $this->sqlData["comment_url"];
            $videoUrl = $this->getVideoUrl();
            $commentBody = htmlspecialchars(strip_tags($this->sqlData["comment_body"]));
            $commentAuthor = $this->sqlData["comment_author"];
            $profileButton = ButtonProvider::createUserProfileButton($this->con, $commentAuthor);
            $timespan = $this->time_elapsed_string($this->sqlData["comment_datePosted"]);
            $deleteButton = "";

            $commentControlsObj = new CommentControls($this->con, $this, $this->userLoggedInObj);
            $commentControls = $commentControlsObj->create();

            $numResponses = $this->getNumberOfReplies();

            if($numResponses > 0) {
                $viewRepliesText = "<span class='repliesSection viewReplies' onclick='getReplies(\"$url\", this, \"$videoUrl\")'>
                                        View all $numResponses replies</span>";
            }
            else {
                $viewRepliesText = "<div class='repliesSection' style='padding-left: 0px;'></div>";
            }

            if($this->userLoggedInObj->getUsername() == $commentAuthor) {
                $deleteButton = "<input type='submit' class='btn btn-danger' name='deleteButtonButton' value='Delete'></input>";
            }

            return "<div class='itemContainer'>
                        <div class='comment'>
                            $profileButton

                            <div class='mainContainer'>
                                <div class='commentHeader'>
                                    <a href='channel?username=$commentAuthor'>
                                        <span class='username'>$commentAuthor</span>
                                    </a>
                                    <span class='timestamp'>$timespan</span>
                                </div>

                                <div class='body'>
                                    $commentBody
                                </div>
                            </div>
                        </div>
                        $commentControls
                        $viewRepliesText
                    </div>";

        }

        public function getNumberOfReplies() {
            $query = $this->con->prepare("SELECT count(*) FROM comments WHERE comment_responseTo=:comment_responseTo");
            $query->bindParam(":comment_responseTo", $commentUrl);
            $commentUrl = $this->sqlData["comment_url"];
            $query->execute();

            return $query->fetchColumn();
        }

        function time_elapsed_string($datetime, $full = false) {
            $now = new DateTime;
            $ago = new DateTime($datetime);
            $diff = $now->diff($ago);
        
            $diff->w = floor($diff->d / 7);
            $diff->d -= $diff->w * 7;
        
            $string = array(
                'y' => 'year',
                'm' => 'month',
                'w' => 'week',
                'd' => 'day',
                'h' => 'hour',
                'i' => 'minute',
                's' => 'second',
            );
            foreach ($string as $k => &$v) {
                if ($diff->$k) {
                    $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
                } else {
                    unset($string[$k]);
                }
            }
        
            if (!$full) $string = array_slice($string, 0, 1);
            return $string ? implode(', ', $string) . ' ago' : 'just now';
        }

        public function getCommentUrl() {
            return $this->sqlData["comment_url"];
        }

        public function getVideoUrl() {
            return $this->videoUrl;
        }

        /* GET LIKES AND DISLIKES */

        public function getLikes() {
            $query = $this->con->prepare("SELECT count(*) as 'count' FROM likes WHERE like_commentUrl=:commentUrl");
            $query->bindParam(":commentUrl", $commentUrl);
            $commentUrl = $this->getCommentUrl();
            $query->execute();

            $data = $query->fetch(PDO::FETCH_ASSOC);
            $numLikes = $data["count"];

            $query = $this->con->prepare("SELECT count(*) as 'count' FROM dislikes WHERE dislike_commentUrl=:commentUrl");
            $query->bindParam(":commentUrl", $commentUrl);
            $query->execute();

            $data = $query->fetch(PDO::FETCH_ASSOC);
            $numDislikes = $data["count"];

            return $numLikes - $numDislikes;
        }

        /* LIKE AND DISLIKE FUNCTION FOR COMMENT SECTION */

        public function like() {
            $commentUrl = $this->getCommentUrl();
            $username = $this->userLoggedInObj->getUsername();

            if($this->wasLikedBy()) {
                // User has already liked
                $query = $this->con->prepare("DELETE FROM likes WHERE like_commentUrl=:commentUrl AND like_username=:username");
                $query->bindParam(":commentUrl", $commentUrl);
                $query->bindParam(":username", $username);
                $query->execute();

                return -1;
            } else {
                $query = $this->con->prepare("DELETE FROM dislikes WHERE dislike_commentUrl=:commentUrl AND dislike_username=:username");
                $query->bindParam(":commentUrl", $commentUrl);
                $query->bindParam(":username", $username);
                $query->execute();
                $count = $query->rowCount();

                $query = $this->con->prepare("INSERT INTO likes(like_commentUrl, like_username) VALUES(:commentUrl, :username)");
                $query->bindParam(":commentUrl", $commentUrl);
                $query->bindParam(":username", $username);
                $query->execute();

                return 1 + $count;
            }
        }

        public function dislike() {
            $commentUrl = $this->getCommentUrl();
            $username = $this->userLoggedInObj->getUsername();

            if($this->wasDislikedBy()) {
                // User has already disliked
                $query = $this->con->prepare("DELETE FROM dislikes WHERE dislike_commentUrl=:commentUrl AND dislike_username=:username");
                $query->bindParam(":commentUrl", $commentUrl);
                $query->bindParam(":username", $username);
                $query->execute();

                return 1;
            } else {
                $query = $this->con->prepare("DELETE FROM likes WHERE like_commentUrl=:commentUrl AND like_username=:username");
                $query->bindParam(":commentUrl", $commentUrl);
                $query->bindParam(":username", $username);
                $query->execute();
                $count = $query->rowCount();

                $query = $this->con->prepare("INSERT INTO dislikes(dislike_commentUrl, dislike_username) VALUES(:commentUrl, :username)");
                $query->bindParam(":commentUrl", $commentUrl);
                $query->bindParam(":username", $username);
                $query->execute();

                return -1 - $count;
            }
        }

        /* LIKES AND DISLIKES CHECKING*/

        public function wasLikedBy() {
            $query = $this->con->prepare("SELECT * FROM likes WHERE like_username=:username AND like_commentUrl=:commentUrl");
            $query->bindParam(":username", $username);
            $query->bindParam(":commentUrl", $commentUrl);

            $username = $this->userLoggedInObj->getUsername();
            $commentUrl = $this->getCommentUrl();

            $query->execute();

            return $query->rowCount() > 0;

        }

        public function wasDislikedBy() {
            $query = $this->con->prepare("SELECT * FROM dislikes WHERE dislike_username=:username AND dislike_commentUrl=:commentUrl");
            $query->bindParam(":username", $username);
            $query->bindParam(":commentUrl", $commentUrl);

            $username = $this->userLoggedInObj->getUsername();
            $commentUrl = $this->getCommentUrl();
            
            $query->execute();
            return $query->rowCount() > 0;
        }

        /* REPLIES */
        public function getReplies() {
            $query = $this->con->prepare("SELECT * FROM comments WHERE comment_responseTo=:commentUrl ORDER BY comment_datePosted ASC");
            $query->bindParam(":commentUrl", $commentUrl);

            $commentUrl = $this->getCommentUrl();

            $query->execute();

            $comments = "";
            $videoUrl = $this->getVideoUrl();
            while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $comment = new Comment($this->con, $row, $this->userLoggedInObj, $videoUrl);
                $comments .= $comment->createReply();
            }

            return $comments;
        }

    }
?>