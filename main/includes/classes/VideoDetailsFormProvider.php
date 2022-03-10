<?php
    class VideoDetailsFormProvider {

        private $con;

        public function __construct($con) {
            $this->con = $con;
        }
        
        public function createUploadForm() {
            $fileInput = $this->createFileInput();
            $titleInput = $this->createTitleInput(null);
            $descriptionInput = $this->createDescriptionInput(null);
            $tagsInput = $this->createTagsInput(null);
            $categoryInput = $this->createCategoryInput(null);
            $privacyInput = $this->createPrivacyInput(null);
            $uploadButton = $this->createUploadButton();

            return "<form action='processing.php' method='POST' enctype='multipart/form-data'>
                        <div class='form-group'>
                            <div class='videoFileContainer'>
                                $fileInput
                            </div>
                        </div>

                        <div class='form-group'>
                            <div class='videoDetailsContainer'>
                                $titleInput
                                $descriptionInput
                                $tagsInput
                                $categoryInput
                                $privacyInput
                            </div>
                            $uploadButton    
                        </div>
                    </form>";
        }

        public function createEditDetailsForm($video) {
            $titleInput = $this->createTitleInput($video->getTitle());
            $descriptionInput = $this->createDescriptionInput($video->getDescription());
            $tagsInput = $this->createTagsInput($video->getTags());
            $categoryInput = $this->createCategoryInput($video->getCategory());
            $privacyInput = $this->createPrivacyInput($video->getPrivacy());
            $updateButton = $this->createUpdateButton();
            $deleteButton = $this->createDeleteButton();

            return "<form method='POST'>
                        $titleInput
                        $descriptionInput
                        $tagsInput
                        $categoryInput
                        $privacyInput
                        $updateButton
                        $deleteButton
                    </form>";

        }

        private function createFileInput() {
            return "<label for='videoFileInput'>Select a video to upload:</label>
                    <br><input type='file' accept='.mp4,.avi,.mov,.webm,.mkv' id='videoFileInput' name='videoFileInput' class='form-control' required>";
        }

        private function createTitleInput($value) {
            if($value == null) $value = "";
            return "<label for='videoTitleInput'>Title</label>
                    <br><input type='text' id='videoTitleInput' name='videoTitleInput' class='form-control' required>";
        }

        private function createDescriptionInput($value) {
            return "<br><label for='videoDescriptionInput'>Description</label>
                    <br><textarea rows='5' id='videoDescriptionInput' name='videoDescriptionInput' class='form-control' ></textarea>";
        }

        private function createTagsInput($value) {
            return "<br><label for='videoTagsInput'>Tags</label>
                    <br><input type='text' id='videoTagsInput' name='videoTagsInput' class='form-control'>";
        }

        private function createCategoryInput($value) {
            if($value == null) $value = "";
            $query = $this->con->prepare("SELECT * FROM categories");
            $query->execute();

            $html = "<br><label for='videoCategoryInput'>Category</label>
                    <br><select id='videoCategoryInput' name='videoCategoryInput' class='form-control'>";

                    while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                        $id = $row["category_id"];
                        $name = $row["category_name"];
                        $selected = ($id == $value) ? "selected='selected'" : "";

                        $html .= "<option $selected value='$id'>$name</option>";
                    }

                    $html .= "</select>";

            return $html;
        }

        private function createPrivacyInput($value) {
            if($value == null) $value = "";

            $publicSelected = ($value == 1) ? "selected='selected'" : "";
            $privateSelected = ($value == 0);
            return "<br><br><label for='videoPrivacyInput'>Privacy</label>
                    <br><select id='videoPrivacyInput' name='videoPrivacyInput' class='form-control'>
                            <option value='1'>Public</option>
                            <option value='2'>Private</option>
                        </select>";
        }

        private function createUploadButton() {
            return "<br><button class='btn btn-primary' type='submit' name='uploadSubmit' style='padding: 10px 20px 10px 20px;'>Upload</button>";
        }

        private function createSaveButton() {
            return "<br><button class='btn btn-success' type='submit' name='saveSubmit' style='padding: 10px 20px 10px 20px;'>Save Info</button>";
        }

        public function createDeleteButton() {
            return "<br><button class='btn btn-danger' type='submit' name='deleteSubmit' style='padding: 10px 20px 10px 20px;'>Delete</button>";
        }

    }
?>