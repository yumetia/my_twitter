<?php

// goal : generate easily html component that are repeated
//  simply by calling the corresponding methods

class HtmlHelper {

    public function renderHeader($size="one three columns"){
        // getting the link of the user pp

        $userPic = $_SESSION["user_pp"];
        
        if($userPic=="default"){
            $userPic = "View/assets/neutral_avatar.png";
        }

        $username = $_SESSION["username"];
        
        return
        "<div class='$size sidebar'>
            <nav>
                <ul>
                    <li><a href='home'><i style='margin:auto;color:#1DA1F2;font-size:24px;' class='fa-brands fa-twitter twitter-icon'></i></a></li>
                </ul>
                <ul class='navbar'>
                    <li><a href='home'><i class='fa-solid fa-house ".(new CheckInfos())->currentPage("home")."'></i>Accueil</a></li>
                    <li><a href='#'><i class='fa-solid fa-magnifying-glass ".(new CheckInfos())->currentPage("explorer")."''></i>Explorer</a></li>
                    <li><a href='#'><i class='fa-regular fa-bell ".(new CheckInfos())->currentPage("notifications")."''></i>Notifications</a></li>
                    <li><a href='messages'><i class='fa-regular fa-envelope ".(new CheckInfos())->currentPage("messages")."''></i>Messages</a></li>
                    <li><a href='#'><i class='fa-regular fa-bookmark ".(new CheckInfos())->currentPage("signets")."''></i>Signets</a></li>
                    <li><a href='#'><i class='fa-regular fa-rectangle-list ".(new CheckInfos())->currentPage("lists")."''></i>Listes</a></li>
                    <li><a href='profile'><i class='fa-regular fa-user ".(new CheckInfos())->currentPage("profile")."''></i>Profil</a></li>
                </ul>

                <button class='tweet-btn'>Tweet
                </button>
    
                <!-- logout button -->
                <div class='logout-box__container' tabindex='0'>
                    <div class='logout-box__item'>
                        <img src='$userPic' alt='Profil'>
                    </div>
                    <div class='logout-box__item'>
                        <h4 class='username'>@$username</h4>
                    </div>
                    <div class='logout-box__item'>
                        <h4>•••</h4>
                    </div>

                    <div class='logout-btn' onclick='logout()'>
                        <p>Se déconnecter</p>
                    </div>
                </div>
            </nav>
        </div>";
    }


