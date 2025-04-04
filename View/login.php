
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Tweet Académie</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="stylesheet" href="View/css/global/responsive.css">
    <link rel="stylesheet" href="View/css/global/skeleton.css">
    <link rel="stylesheet" href="View/css/pages/auth.css">
    
</head>
<body>


<?php if (isset($_SESSION["wrong_infos"])):?>
    <div class="container">
        <h2 class='auth-warning'><?= $_SESSION["wrong_infos"];
        unset($_SESSION["wrong_infos"]);
        ?></h2>
    </div>
    <?php endif;?>
    <div class="login-container">
        <img src="View/assets/twitter-logo.png" alt="Twitter Logo" class="twitter-logo">
        <h2>Connexion</h2>
        <form action="Login" method="POST">
            <!-- for easy debuggin im gonna write infos of
             a default account.
             Remove it later !!!
            -->
            <input type="text" name="username" placeholder="Nom d'utilisateur" required value="Adel" >
            <input type="password" name="password" placeholder="Mot de passe" required value="Killuu28469!">
            <button type="submit" class="button-primary">Se connecter</button>
        </form>
        <a href="register" class="register-link">Pas encore inscrit ? Créez un compte</a>
    </div>
</body>
</html>
