<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    class VideoProcessor {
        /* URL generator */
        private function generateUrl($length = 11) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }

        private $con;
        private $sizeLimit = 1000000000;
        private $allowedTypes = array("mp4", "flv", "webm", "mkv", "vob", "ogv", "ogg", "avi", "wmv", "mov", "mpeg", "mpg", "mtc");
    
        public function __construct($con) {
            $this->con = $con;
        }

        /* the main function */
        public function upload($videoUploadData) {

            $videoFileData = $videoUploadData->videoFileDataArray;
            $videoRootDir = "/var/www/html/winsvideo.net/main/";
            $tmpFileDir = $videoRootDir . "uploads/tmp/";
            $convertedFileDir = $videoRootDir . "uploads/videos/";
            $convertedFileName = hash('sha512', rand()) . ".mp4";

            $tmpFilePath = $tmpFileDir . hash('sha512', rand()) . basename($videoFileData["name"]);
            $tmpFilePath = str_replace(" ", "_", $tmpFilePath);

            $isValidFile = $this->checkFileData($videoFileData, $tmpFilePath);
            $videoUrl = $this->generateUrl();

            if(!$isValidFile) {
                return false;
            }

            if(move_uploaded_file($videoFileData["tmp_name"], $tmpFilePath)) {
                $convertedFilePath = $convertedFileDir . $convertedFileName;
                $remoteUrlVideoFilePath = "https://videos.winsvideo.net/uploads/videos/" . $convertedFileName;

                if(!$this->insertVideoData($videoUploadData, $remoteUrlVideoFilePath, $videoUrl)) {
                    echo "Error inserting video data into database";
                    return false;
                } 

                if(!$this->processVideoFile($tmpFilePath, $convertedFilePath, $videoUrl, "https://videos.winscloud.net/")) {
                    echo "Error processing video file";
                    return false;
                } else {
                    return [true, $videoUrl]; 
                }
                    
            }
        }

        /* process the video file data */
        private function checkFileData($videoFileData, $filePath) {
            $videoFileType = pathInfo($filePath, PATHINFO_EXTENSION);
            
            if(!$this->isValidSize($videoFileData)) {
                echo "File is too big. File must be less than 100MB";
                return false;
            } else if(!$this->isValidType($videoFileType)) {
                echo "Invalid file type. File must be of the following types: " . implode(", ", $this->allowedTypes);
                return false;
            } else if(!$this->hasError($videoFileData)) {
                echo "There was an error uploading the file";
                print_r($videoFileData);
                return false;
            }

            return true;

        }

        /* checking stuff */
        private function isValidSize($data) {
            return $data["size"] <= $this->sizeLimit;
        }
    
        private function isValidType($type) {
            $lowercased = strtolower($type);
            return in_array($lowercased, $this->allowedTypes);
        }
    
        private function hasError($data) {
            return $data["error"] == 0;
        }

        /* insert the god damn video data to the db */
        private function insertVideoData($uploadData, $filePath, $videoUrl) {
            $query = $this->con->prepare("INSERT INTO videos(video_url, video_title, video_description, video_author, video_category, video_privacy, video_tags, video_filePath, video_convert_status) VALUES(:video_url, :video_title, :video_description, :video_author, :video_category, :video_privacy, :video_tags, :video_filePath, '0')");
            if(!$uploadData->videoAuthor) {
                echo "You are not signed in >:(";
                return false;
            }

            $query->bindParam(":video_url", $videoUrl);
            $query->bindParam(":video_title", $uploadData->videoTitleInput);
            $query->bindParam(":video_description", $uploadData->videoDescriptionInput);
            $query->bindParam(":video_author", $uploadData->videoAuthor);
            $query->bindParam(":video_category", $uploadData->videoCategoryInput);
            $query->bindParam(":video_privacy", $uploadData->videoPrivacyInput);
            $query->bindParam(":video_tags", $uploadData->videoTagsInput);
            $query->bindParam(":video_filePath", $filePath);
            
            echo "<b>Insert Video Data Function</b>";
            echo "<br>$videoUrl";

            return $query->execute();
        }

        // this function is convert the video file to mp4, delete the tmp file, generate thumbnail, calculate the duration and update the db
        private function processVideoFile($tmpFilePath, $convertedFilePath, $videoUrl, $remoteUrl) {
            exec("/usr/bin/php -f /var/www/html/winsvideo.net/execute/convert.php $tmpFilePath $convertedFilePath $videoUrl /dev/null 2>/dev/null &");
            echo "<br><br><b>Process video file function</b>";
            echo "<br><b>TEMP</b>: $tmpFilePath <br> <b>FINAL</b>: $convertedFilePath <br> <b>URL:</b> $videoUrl<br>";
            return true;
        }
    }
?>