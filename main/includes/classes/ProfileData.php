<?php
    class ProfileData {

        public $con, $profileUserObj;

        public function __construct($con, $profileUsername) {
            $this->con = $con;
            $this->profileUserObj = new User($con, $profileUsername);
        }

        public function getProfileUserObj() {
            return $this->profileUserObj;
        }

        public function getProfileUsername() {
            return $this->profileUserObj->getUsername();
        }

        public function userExists() {
            $query = $this->con->prepare("SELECT * FROM users WHERE user_username = :username");
            $profileUsername = $this->getProfileUsername();
            $query->bindParam(":username", $profileUsername);
            $query->execute();

            return $query->rowCount() != 0;
        }

        public function getProfileBanner() {
            return $this->profileUserObj->getBanner();
        }

        public function getProfileDisplayname() {
            return $this->profileUserObj->getDisplayName();
        }

        public function getProfilePic() {
            return $this->profileUserObj->getProfilePic();
        }

        public function getSubscriberCount() {
            return $this->profileUserObj->getSubscriberCount();
        }

        public function getAboutText() {
            return $this->profileUserObj->getAboutText();
        }

        public function getCountry() { 
            return $this->profileUserObj->getCountry(); 
        } 
    
        public function getLinks() { 
            return $this->profileUserObj->getLinks(); 
        } 
    
        public function getBadges() { 
            return $this->profileUserObj->getBadges(); 
        }
    
        public function getStatus() { 
            return $this->profileUserObj->getStatus(); 
        }
        
        public function getBackground() { 
            return $this->profileUserObj->getBackground(); 
        }
    
        public function getColor() { 
            return $this->profileUserObj->getColor(); 
        }
    
        public function loggedInUsername() { 
            return $this->profileUserObj->loggedInUsername(); 
        }

        public function getDisplayEmail() {
            return $this->profileUserObj->getContactEmail();
        }

        public function getTotalVideoCount() {
            $query = $this->con->prepare("SELECT count(video_id) AS total FROM videos WHERE video_author=:uploadedBy");
            $username = $this->getProfileUsername();
            $query->bindParam(":uploadedBy", $username);
            $query->execute();
    
            return $query->fetchColumn();
        }
        
    
        public function getUsersVideos() {
            $query = ""; 
            $usernameLoggedIn = $_SESSION["userLoggedIn"] ?? NULL;

            if($usernameLoggedIn != $this->getProfileUsername()) {
                $query = $this->con->prepare("SELECT * FROM videos WHERE video_author=:uploadedBy AND video_privacy = '1' ORDER BY video_uploadDate DESC");
            }
    
            if($usernameLoggedIn == $this->getProfileUsername()) {
                $query = $this->con->prepare("SELECT * FROM videos WHERE video_author=:uploadedBy ORDER BY video_uploadDate DESC");
            }
            
            $username = $this->getProfileUsername();
            $query->bindParam(":uploadedBy", $username);
         
            $query->execute();
    
            $videos = array();
            while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $videos[] = new Video($this->con, $row, $this->profileUserObj->getUsername());
            }
            return $videos;
        }

        public function getUsersRandomVideos() {
            $query = ""; 
    
            $query = $this->con->prepare("SELECT * FROM videos WHERE video_author=:uploadedBy AND video_privacy = '1' ORDER BY RAND() LIMIT 1");
            
            $username = $this->getProfileUsername();
            $query->bindParam(":uploadedBy", $username);
         
            $query->execute();
    
            $videos = array();
            while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $videos[] = new Video($this->con, $row, $this->profileUserObj->getUsername());
            }
            return $videos;
        }

        public function getAllUserDetails() {
            return array(
                "Name" => $this->getProfileDisplayname(),
                "Username" => $this->getProfileUsername(),
                "Subscribers" => $this->getSubscriberCount(),
                "Total views" => number_format($this->getTotalViews()),
                "Sign up date" => $this->getSignUpDate()
            );
        }
    
        public function getTotalViews() {
            $query = $this->con->prepare("SELECT sum(video_views) FROM videos WHERE video_author=:uploadedBy");
            $username = $this->getProfileUsername();
            $query->bindParam(":uploadedBy", $username);
            
            $query->execute();
    
            return $query->fetchColumn();
        }
    
        public function getSignUpDate() {
            $date = $this->profileUserObj->getSignUpDate();
            return date("F jS, Y", strtotime($date));
        }
    }
?>