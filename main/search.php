<?php
    include_once("includes/header.php");

    require_once("includes/classes/SearchResultsProvider.php");

    if(!isset($_GET["term"]) || $_GET["term"] == "") {
        header('Location: https://winsvideo.net');
        exit();
    }

    $term = htmlspecialchars(strip_tags($_GET["term"]));

    if(!isset($_GET["orderBy"]) || $_GET["orderBy"] == "views") {
        $orderBy = "video_views";
    } else {
        $orderBy = "video_uploadDate";
    }

    $searchResultsProvider = new SearchResultsProvider($con, $userLoggedInObj);
    $videos = $searchResultsProvider->getVideos($term, $orderBy);
    $videoGrid = new VideoGrid($con, $userLoggedInObj);
?>

<div class="largeVideoGridContainer">
    <div class="column">
    <?php
        if(sizeof($videos) > 0) {
            echo $videoGrid->createLarge($videos, sizeof($videos) . " results found", true);
        } else {
            echo "<h1>Oops!</h1> <p>No results were found</p>";
        }
    ?>
    </div>
</div>

<?php
    include_once("includes/footer.php");
?>