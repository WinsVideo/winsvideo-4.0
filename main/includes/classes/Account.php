<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    class Account {

        private $con;
        private $errorArray = array();

        public function __construct($con) {
            $this->con = $con;
        }

        /* MAIN SHIT */

        public function login($un, $pw) {

            $query = $this->con->prepare("SELECT * FROM users WHERE user_username=:un");
            $query->bindParam(":un", $un);
            $query->execute();

            $result = $query->fetchAll();
            if($query->rowCount() == 1) {
                foreach($result as $row) {
                    if(password_verify($pw, $row["user_password"])) {
                        return true;
                    } 
                }
            } else {
                array_push($this->errorArray, Constants::$loginFailed);
                return false;
            }          
            
        }

        public function register($displayName, $un, $pw, $pw2, $em, $em2) {
            $this->validateDisplayName($displayName);
            $this->validateUsername($un);
            $this->validatePassword($pw, $pw2);
            $this->validateEmails($em, $em2);

            if (empty($this->errorArray)) {
                return $this->insertUserDetails($displayName, $un, $pw, $em);
            } else {
                return false;
            }
        }

        public function insertUserDetails($displayname, $un, $pw, $em) {

            $displayname = htmlspecialchars(strip_tags($displayname));
            $un = htmlspecialchars(strip_tags($un));
            $pw = htmlspecialchars(strip_tags($pw));
            $em = htmlspecialchars(strip_tags($em));

            $hashed_password = password_hash($pw, PASSWORD_DEFAULT);

            $query = $this->con->prepare("INSERT INTO users (user_displayname, user_username, user_email, user_password) VALUES(:displayname, :un, :em, :pw)");
            $query->bindParam(":displayname", $displayname);
            $query->bindParam(":un", $un);
            $query->bindParam(":em", $em);
            $query->bindParam(":pw", $hashed_password);

            return $query->execute();
        }



        /* SUB-MAIN SHIT */
        private function validateDisplayName($displayName) {
            if(strlen($displayName) > 1000 || strlen($displayName) < 2) {
                array_push($this->errorArray, Constants::$displayNameCharacters);
            }
        }

        private function validateUsername($un) {
            if(strlen($un) > 100 || strlen($un) < 5) {
                array_push($this->errorArray, Constants::$usernameCharacters);
                return;
            }
    
            $query = $this->con->prepare("SELECT user_username FROM users WHERE user_username=:un");
            $query->bindParam(":un", $un);
            $query->execute();
    
            if($query->rowCount() != 0) {
                array_push($this->errorArray, Constants::$usernameTaken);
            }
        }

        private function validatePassword($pw, $pw2) {
            if($pw != $pw2) {
                array_push($this->errorArray, Constants::$passwordsDoNotMatch);
                return;
            }
    
            if(preg_match("/[^A-Za-z0-9]/", $pw)) {
                array_push($this->errorArray, Constants::$passwordNotAlphanumeric);
                return;
            }
    
            if(strlen($pw) > 128 || strlen($pw) < 5) {
                array_push($this->errorArray, Constants::$passwordLength);
            }
        }

        // for validating emails when registering 
        private function validateEmails($em, $em2) {
            if($em != $em2) {
                array_push($this->errorArray, Constants::$emailsDoNotMatch);
                return;
            }
    
            if(!filter_var($em, FILTER_VALIDATE_EMAIL)) {
                array_push($this->errorArray, Constants::$emailInvalid);
                return;
            }
    
            $query = $this->con->prepare("SELECT user_email FROM users WHERE user_email=:em");
            $query->bindParam(":em", $em);
            $query->execute();
    
            if($query->rowCount() != 0) {
                array_push($this->errorArray, Constants::$emailTaken);
            }
        }

        /* GET ERROR STUFF */
        public function getError($error) {
            if(!in_array($error, $this->errorArray)) {
                $error = "";
            }
            return "<span class='errorMessage'>$error</span>";
        }

        public function getFirstError() {
            if(!empty($this->errorArray)) {
                return $this->errorArray[0];
            } else {
                return 0;
            }
        }

        /* GET USERS IP */
        private function get_client_ip() {
            $ipaddress = '';
            if (getenv('HTTP_CLIENT_IP'))
                $ipaddress = getenv('HTTP_CLIENT_IP');
            else if(getenv('HTTP_X_FORWARDED_FOR'))
                $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
            else if(getenv('HTTP_X_FORWARDED'))
                $ipaddress = getenv('HTTP_X_FORWARDED');
            else if(getenv('HTTP_FORWARDED_FOR'))
                $ipaddress = getenv('HTTP_FORWARDED_FOR');
            else if(getenv('HTTP_FORWARDED'))
               $ipaddress = getenv('HTTP_FORWARDED');
            else if(getenv('REMOTE_ADDR'))
                $ipaddress = getenv('REMOTE_ADDR');
            else
                $ipaddress = 'UNKNOWN';
            return $ipaddress;
        }

    }
?>