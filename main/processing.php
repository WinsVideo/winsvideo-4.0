<?php
    include("includes/header.php");

    require_once("includes/classes/VideoUploadData.php");
    require_once("includes/classes/VideoProcessor.php");

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
?>

<div class="container">
    <?php
    if(isset($_POST['uploadSubmit'])) {

        $videoUploadData = new VideoUploadData(
            $_FILES["videoFileInput"], 
            $_POST["videoTitleInput"],
            $_POST["videoDescriptionInput"],
            $_POST["videoTagsInput"],
            $_POST["videoCategoryInput"],
            $_POST["videoPrivacyInput"],
            $_SESSION["userLoggedIn"]
        );  

        $videoProcessor = new VideoProcessor($con);
        $wasSuccessful = $videoProcessor->upload($videoUploadData);
        $insert = new VideoProcessor($con);

        // print_r($wasSuccessful);
        // echo $wasSuccessful[1];

        $videoUploadedUrl = $wasSuccessful[1];
        if($wasSuccessful[0]) {
            echo "Upload Successful";
            header("Location: https://winsvideo.net/watch?v=$videoUploadedUrl");
        } else {
            echo "Upload Failed";
        } 

    } else {
        echo "Not a post request.";
    }
    ?>
</div>

<?php
    include("includes/footer.php");
?>