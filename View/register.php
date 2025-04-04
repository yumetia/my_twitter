<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Tweet Académie</title>
    <link rel="shortcut icon" href="favicon.ico" />
    <link rel="stylesheet" href="View/css/global/responsive.css">
    <link rel="stylesheet" href="View/css/global/skeleton.css">
    <link rel="stylesheet" href="View/css/pages/auth.css">
</head>
<body>
    <?php if ( isset($_SESSION["wrong_email"]) ||
    isset($_SESSION["email_exists"] )||
    isset($_SESSION["wrong_password"])||
    isset($_SESSION["wrong_birthdate"])):?>
    <div class="container">
        <h2 class='auth-warning'><?= 
        $_SESSION["wrong_email"]??
        $_SESSION["email_exists"] ??
        $_SESSION["wrong_password"] ??
        $_SESSION["wrong_birthdate"]
        ?></h2>
    <?php
    unset($_SESSION["wrong_email"]);
    unset($_SESSION["email_exists"]);
    unset($_SESSION["wrong_password"]);
    unset($_SESSION["wrong_birthdate"]);
    ?>
    </div>
    <?php endif;?>
    <div class="register-container">
        <img src="View/assets/twitter-logo.png" alt="Twitter Logo" class="twitter-logo">
        <h2>Inscription</h2>
        <form action="Register" method="POST">
            <input type="text" name="username" placeholder="Nom d'utilisateur" required>
            <input type="email" name="email" placeholder="Adresse email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <input type="password" name="confirm_password" placeholder="Confirmez le mot de passe" required>
            <div class="birthdate">
                <input type="date" name="birthdate" required>
            </div>
            <button type="submit" class="button-primary">S'inscrire</button>
        </form>
        <a href="login" class="login-link">Déjà un compte ? Connectez-vous</a>
    </div>
</body>
</html>

