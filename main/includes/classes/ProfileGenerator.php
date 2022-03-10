<style>
@import url('https://fonts.googleapis.com/css?family=Roboto:300&display=swap');
			 div.subs {
				font-family: 'Roboto', sans-serif;
			font-weight: 300;
			color: #455a64;
				font-size: 30px;
			}

			div.subCount {
				font-family: 'Roboto', sans-serif;
			font-weight: 300;
			color: #455a64;
				font-size: 30px;
			}

			div.viewCount2 {
				font-family: 'Roboto', sans-serif;
			font-weight: 300;
			color: #455a64;
				font-size: 30px;
			}

			div.totalVids {
				font-family: 'Roboto', sans-serif;
			font-weight: 300;
			color: #455a64;
				font-size: 30px;
			}

            img.profileImage {
                border-radius: 50%;
                border:3px solid #fbfbfb;
            }

            .column {
                margin: 20px;
                padding: 20px;
                background-color: lightgray;
                border-radius: 10px;

            }

            .badges {
                width: 10px;
                height: 10px;
            }

            #channelDescription {
                white-space: -moz-pre-wrap;
                white-space: -pre-wrap;
                white-space: -o-pre-wrap;
                white-space: pre-wrap;
                word-wrap: break-word;
            }

            .inactiveLink {
                pointer-events: none;
                cursor: default;
            }

            .profileHeader {
                background-color: #fff;
                /* background-color: rgba(255,255,255,0.5); */
                box-shadow: rgba(0, 0, 0, 0) 0 1px 2px;
                border-left: 1px solid #ddd;
                border-right: 1px solid #ddd;
                float: none;
                display: inline;
            }

            .nav {
                background-color: #fff;
                box-shadow: rgba(0, 0, 0, 0.1) 0 2px 2px;
                border-left: 1px solid #ddd;
                border-right: 1px solid #ddd;
                border-bottom: 1px solid #ddd;
                float: none;
            } 

            .profileHeader .buttonContainer .buttonItem button {
		            padding: 10px 15px;
		            font-size: 14px;
		            font-weight: 500;
		            border: none;
		            border-radius: 2px;
        		}

            .textProfile:hover {
                text-decoration: underline;
            }  
                
		</style>


