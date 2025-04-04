<?php

$data = json_decode(file_get_contents("php://input"), true);

class Edit extends GenericModel
{
    public function updateInfosUser($column, $value, $user_id)
    {
        $sql = "UPDATE Users SET $column = :value WHERE user_id = :user_id";
        $stmt = $this->database->prepare($sql);
        $stmt->execute([
            ":value" => $value,
            ":user_id" => $user_id
        ]);
    }
    
    public function changeTheme($user_id)
    {
        $theme = $this->getRow("Users", "user_id", $user_id);
        if ($theme) {
            if ($theme["theme"] == 1) {
                echo json_encode(["theme" => $theme["theme"], "status" => 200]);
                $this->updateInfosUser("theme", "0", $user_id);
            } else {
                echo json_encode(["theme" => $theme["theme"], "status" => 200]);
                $this->updateInfosUser("theme", "1", $user_id);
            }
        } else {
            echo json_encode(["error" => "Error getting the db row"]);
        }
    }
    
    public function loadTheme($user_id)
    {
        $theme = $this->getRow("Users", "user_id", $user_id);
        if ($theme) {
            echo json_encode(["theme" => $theme["theme"], "status" => 200]);
        } else {
            echo json_encode(["error" => "Error getting the db row"]);
        }
    }
    
    private function handleUpload($inputName, $uploadDir, $currentPath, $defaultPath)
    {
        if (isset($_FILES[$inputName]) && $_FILES[$inputName]["error"] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES[$inputName]["tmp_name"];
            $fileName    = basename($_FILES[$inputName]["name"]);
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $destPath = $uploadDir . time() . "_" . $fileName;
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                if ($currentPath && $currentPath !== $defaultPath && file_exists($currentPath)) {
                    unlink($currentPath);
                }
                return $destPath;
            }
        }
        return $currentPath;
    }
    
    public function updateProfile($user_id, $username, $bio, $birthdate)
    {
        $profilePicPath = $_SESSION["user_pp"];
        $bannerPath     = $_SESSION["user_banner"];
        
        // update pp
        $profilePicPath = $this->handleUpload(
            "profile_pic",
            "View/assets/uploads/profile/",
            $profilePicPath,
            "View/assets/neutral_avatar.png"
        );
        
        // update banner
        $bannerPath = $this->handleUpload(
            "banner",
            "View/assets/uploads/banner/",
            $bannerPath,
            "View/assets/default_banner.jpg"
        );
        
        $sql = "UPDATE Users
                SET username = :username, bio = :bio, birthdate = :birthdate, profile = :profile, banner = :banner
                WHERE user_id = :user_id";
        $stmt = $this->database->prepare($sql);
        $result = $stmt->execute([
            ":username"  => $username,
            ":bio"       => $bio,
            ":birthdate" => $birthdate,
            ":profile"   => $profilePicPath,
            ":banner"    => $bannerPath,
            ":user_id"   => $user_id
        ]);
        
        if ($result) {
            $row = $this->getRow("Users", "user_id", $user_id);
            $_SESSION["username"]       = $row["username"];
            $_SESSION["user_bio"]       = $row["bio"];
            $_SESSION["user_birthdate"] = $row["birthdate"];
            $_SESSION["user_pp"]        = $row["profile"];
            $_SESSION["user_banner"]    = $row["banner"];
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["error" => "Erreur lors de la mise à jour"]);
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($data["cgTheme"])) {
    (new Edit())->changeTheme($_SESSION["user_id"]);
} elseif ($_SERVER["REQUEST_METHOD"] === "POST" && isset($data["ldTheme"])) {
    (new Edit())->loadTheme($_SESSION["user_id"]);
} elseif ($_SERVER["REQUEST_METHOD"] === "POST" && (isset($_POST["username"]) || isset($_POST["bio"]))) {
    $username  = trim($_POST["username"]);
    $bio       = trim($_POST["bio"] ?? "");
    $birthdate = trim($_POST["birthdate"] ?? "");
    (new Edit())->updateProfile($_SESSION["user_id"], $username, $bio, $birthdate);
}
