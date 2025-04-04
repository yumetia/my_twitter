<?php

class CheckInfos extends GenericModel {
    public function isLoggedUser(){
        return isset($_SESSION["user_id"]);
    }

    public function currentPage($page){
        // all pages possibles in my project
        $currentPage = basename($_SERVER["PHP_SELF"]);

        return $page==$currentPage?"active":"";
     
    }
    public function emailExists($email) {
        $sql = "SELECT user_id FROM Users WHERE email = :email LIMIT 1";
        $stmt = $this->database->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch() ? true : false;
    }

    public function isValidPassword($password) {
        return strlen($password) >= 10   &&
        preg_match('/[A-Z]/', $password) &&
        preg_match('/[a-z]/', $password) &&
        preg_match('/[0-9]/', $password);
    }

    public function isValidBirthdate($birthdate) {
        $date = DateTime::createFromFormat('Y-m-d', $birthdate);
        if (!$date) {
            return false;
        }
        
        $today = new DateTime();
        $age = $today->diff($date)->y;
        
        return $age >= 18;
    }
}
