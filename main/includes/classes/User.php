<?php
    class User {
        
        private $con, $sqlData;

        public function __construct($con, $username) {
            $this->con = $con;

            $query = $this->con->prepare("SELECT * FROM users WHERE user_username = :un");
            $query->bindParam(":un", $username);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }

        /* CORE */
        public static function isLoggedIn() {
            return isset($_SESSION["userLoggedIn"]);
        }
        
        public function loggedInUsername() {
            return $_SESSION["userLoggedIn"];
        }

        public function getUsername() {
            return $this->sqlData["user_username"] ?? null;
        }

        public function getDisplayName() {
            return htmlspecialchars(strip_tags($this->sqlData["user_displayname"]));
        }

        public function getEmail() {
            return htmlspecialchars(strip_tags($this->sqlData["user_email"]));
        }

        public function getSignUpDate() {
            return $this->sqlData["user_signedUp"];
        }

        /* CHANNEL PAGE */
        public function getProfilePic() {
            return $this->sqlData["user_profilePic"] ?? "https://videos.winsvideo.net/uploads/images/profilePics/default.png";
        }

        public function getBanner() {
            return $this->sqlData["user_banner"] ?? NULL;
        }

        public function getAboutText() {
            return $this->sqlData["user_aboutText"] ?? NULL;
        }

        public function getCountry() {
            return $this->sqlData["user_country"] ?? NULL;
        }

        public function getLinks() {
            // coming soon
            return $this->sqlData["user_links"] ?? NULL;
        }

        public function getBadges() {
            return $this->sqlData["user_badges"] ?? NULL;
        }

        public function getStatus() {
            return $this->sqlData["user_status"] ?? NULL;
        }

        public function getContactEmail() {
            return $this->sqlData["user_contactEmail"] ?? NULL;
        }

        public function getBackground() {
            return $this->sqlData["user_background"] ?? NULL;
        }

        public function getColor() {
            return $this->sqlData["user_color"] ?? NULL;
        }

        /* SUBSCRIPTIONS */
        public function isSubscribedTo($userTo) {
            $username = $this->getUsername();
            $query = $this->con->prepare("SELECT * FROM subscribers WHERE userTo=:userTo AND userFrom=:userFrom");
            $query->bindParam(":userTo", $userTo);
            $query->bindParam(":userFrom", $username);
            $query->execute();
            return $query->rowCount() > 0;
        }

        public function getSubscriberCount() {
            $username = $this->getUsername();
            $query = $this->con->prepare("SELECT * FROM subscribers WHERE userTo=:userTo");
            $query->bindParam(":userTo", $username);
            $query->execute();
            return $query->rowCount();
        }

        public function getSubscriptions() {
            $query = $this->con->prepare("SELECT userTo FROM subscribers WHERE userFrom=:userFrom");
            $username = $this->getUsername();
            $query->bindParam(":userFrom", $username);
            $query->execute();
            
            $subs = array();
            while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $user = new User($this->con, $row["userTo"]);
                array_push($subs, $user);
            }
            return $subs;
        }

    }
?>