<?php
if (!(new CheckInfos())->isLoggedUser()) {
    (new Redirect())->redirectPath("login");
}

// important to toggle between comment and tweets display

if (isset($_SESSION["comment_id"])) {
    unset($_SESSION["comment_id"]);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="View/css/global/skeleton.css">

    <link rel="stylesheet" href="View/css/global/header.css">
    <link rel="stylesheet" href="View/css/global/main.css">
    <link rel="stylesheet" href="View/css/global/footer.css">
    
    <link rel="stylesheet" href="View/css/components/popup.css">

    <script src="View/js/components/likes.js" defer></script>
    <script src="View/js/components/retweets.js" defer></script>
    <script src="View/js/components/sendTweet.js" defer></script>
    <script src="View/js/pages/tweetAndCommentClick.js" defer></script>
    <script src="View/js/global/theme.js" defer></script>
    <script src="View/js/global/logout.js" defer></script>
</head>
<body>
<div class="container">
    <div class="row">

        <?= (new HtmlHelper())->renderHeader();?>

        <div class="six columns main-col">
            <header>
                <h2 class="title-content">Accueil</h2>
                <div class="bar"></div>
            </header>
            <div class="tweet-box">
                <a href="profile" id="profile-icon">
                    <img src="<?= $_SESSION["user_pp"]=="default" ?"View/assets/neutral_avatar.png":$_SESSION["user_pp"]; ?>" alt="logo">
                </a>
                <textarea class="send-tweet" type="text" placeholder="Quoi de neuf ?" maxlength="140" ></textarea>
                <!-- button send drop here by js -->
            </div>
            <div class="tweets">
                <!-- load tweets -->
                <?= (new Tweets())->loadTweets()?>
            </div>
        </div>

        <!-- trending bar -->
     <?= (new HtmlHelper())->trendingBar(); ?>

    </div>
</div>

<!-- footer -->
<?= (new HtmlHelper())->renderFooter();?>

</body>
</html>
