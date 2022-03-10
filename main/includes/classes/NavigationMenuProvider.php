<?php
// include_once("includes/classes/ProfileData.php");
require_once("includes/classes/User.php");  
class NavigationMenuProvider {

    private $con, $userLoggedInObj;

    public function __construct($con, $userLoggedInObj) {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create() {

        $usernameLoggedIn = User::isLoggedIn() ? $_SESSION["userLoggedIn"] : "";

        $menuHtml = $this->createNavItem("Home", "home", "https://winsvideo.net");

        if(User::isLoggedIn()) {
            $menuHtml .= $this->createNavItem("My channel", "account_circle", "profile?username=$usernameLoggedIn");
        }

        $menuHtml .= $this->createNavItem("Upload", "upload", "upload");
        
        $menuHtml .= $this->createNavItem("Liked", "thumb_up", "likedVideos");

        $menuHtml .= $this->createNavItem("On the Rise", "trending_up", "trending");
        $menuHtml .= $this->createNavItem("Subscribed", "subscriptions", "subscriptions");
        $menuHtml .= $this->createNavItem("Changes", "task_alt", "changes");

        if(User::isLoggedIn()) {
            $menuHtml .= $this->createNavItem("Logout", "logout", "logout");


            // $menuHtml .= $this->createSubscriptionsSection();

        }

        return "<div class='navigationItems'>
                        <div class='navigationMenu'>
                            $menuHtml
                        </div>
                </div>";
    }

    private function createNavItem($text, $icon, $link) {
        return "<div class='navigationItem'>
                    <a href='$link'>
                        <span class='material-icons'>
                            $icon
                        </span>
                        <span class='navigationLink'>$text</span>
                    </a>
                </div>";
    }

    // <img class='navigationImage' src='$icon'>

    private function createNavChannelsItem($text, $icon, $link) {
        return "<div class='navigationItemChannels'>
                    <a href='$link'>
                        <img class='navigationImageChannels' src='$icon'>
                        <span class='navigationLinkChannels'>$text</span>
                    </a>
                </div>";
    }

    /* private function createSubscriptionsSection() {
        $subscriptions = $this->userLoggedInObj->getSubscriptions();

        $html = "<span class='heading'>Followed</span>";
        foreach($subscriptions as $sub) {
            $subUsername = $sub->getUsername();
            $html .= $this->createNavChannelsItem($subUsername, $sub->getProfilePic(), "profile?username=$subUsername");
        }
        return $html;
    } */
}
?> 


