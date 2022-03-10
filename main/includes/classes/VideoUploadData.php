<?php
    class VideoUploadData {
        public $videoFileDataArray, $videoTitleInput, $videoDescriptionInput, $videoTagsInput, $videoCategoryInput, $videoPrivacyInput, $videoAuthor;

        public function __construct($videoFileDataArray, $videoTitleInput, $videoDescriptionInput, $videoTagsInput, $videoCategoryInput, $videoPrivacyInput, $videoAuthor) {
            $this->videoFileDataArray = $videoFileDataArray;
            $this->videoTitleInput = $videoTitleInput;
            $this->videoDescriptionInput = $videoDescriptionInput;
            $this->videoTagsInput = $videoTagsInput;
            $this->videoCategoryInput = $videoCategoryInput;
            $this->videoPrivacyInput = $videoPrivacyInput;
            $this->videoAuthor = $videoAuthor;
        }

        public function updateVideoDetails($con, $videoUrl) {
            $query = $con->prepare("UPDATE videos SET video_title = :videoTitleInput, video_description = :videoDescriptionInput, video_tags = :videoTagsInput, video_category = :videoCategoryInput, video_privacy = :videoPrivacyInput WHERE video_url = :videoUrl");

            $query->bindParam(":videoTitleInput", $this->videoTitleInput);
            $query->bindParam(":videoDescriptionInput", $this->videoDescriptionInput);
            $query->bindParam(":videoTagsInput", $this->videoTagsInput);
            $query->bindParam(":videoCategoryInput", $this->videoCategoryInput);
            $query->bindParam(":videoPrivacyInput", $this->videoPrivacyInput);
            $query->bindParam(":videoUrl", $videoUrl);
        }
    }
?>