<?php
    require_once("ProfileData.php");

    class ProfileGenerator {
        
        private $con, $userLoggedInObj, $profileData;

        public function __construct($con, $userLoggedInObj, $profileUsername) {
            $this->con = $con;
            $this->userLoggedInObj = $userLoggedInObj;
            $this->profileData = new ProfileData($con, $profileUsername);
        }

        public function create() {
            $profileUsername = $this->profileData->getProfileUsername();

            if(!$this->profileData->userExists()) {
                return "This channel doesn't exist";
            }

            $bannerSection = $this->createBannerSection();
            $headerSection = $this->createHeaderSection();
            $tabsSection = $this->createTabsSection();
            $contentSection = $this->createContentSection();
            $channelBackground = $this->profileData->getBackground() ?? NULL;
            $channelColor = $this->profileData->getColor() ?? NULL;

            return "<div class='profileContainer' style='background: url($channelBackground); no-repeat center center fixed 
                        -webkit-background-size: auto;
                        -moz-background-size: auto;
                        -o-background-size: auto;
                        background-size: auto;
                    '>
                        <div class='article animated slideInUp'>
                        $bannerSection
                        $headerSection
                        $tabsSection
                        $contentSection
                        </div>
                    </div>";
        }
        
        public function createBannerSection() {
            $bannerPhotoSrc = $this->profileData->getProfileBanner();
            $channelName = $this->profileData->getProfileDisplayname();
            $changeProfilePhotoLink = "https://winsvideo.net/profilePicture";
            $changeBannerPhotoLink = "https://winsvideo.net/bannerPicture";
            $class = "";
            $channelUsername = $this->profileData->getProfileUsername();
            $userSession = $this->userLoggedInObj->getUsername() ?? NULL;
            $hover = "";
            $profileImage = $this->profileData->getProfilePic();
            $cursor = "cursor: default;";
            $changeBannerButton = "";

            if($userSession != $channelUsername) {
                $changeBannerPhotoLink = "";
                $changeProfilePhotoLink = "";
                $class = "inactiveLink";
                
                $changeBanner = "display: none;";
                $cursor = "cursor: default;";
            } else {
                $changeBanner = "display: inline;";
                $cursor = "cursor: default";
            }

            return "
                    <style>
                        .image {
                            opacity: 1;
                            display: block;
                            transition: .5s ease;
                            backface-visibility: hidden;
                        }

                        #edit {
                            position: absolute;
                            left: 83px;
                            top: 0px;
                            display: none;
                        }

                        #picture:hover .image~#edit{
                            display: inline;
                        }

                        .picture:hover .middle {
                            opacity: 1;
                        }

                        #editBanner {
                            position: absolute;
                            right: 0px;
                            top: 0px;
                            display: none;
                        }

                        #bannerImage:hover~#editBanner {
                            display: inline;
                            cursor: default;
                        }

                        .profileButton {
                            height: 32px;
                            width: 32px;
                            box-shadow: none;
                        }
                    </style>
                    <div class='coverPhotoContainer' style='border: 1px solid #ddd;'>
                            <div id='picture' class='picture'>
                                <a class='$class'>
                                    <img src='$profileImage' class='image' width='100' height='100' style='position: absolute; left: 15px; top: 0px; box-shadow: 0 1px 1px rgb(0 0 0 / 40%); cursor: default;'>
                                    <div id='edit' class='editButtonProfile'>
                                        <button type='submit' class='profileButton' data-toggle='modal' data-target='#profileModal'>
                                            <span class='material-icons' style='color: gray; font-size: 15px; padding-top: 5px;'>
                                                edit
                                            </span>
                                        </button>
                                    </div>
                                </a>
                            </div>
                            <a class='$class'>
                                <img src='$bannerPhotoSrc' class='coverPhoto' style='cursor: default;'>
                                
                                <div id='editBanner' class='editButtonBanner' style='cursor: pointer;'>
                                    <button type='submit' class='profileButton' data-toggle='modal' data-target='#bannerModal'>
                                        <span class='material-icons' style='color: gray; font-size: 15px; padding-top: 5px;'>
                                            edit
                                        </span>
                                    </button>
                                </div>
                                
                            </a>
                    </div>";

        }

        public function createHeaderSection() {
            $profileImage = $this->profileData->getProfilePic();
            $channelDisplayName = htmlspecialchars(strip_tags($this->profileData->getProfileDisplayname()));
            
            // UNSURE 
            $subCount = $this->profileData->getSubscriberCount();
            $subCount2 = $this->profileData->getSubscriberCount();

            $button = $this->createHeaderButton();
            
            $channelAboutText = $this->profileData->getAboutText();
            $channelColor = $this->profileData->getColor();
            $channelBadges = $this->profileData->getBadges();
            $channelUsername = $this->profileData->getProfileUsername();
            $userSession = $this->userLoggedInObj->getUsername();
            $channelBackground = $this->profileData->getBackground();
            
            $style = "";
            $badges = "";
            $badgesStyle = "";
            $profileChangeButton = "https://winsvideo.net/profilePicture";
            $class = "";

            if($userSession != $channelUsername) {
                $profileChangeButton = "";
                $class = "inactiveLink";
            }

            if(!$channelBadges) {
                $badgesStyle = "display: none";
            }

            if($channelBadges == "verified") {
                $badges = "https://winsvideo.net/assets/images/badges/verified.png";
            }

            if($channelBadges == "devs") {
                $badges = "https://images-ext-1.discordapp.net/external/aTcv5TJ74qunAZIcJgdW3nVD1ccGgcQDqPQDUgZq7tk/%3Fs%3D400%26v%3D4/https/avatars1.githubusercontent.com/u/40795980";
            }

            if($channelBackground) {
                $style = "background-color: rgba(255, 255, 255)";
            } else if (!$channelBackground) {
                $style = "background-color: rgba(255, 255, 255)";
            } else {
                $style = "background-color: rgba(255, 255, 255, 0.5); color: lightgreen;";
            }

            return "<div class='profileHeader' style='$style'>
                        <div class='userInfoContainer'>
                            <div class='userInfo'>
                                <a href='https://winsvideo.net/channel?username=$channelUsername'>
                                    <span class='title textProfile' style='$channelColor';
                                        overflow: hidden;
                                        text-overflow: ellipsis;
                                        word-wrap: break-word;
                                        word-break: break-all;
                                        text-overflow: ellipsis;
                                        color: #333;
                                    font-size: 20px;'>$channelDisplayName <img class='badges' src='$badges' style='$badgesStyle'></span>
                                </a>
                            </div>
                        </div>
                        <div class='buttonContainer'>
                            <div class='buttonItem'>
                                $button
                            </div>
                        </div>
                        <style>
                        .profileHeader {
                            padding: 0px 20px 0 15px;
                            display: flex;
                            height: 100px;
                        }
                        </style>
                    </div>

                    <div class='profileHeader2'>
                        <ul class='nav nav-tabs' role='tablist' style='$style' id='navTab'>
                            <li class='nav-item'>
                                <a class='nav-link active' id='home-tab' data-bs-toggle='tab' href='#home' role='tab' aria-controls='home' aria-selected='true' style='color: black; background-color: rgba(255, 255, 255, 0)'>Home</a>
                            </li>
                            <li class='nav-item'>
                                <a class='nav-link' id='videos-tab' data-bs-toggle='tab' href='#videos' role='tab' aria-controls='videos' aria-selected='false' style='color: black; background-color: rgba(255, 255, 255, 0)'>Videos</a>
                            </li>
                            <li class='nav-item'>
                                <a class='nav-link' id='about-tab' data-bs-toggle='tab' href='#about' role='tab' aria-controls='about' aria-selected='false' style='color: black; background-color: rgba(255, 255, 255, 0)'>About</a>
                            </li>
                        </ul>
                    </div>";
        }

        private function createHeaderButton() {
            if($this->userLoggedInObj->getUsername() == $this->profileData->getProfileUsername()) {
                return "";
            } else {
                return ButtonProvider::createSubscriberButton(
                            $this->con,
                            $this->profileData->getProfileUserObj(),
                            $this->userLoggedInObj);
            }
        }

        public function createTabsSection() {
            return "";
        }

        public function createContentSection() {

            $videos = $this->profileData->getUsersVideos();

            if(sizeof($videos) > 0) {
                $videoGrid = new VideoGrid($this->con, $this->userLoggedInObj);
                $videoGridHtml = $videoGrid->create($videos, null, false);
            } else {
                $videoGridHtml = "<span>This user has no videos.</span>";
                $subCount2 = $this->profileData->getSubscriberCount();
            }

            $aboutSection = $this->createAboutSection();
            $homeSection = $this->createHomeSection();
            $statisticsSection = $this->createStatisticsSection();
            $channelBackground = $this->profileData->getBackground();

            return "<br>
                        <div style='border-top: 1px solid #ddd; border-bottom: 1px solid #ddd;' class='tab-content channelContent transperency'>
                            <div class='tab-pane fade show active' id='home' role='tabpanel' aria-labelledby='home-tab'>
                                $homeSection
                            </div>
                            <div class='tab-pane fade' id='videos' role='tabpanel' aria-labelledby='videos-tab'>
                                $videoGridHtml
                            </div>
                            <div class='tab-pane fade' id='about' role='tabpanel' aria-labelledby='about-tab'>
                                $aboutSection
                            </div>
                        </div>";
        }

        private function createStatisticsSection() {
            $subCount3 = $this->profileData->getSubscriberCount();
            $totalViews = $this->profileData->getTotalViews();
            $name = $this->profileData->getProfileDisplayname();
            // $totalVids = $this->profileData->getTotalVids();
            $color = $this->profileData->getColor();
            $totalVideoCount = $this->profileData->getTotalVideoCount();
            $style = "";

            if($totalViews == null) {
                $style = "display: none";
            }

            if($totalVideoCount == null) {
                $style = "display: none;";
            }

            $html = "<h4>Public Statistics</h4>
                    <hr>
                    <span>NULL</span>
                    <span>Subscriber Count</span>
                        <div class='subCount'>
                            ". number_format($subCount3) ."
                        </div>
                    <span style='$style'>Total View Count</span>
                        <div class='viewCount2' style='$style'>
                            ". number_format($totalViews) ."
                        </div>
                    <span style='$style'>Total Videos Uploaded</span>
                        <div class='totalVids' style='$style'>
                            ". number_format($totalVideoCount)."
                        </div>";

            return $html;
        }

        private function createHomeSection() {
            $channelSignUpDate = $this->profileData->getSignUpDate();
            $channelDisplayName = $this->profileData->getProfileDisplayname();
            
            $subCount2 = $this->profileData->getSubscriberCount();
            
            $channelAboutText = $this->profileData->getAboutText();
            $channelVideos = $this->profileData->getUsersVideos();
            
            $channelCountry = $this->profileData->getCountry();
            $channelStatus = $this->profileData->getStatus();
            $channelColor = $this->profileData->getColor();
            $channelRandomVideos = $this->profileData->getUsersRandomVideos();

            $style = "";

            if(sizeof($channelVideos) > 0) {
                $videoGrid = new VideoGrid($this->con, $this->userLoggedInObj);
                $videoGridHtml = $videoGrid->create($channelVideos, null, false);
            } else {
                $videoGridHtml = "<br><div class='alert alert-danger' role='alert'>
                                    This user has no videos!
                                  </div>";
            }

            if(sizeof($channelRandomVideos) > 0) {
                $videoGrid2 = new VideoGrid($this->con, $this->userLoggedInObj);
                $videoGridHtml2 = $videoGrid->create($channelRandomVideos, null, false);
            } else {
                $videoGridHtml2 = "<div class='alert alert-danger' role='alert'>
                                       No videos to recommend!
                                   </div>";
            }

            if(!$channelStatus) {
                $style = "display: none";
            }

            if(!$channelCountry) {
                $style = "display: none;";
            }

            $html = "<div class='videos'>
                        <div class='recommended'>
                            <h3>Recommended</h3>
                            <span>Recommended video from $channelDisplayName</span>
                            <br><br>
                        </div>
                        $videoGridHtml2
                        <hr>
                        <div class='all-videos'>
                            <h4 class='all-videos-text'>All Videos</h4>
                            <span class='video-uploaded-by-text'>Uploaded by $channelDisplayName</span>
                            <br><br>
                        </div>
                        $videoGridHtml
                    </div>";

            return $html;
        }

        private function createAboutSection() {
            $channelSignUpDate = $this->profileData->getSignUpDate();
            $channelDisplayName = $this->profileData->getProfileDisplayname();
            
            $subCount2 = $this->profileData->getSubscriberCount();
            
            $channelAboutText = $this->profileData->getAboutText();
            $channelCountry = $this->profileData->getCountry();
            $channelColor = $this->profileData->getColor();

            $channelTotalViews = number_format($this->profileData->getTotalViews());
            $channelDisplayEmail = $this->profileData->getDisplayEmail();

            $changeDescriptionLink = "https://winsvideo.net/channelDescription";

            $channelUsername = $this->profileData->getProfileUsername();
            $userSession = $this->userLoggedInObj->getUsername();

            $style = "";
            $class = "";
            $style2 = "";

            if($userSession != $channelUsername) {
                $changeDescription = "";
                $style2 = "display: none";
            }

            if(!$channelAboutText) {
                $style = "display: none";
            }

            if(!$channelCountry) {
                $style = "display: none;";
            }

            $html = "<div class='section aboutTab'>
                <span><b>$subCount2</b> subscribers • <b>$channelTotalViews</b> views</span>
                <br><span>Joined <b>$channelSignUpDate</b></span>
                <br>
                    <div class='title name1 aboutDescription' style=''>
                    <br>
                        <b>Description</b>
                        <span style='$style2'> • </span>
                        <a href='' style='$style2' data-toggle='modal' data-target='#descriptionModal'>
                            <span><b>Edit your channel description!</b></span>
                        </a>
                        <p id='channelDescription'>$channelAboutText</p>
                    </div>
                    <hr>
                    <span><b>Details</b></span>
                    <div class='email'>
                    <br>
                        <b>Business Email: </b><span>$channelDisplayEmail</span>
                        <span style='$style2'> • </span>
                                <a href='' style='$style2' data-toggle='modal' data-target='#emailModal'>
                                    <span><b>Edit your business email</b></span>
                                </a>
                                </div>
                        <div class='country' style=''>
                            <br>
                                <b class='country-title'>Location: </b><span class='country-list'>NULL</span>
                                <span style='$style2'> • </span>
                                <a href='' style='$style2' data-toggle='modal' data-target='#locationModal'>
                                    <span><b>Edit your location</b></span>
                                </a>
                            </div>";

            
            return $html;
            
        }


    }
?>

