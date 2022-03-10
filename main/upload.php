<?php
    include("includes/header.php");

    require_once("includes/classes/VideoDetailsFormProvider.php");

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    if(!$usernameLoggedIn) {
        header("Location: login");
    }
?>

<div class="column">
    <div class="uploadForm">
        <?php
            $formProvider = new VideoDetailsFormProvider($con);
            echo $formProvider->createUploadForm();
        ?>
    </div>
</div>

<?php
    include("includes/footer.php");
?>

<?php
        /* exec("/usr/bin/php -f /var/www/html/winsvideo.net/execute/convert.php /var/www/html/winsvideo.net/main/uploads/temp/test-video.mp4 /var/www/html/winsvideo.net/main/uploads/videos/final.mp4> /dev/null 2>/dev/null &");
        echo "Files will be encoded, come back later!"; */
    ?>