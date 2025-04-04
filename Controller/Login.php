<?php


class Login extends GenericModel{

    public function checkUser(){
            $username = $_POST["username"];
            $salt = "vive le projet tweet_academy";
            $password = hash("ripemd160",$salt.htmlspecialchars($_POST["password"]));

            $user_row = $this->getRow("Users","username",$username);
            $redirect = new Redirect();

            var_dump($user_row);

            try
            {
                if( is_array($user_row) &&
                $user_row["username"]===$username &&
                hash_equals($password,$user_row["password"])){
                
                    // deleted ?

                if ($user_row["is_deleted"]==0){
                
                    // connected

                    $userId = $user_row["user_id"];
                    $email = $user_row["email"];
                    $birthdate = $user_row["birthdate"];
                    $profilePic = $user_row["profile"];
                    $banner = $user_row["banner"];
                    $bio = $user_row["bio"];
                    $created_at = $user_row["created_at"];
                    $theme = $user_row["theme"];
                
                    $_SESSION["user_id"]=$userId;
                    $_SESSION["username"]=$username;
                    $_SESSION["user_email"]=$email;
                    $_SESSION["user_password"]=$password;

                    $_SESSION["user_birthdate"]=$birthdate;
                    $_SESSION["user_pp"]=$profilePic;
                    $_SESSION["user_bio"]=$bio;
                    $_SESSION["user_banner"]=$banner;
                    $_SESSION["user_created_at"]=$created_at;
                    $_SESSION["theme"]=$theme;

                    $redirect->redirectPath("home");

                }

                $_SESSION["wrong_infos"] = "<strong style='color:red;'>Account deleted !</strong> <br>$username" ;
                $redirect->redirectPath("login");
            }
            
        } catch(Exception $e){
            echo "Message :".$e->getMessage();
        }
        
        $_SESSION["wrong_infos"] = "<strong style='color:red;'>Please enter correct informations :</strong> <br>$username" ;
        $redirect->redirectPath("login");
                
    }
}

if($_SERVER["REQUEST_METHOD"]==="POST"){
    $login = new Login();
    $login = $login->checkUser();
}
