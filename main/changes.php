<?php
    include("includes/changesHeaderLegacy.php");
?>

<style>
    .toDoList pre {
        background-color: #f1f1f1;
        border-radius: 5px;
        padding: 10px;
    }
</style>

<div class="container">
    <div class="updateSection">

            <h1>Updates</h1>

            <h2>Public-Alpha / Public In-dev Phase</h2>
                <h3>14th December 2021</h3>
                    <ul>
                        <li>UI redesign done</li>
                        <li>Channel subscribing fixed</li>
                        <li>Personal profile recreated</li>
                    </ul>    
            
                <h3>25th December 2021</h3>
                <ul>
                    <li>Fixed Like/Dislike system</li>
                </ul>

                <h3>24th December 2021</h3>
                <ul>
                    <li>Added video embeds</li>
                </ul>

                <h3>22nd December 2021</h3>
                <ul>
                    <li>Redirects user to the video after uploading it.</li>
                </ul>

                <h3>20th - 21st December 2021</h3>
                <ul>
                    <li>Website opened to public</li>
                    <li>View counts counting fixed.</li>
                </ul>

            <h2>Alpha-Phase</h2>

                <h3>16 - 19th December 2021</h3>
                <ul>
                    <li>Video upload & processing part done.</li>
                    <li>Video Info Section done</li>
                    <li>Homepage done</li>
                    <li>TEMPORARY UI</li>
                </ul>
    </div>

    <div class="toDoList">
        <h2>To-Do</h2>

        <p>Priorities</p>
        <ul>
            <li>
                Search bar 

                <pre>A search bar for searching videos and channels. </pre>
            </li>
            <li>
                Edit Video Page

                <pre>The edit video page is for managing a specific video. Changing thumbnails, editing video information (title, description, tags, category), or deleting a video</pre>
            </li>
            <li>
                Categories page

                <pre>Showing videos in each categories. </pre>
            </li>
            <li>
                UI redesign (back to classic layout)
                <pre>A big / major update for WinsVideo 4.0. Here are what the UI reimplementation includes:
                    - Better animations when clicking on buttons and links
                    - Better Navbar design
                    - Profile Page
                    - Settings Page
                    - Redesigned upload page with drag & drop support</pre>
            </li>
            <li>
                Channel dashboard

                <pre>Place where you can manage your channel, your videos. You can *probably* view your analytics.
WinsVideo currently does not collect any analytics related data, this might change in the future. </pre>
            </li>
        </ul>
    </div>

    <div class="writer">
        <span>Written by <a href="https://winsdominoes.com">@WinsDominoes</a></span>
    </div>
</div>

<?php
    include("includes/footer.php");
?>