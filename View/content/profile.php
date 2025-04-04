<?php
if (!(new CheckInfos())->isLoggedUser()) {
    (new Redirect())->redirectPath("login");
}

$user = [
    'username'    => $_SESSION["username"]       ?? 'Inconnu',
    'bio'         => (trim($_SESSION["user_bio"])==""||trim($_SESSION["user_bio"]==null))? 'Pas de bio.':$_SESSION["user_bio"],
    'profile_pic' => $_SESSION["user_pp"]=="default" ? 'View/assets/neutral_avatar.png' : $_SESSION["user_pp"],
    'banner'      => $_SESSION["user_banner"]=="default" ? 'View/assets/default_banner.jpg' : $_SESSION["user_banner"],
    'created_at'  => $_SESSION["user_created_at"],
    'followers'   => 30,
    'following'   => 12
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Profile</title>
  <link rel="shortcut icon" href="favicon.ico">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="View/css/global/skeleton.css">

  <link rel="stylesheet" href="View/css/global/header.css">
  <link rel="stylesheet" href="View/css/global/main.css">
  <link rel="stylesheet" href="View/css/global/footer.css">
  <link rel="stylesheet" href="View/css/pages/profile.css">
  <link rel="stylesheet" href="View/css/components/popup.css">

  <script src="View/js/components/likes.js" defer></script>
  <script src="View/js/components/retweets.js" defer></script>
  <script src="View/js/components/popup.js" defer></script>
  
  <script src="View/js/pages/tweetAndCommentClick.js" defer></script>
  <script src="View/js/pages/editProfile.js" defer></script>
  
  <script src="View/js/global/logout.js" defer></script>
  <script src="View/js/global/theme.js" defer></script>
</head>
<body>
<div class="container">
  <div class="row">
      <?= (new HtmlHelper())->renderHeader(); ?>
    
    <main class="six columns main-col">

      <header>
        <h2 class="profile-title">Profile</h2>
      </header>

      <div class="profile-header">

        <div class="profile-banner">
          <img src="<?= htmlspecialchars($user['banner'], ENT_QUOTES, 'UTF-8') ?>" alt="Banner">
        </div>

        <div class="profile-pic-container">
          <img src="<?= htmlspecialchars($user['profile_pic'], ENT_QUOTES, 'UTF-8') ?>" alt="Profile" class="profile-pic">
        </div>

        <div class="profile-info">

          <div class="profile-info-left">
            <h3><?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?></h3>
            <p class="created_at">
              <?php
              setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
              $date = DateTime::createFromFormat('Y-m-d', $user["created_at"]);
              echo "a rejoint en " . strftime('%B %Y', $date->getTimestamp());
              ?>
            </p>
            <p class="bio"><?= htmlspecialchars($user['bio'], ENT_QUOTES, 'UTF-8') ?></p>
            
            <div class="stats">
              <span><strong><?= $user['following'] ?></strong> Following</span>
              <span><strong><?= $user['followers'] ?></strong> Followers</span>
            </div>

            <button class="edit-profile-btn" onclick="showPopup()">Modifier profil</button>
          </div>
          <?= (new HtmlHelper())->popupEditProfile();?>
        </div>

      </div>


      <div class="tweets">
        <!-- load tweets -->
        <?php (new Tweets())->loadPersoTweets()?>

      </div>
    </div>


    </main>
    <!-- trending bar -->
     <?= (new HtmlHelper())->trendingBar(); ?>
  </div>
</div>

<!-- footer -->
 <?= (new HtmlHelper())->renderFooter();?>

</body>
</html>