    public function commentInterface($username, $date, $content, $grid = ""){
        $tweetId = $_SESSION["tweet_id"];
        $commentId = $_SESSION["comment_id"];
        $id = $tweetId ?? $commentId;

        if ($commentId){
            $commentCount = (new GenericModel())->count("Comments c","
            JOIN Tweets t ON c.tweet_id = t.tweet_id
            JOIN Users u ON c.user_id = u.user_id
            WHERE c.tweet_id = $tweetId and parent_comment_id=$commentId
            ORDER BY c.created_at DESC");
            $likeCount = (new Likes())->countLikes(null, $commentId);
            $retweetCount = (new Retweets())->countRetweets(null, $commentId);
            
        } else{
            $commentCount = (new GenericModel())->count("Tweets t","
            join Comments c
            on t.tweet_id=c.tweet_id
            where t.tweet_id=$tweetId;");
            $likeCount = (new Likes())->countLikes($tweetId, null);
            $retweetCount = (new Retweets())->countRetweets($tweetId, null);
        }
        return "
            <div class='commentInterface__container $grid'>
                <h3 style='text-align:center;'>Tweet</h3>
                <div class='commentInterface__tweet'>
                    <div class='tweet-header'>
                        <p class='tweet-username' style='margin:0;'><strong>$username</strong></p>
                        <p class='tweet-date' style='font-size:smaller;font-style:italic;margin:0;'>$date</p>
                    </div>
                    <div class='tweet-content'>
                        <p class='tweet-content' style='margin:0;'>$content</p>
                    </div>
                    <div class='icon'>
                        <i class='fa-regular fa-comment'><strong> $commentCount</strong></i>
                        <i class='fa-solid fa-retweet'><strong> $retweetCount</strong></i>
                        <i class='fa-regular fa-heart like-button' data-tweet-id='$id'><strong> $likeCount</strong></i>
                    </div>
                </div>
            </div>

            <div class='commentInterface__answer-input $grid'>
                <textarea class='send-tweet' placeholder='Postez votre reponse' maxlength='140'></textarea>
            </div>
            
            <div class='commentInterface__section-comments'>
                <!-- swipe vertical (infinite)-->
            </div>
        ";
    }
    
    public function popupEditProfile(){
        $username = $_SESSION["username"];
        $bio = $_SESSION["user_bio"];
        $birthdate = $_SESSION["user_birthdate"];
        
        return
        "
        <div id='popup'>
            <div class='edit__header'>
              <button class='close' onclick='closePopup()'>X</button>
              <button class='save' id='saveEdit'>Enregistrer</button>
          </div>


            <div class='edit__pp'>
              <div class='edit-profile__item'>
                <span>
                  <p>Photo de profil :</p>
                  <input type='file' class='edit-profile' id='profile_pic' name='profile_pic'>
                </span>
            </div>


            <div class='edit-banner__item'>
                <span>
                  <p>Bannière :</p>
                  <input type='file' class='edit-profile' id='banner' name='banner'>
                </span>
              </div>
            </div>


            <div class='edit__infosProfile'>
              <div class='edit-username__item'>
                <span>
                  <p>Nom d'utilisateur:</p>
                  <input maxlength='50' class='edit-profile' id='0' name='username' placeholder='$username'>
                </span>
            </div>


              <div class='edit-bio__item'>
                <span>
                  <p>Bio:</p>
                  <input maxlength='160' class='edit-profile' id='1' name='bio' placeholder='$bio'>
                </span>
              </div>


              <div class='edit-birthdate__item'>
                <span>
                  <p>Date de naissance:</p>
                  <input type='date' class='edit-profile' id='2' name='birthdate' placeholder='$birthdate'>
                </span>
              </div>
        ";
    }

    public function trendingBar(){
        return
        "
        <aside class='three columns trending-col' aria-label='Tendances'>
      <div class='theme__container' onclick='changeTheme()'>
        <i class='fa-brands fa-superpowers theme-icon'></i>
        <span>Theme</span>
      </div>
      <input class='search' type='search' placeholder='Rechercher sur Twitter'>
      <ul>
        <h3>Quoi de neuf</h3>
        <a href='#'><i class='fa-solid fa-gear'></i><span class='sr-only'>Paramètres</span></a>
        <div class='bottom-bar'></div>
        <p>Tendance mondiale</p>
        <p class='sub-title'>Tendance avec : #AuthorsDay</p>
        <img class='picture' src='#' alt='utilisateur'>
        <p class='sub-tweet'>Tina Koyama en parle sur Twitter</p>
        <div class='bottom-bar1'></div>
        <li>#NationalAuthorsDay</li>
        <p class='mill'>125k tweets</p>
        <p class='people'>5 094 personnes en parlent</p>
        <div class='bottom-bar2'></div>
        <li>#SkincareSunday</li>
        <li>#InternationalCatDay</li>
      </ul>
    </aside>
        ";
    }
    
    public function displayTheme(){
        return
        "
        <div class='theme__container' onclick='changeTheme()'>
            <i class='fa-brands fa-superpowers theme-icon'></i>
            <span>Theme</span>
        </div>
        ";
    }

    public function renderFooter(){
        return
        '
        <footer>
            <div class="footer__container">
                <p>MyTweeter by</p>
                <p>Yumetia, Ghost, Pouillou, and Hideonbush !</p>
                <p>&copy; ' . date("Y") . ' All rights reserved !</p>
            </div>
        </footer>
        ';
    }

}
