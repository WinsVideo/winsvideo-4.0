<?php
    class VideoGrid {

        private $con, $userLoggedInObj;
        private $largeMode = false;
        private $gridClass = "videoGrid";

        public function __construct($con, $userLoggedInObj) {
            $this->con = $con;
            $this->userLoggedInObj = $userLoggedInObj;
        }

        /* MAIN FUNCTIONS */

        public function create($videos, $title, $showFilder) {
            if($videos == null) {
                $gridItems = $this->generateItems();
            } else {
                $gridItems = $this->generateItemsFromVideos($videos);
            }

            $header = "";

            if($title != null) {
                $header = $this->createGridHeader($title, $showFilder);
            }

            return "$header
                    <div class='$this->gridClass'>
                        $gridItems
                    </div>";
        }

        public function createLarge($videos, $title, $showFilter) {
            $this->gridClass .= " large";
            $this->largeMode = true;
            return $this->create($videos, $title, $showFilter);
        }

        /* GENERATING ITEMS */

        public function generateItems() {

            $query = $this->con->prepare("SELECT DISTINCT * FROM videos WHERE video_privacy = '1' ORDER BY RAND() LIMIT 30");
            $query->execute(); 

            $gridItems = "";

            while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $video = new Video($this->con, $row, $this->userLoggedInObj);
                $gridItem = new VideoGridItem($video, $this->largeMode);
                $gridItems .= $gridItem->create();
            }

            return $gridItems;
        }

        public function generateItemsWithoutVideo($videoUrlException) {
            $query = $this->con->prepare("SELECT DISTINCT * FROM videos WHERE video_privacy = '1' AND video_url != '$videoUrlException' ORDER BY RAND() LIMIT 30");
            $query->execute();   

            $gridItems = "";

            while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $video = new Video($this->con, $row, $this->userLoggedInObj);
                $gridItem = new VideoGridItem($video, $this->largeMode);
                $gridItems .= $gridItem->create();
            }

            return $gridItems;
        }

        public function generateItemsFromVideos($videos) {
            $elementsHtml = "";

            foreach($videos as $video) {
                $item = new VideoGridItem($video, $this->largeMode);
                $elementsHtml .= $item->create();
            }

            return $elementsHtml;
        }

        public function createGridHeader($title, $showFilter) {
            
            $filter = "";

            if($showFilter) {
                
                $link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                $urlArray = parse_url($link);
                $query = $urlArray["query"];

                parse_str($query, $params);

                unset($params["orderBy"]);

                $newQuery = http_build_query($params);

                $newUrl = basename($_SERVER["PHP_SELF"]) . "?" . $newQuery;

                $filter = "<div class='right'>
                                <span>Order by:</span>
                                <a href='$newUrl&orderBy=uploadDate'>Upload date</a>
                                <a href='$newUrl&orderBy=views'>Most viewed</a>
                        </div>";
            }

            return "<div class='videoGridHeader'>
                        <div class='left'>
                            <span class='title'>$title</span>
                        </div>
                        $filter
                    </div>";
        }
        
        

    }
?>