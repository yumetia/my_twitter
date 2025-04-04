<?php

class Register extends CheckInfos {

    public function registerUser($username, $email, $password, $confPassword, $birthdate) {
        $errors = [];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['wrong_email'] = "Email invalide !";
        }
        if ($this->emailExists($email)) {
            $errors['email_exists'] = "Cet email est déjà utilisé !";
        }
        if ($password !== $confPassword) {
            $errors['wrong_password'] = "Les mots de passe ne correspondent pas !";
        }
        if (!$this->isValidPassword($password)) {
            $errors['wrong_password'] = "Mot de passe trop faible !";
        }
        if (!$this->isValidBirthdate($birthdate)) {
            $errors['wrong_birthdate'] = "Date de naissance invalide ou âge insuffisant !";
        }

        if (!empty($errors)) {
            foreach ($errors as $key => $message) {
                $_SESSION[$key] = $message;
            }
            return ['status' => false, 'message' => reset($errors)];
        }

        $salt = "vive le projet tweet_academy";
        $hashedPassword = hash("ripemd160", $salt . $password);

        try {
            $sql = "INSERT INTO Users (username, email, password, birthdate, profile, banner, created_at) VALUES (:username, :email, :password, :birthdate, 'default', 'default', now())";
            $stmt = $this->database->prepare($sql);
            $stmt->execute([
                ':username'   => $username,
                ':email'      => $email,
                ':password'   => $hashedPassword,
                ':birthdate'  => $birthdate
            ]);

            $user_row = $this->getRow("Users", "email", $email);
            $userId = $user_row["user_id"];

            $_SESSION["user_id"]          = $userId;
            $_SESSION["username"]         = $username;
            $_SESSION["user_email"]       = $email;
            $_SESSION["user_password"]    = $password;
            $_SESSION["user_birthdate"]   = $birthdate;
            $_SESSION["user_pp"]          = $user_row["profile"];
            $_SESSION["user_banner"]      = $user_row["banner"];
            $_SESSION["user_created_at"]  = $user_row["created_at"];
            $_SESSION["user_theme"]       = $user_row["theme"];

            return ['status' => true, 'message' => "Inscription réussie 🎉"];
        } catch (PDOException $e) {
            return ['status' => false, 'message' => "Erreur lors de l'inscription : " . $e->getMessage()];
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username     = htmlspecialchars($_POST["username"]);
    $email        = htmlspecialchars($_POST["email"]);
    $password     = htmlspecialchars($_POST["password"]);
    $confPassword = htmlspecialchars($_POST["confirm_password"]);
    $birthdate    = $_POST["birthdate"];

    $register = new Register();
    $result = $register->registerUser($username, $email, $password, $confPassword, $birthdate);

    if ($result['status']) {
        (new Redirect())->redirectPath("home");
    }
    (new Redirect())->redirectPath("register");
}
