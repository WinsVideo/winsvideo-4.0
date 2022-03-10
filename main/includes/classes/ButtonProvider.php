<?php
class ButtonProvider {

    public static $signInFunction = "notSignedIn()";

    public static function createLink($link) {
        return User::isLoggedIn() ? $link : ButtonProvider::$signInFunction;
    }

    public static function createButton($text, $imageSrc, $action, $class) {

        $image = ($imageSrc == null) ? "" : "<img src='$imageSrc'>";

        $action  = ButtonProvider::createLink($action);

        return "<button class='$class' onclick='$action'>
                    $image
                    <span class='text'>$text</span>
                </button>";
    }

    public static function createCommentButton($text, $imageSrc, $action, $class) {

        $image = ($imageSrc == null) ? "" : "<img src='$imageSrc'>";

        $action  = ButtonProvider::createLink($action);

        return "<button class='$class' onclick='$action'>
                    $image
                    <span class='text'>$text</span>
                </button>";
    }


    public static function createHyperlinkButton($text, $imageSrc, $href, $class) {

        $image = ($imageSrc == null) ? "" : "<img src='$imageSrc'>";

        return "<a href='$href'>
                    <button class='$class'>
                        $image
                        <span class='text'>$text</span>
                    </button>
                </a>";
    }

    public static function createUserProfileButton($con, $username) {
        $userObj = new User($con, $username);
        $profilePic = $userObj->getProfilePic();
        // $profilePic = NULL;
        $link = "channel?username=$username";
        $link2 = "dashboard?username=$username";

        if($username == NULL) {
            $profilePic = "https://videos.winsvideo.net/uploads/images/profilePics/default.png";
        }
        return "<a href='$link'>
                    <img src='$profilePic' class='profilePicture' alt='Your Channel'>
                </a>";
    }
    
    public static function createEditVideoButton($videoId) {
        $href = "editVideo?videoId=$videoId";

        $button = ButtonProvider::createHyperlinkButton("EDIT VIDEO", null, $href, "edit button");

        return "<div class='editVideoButtonContainer'>
                    $button
                </div>";
    }

	public static function createDashboardButton($con, $username) {
        $userObj = new User($con, $username);
        $profilePic = $userObj->getProfilePic();
        $link = "dashboard.php?username=$username";

        return "<a href='$link'>
                    <img src='assets/images/icons/sharp_analytics_black_48dp.png' alt='Dashboard' />
                </a>";
    }

	public static function createEditChannelButton($con, $username) {
        $userObj = new User($con, $username);
        $profilePic = $userObj->getProfilePic();
        $link = "editChannel.php?username=$username";

        return "<a href='$link'>
                    <img src='assets/images/icons/create.png' alt='Edit Channel' />
                </a>";
    }
    
    public static function createSubscriberButton($con, $userToObj, $userLoggedInObj) {
        $userTo = $userToObj->getUsername();
        $userLoggedIn = $userLoggedInObj->getUsername();

        $isSubscribedTo = $userLoggedInObj->isSubscribedTo($userTo);
        $buttonText = $isSubscribedTo ? "SUBSCRIBED" : "SUBSCRIBE";
        $buttonText .= " " . $userToObj->getSubscriberCount();

        $buttonClass = $isSubscribedTo ? "unsubscribe button" : "subscribe button";
        $action = "subscribe(\"$userTo\", \"$userLoggedIn\", this)";

        $button = ButtonProvider::createButton($buttonText, null, $action, $buttonClass);

        return "<div class='subscribeButtonContainer'>
                    $button
                </div>";
    }
    
    public static function createUserProfileNavigationButton($con, $username) {
        if(User::isLoggedIn()) {
            return ButtonProvider::createUserProfileButton($con, $username);
        }
        else {
            return "<a href='login'>
                        <img src='assets/images/icons/login.png' alt='login'>
                    </a>";
        }
    }

}
?>