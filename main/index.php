<?php
    include("includes/header.php");
?>

<style>
    @import url('https://fonts.googleapis.com/css?family=Red+Hat+Display:900&display=swap');

    .black-lives-matter {
        font-size: 8vw;
        line-height: 8vw;
        margin: 0;
        font-family: 'Red Hat Display', sans-serif;
        font-weight: 900;
        background: url(https://raw.githubusercontent.com/s1mpson/-/master/codepen/black-lives-matter/victim-collage.png);
        background-size: 40%;
        background-position: 50% 50%;
        -webkit-background-clip: text;
        color: rgba(0,0,0,0.08);
        animation: zoomout 10s ease 500ms forwards;
    }

    @keyframes zoomout {
        from {
            background-size: 40%;
        }
        to {
            background-size: 10%;
        }
    }
</style>

<div class="column">

    <div align="center">
        <a href="https://blacklivesmatter.com/about/"><h1 class="black-lives-matter">Black Lives Matter</h1></a>
        <p class="black-lives-matter-caption">Learn more about the Black Lives Matter movement, by clicking here <a href="https://www.reddit.com/r/AntiHateCommunities/">https://blacklivesmatter.com/about/</a> or the text above. Credit to the original source code <a href="https://codepen.io/s1mpson/pen/MWKYMEe">https://codepen.io/s1mpson/pen/MWKYMEe</a></p>
    </div>

    <div class="videoSection">
        <?php
            $videoGrid = new VideoGrid($con, $userLoggedInObj->getUsername());
            echo $videoGrid->create(null, "Recommended", false);
        ?>
    </div>
</div>

<?php
    include("includes/footer.php");
?>
