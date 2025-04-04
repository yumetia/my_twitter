<?php

if (!isset($_SESSION["tweet_username"])) {
    (new Redirect())->redirectPath("error404");
}


$tweetUsername = $_SESSION["tweet_username"];
$tweetDate = $_SESSION["tweet_date"];
$tweetContent = $_SESSION["tweet_content"];
$reply = isset($_SESSION["comment_id"]) ? true : false;


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Commentaires</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="View/css/global/header.css">
    <link rel="stylesheet" href="View/css/global/main.css">
    <link rel="stylesheet" href="View/css/global/skeleton.css">
    <link rel="stylesheet" href="View/css/global/footer.css">
    
    <link rel="stylesheet" href="View/css/components/popup.css">

    <!--  variable JS isReply -->
    <script>
        const isReply = <?= json_encode($reply); ?>;
    </script>
    
    <!-- JS -->
    <script src="View/js/components/likes.js" defer></script>
    <script src="View/js/components/retweets.js" defer></script>
    <script src="View/js/pages/tweetAndCommentClick.js" defer></script>
    <script src="View/js/pages/sendComment.js" defer></script>
    <script src="View/js/global/theme.js" defer></script>
    <script src="View/js/global/logout.js" defer></script>
</head>
<body class="<?= $reply ? 'reply-page' : '' ?>">
    <div class="container">
        <?= (new HtmlHelper())->renderHeader(); ?>
        <div class="six columns comment-interface-block">
            
            <?php
            if(isset($_SESSION["comment_id"])){
                
                $commentUsername= $_SESSION["comment_username"];
                $commentDate= $_SESSION["comment_date"];
                $commentContent= $_SESSION["comment_content"];
                echo (new HtmlHelper())->commentInterface($commentUsername, $commentDate, $commentContent);
            } else{
                echo (new HtmlHelper())->commentInterface($tweetUsername, $tweetDate, $tweetContent);
            }
            ?>
            <?php
                if ($reply) {
                    (new Comments())->loadComments(true);
                } else {
                    (new Comments())->loadComments(false);
                }
            ?>
        </div>
        <?= (new HtmlHelper())->trendingBar(); ?>
    </div>
</body>
</html>
