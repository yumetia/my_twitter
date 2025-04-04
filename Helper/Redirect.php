<?php

$data = json_decode(file_get_contents("php://input"),true);

class Redirect {

    public function redirectPath($path){
        header("Location: $path");
        exit;
    }
    public function logout(){
        // need path in parameter because path is relative
        session_unset();
        session_destroy();
        echo json_encode(array("message"=>"logout","status"=>200));
    }

    public function disableAcc($path,$id_user){
        $request = "UPDATE user SET is_deleted='1' where user.id_user = '$id_user'";
        $stmt = $this->database->prepare($request);
        $stmt->execute();

        $this->logout($path);
    }

}

if ($_SERVER["REQUEST_METHOD"]==="POST"&&
isset($data["logout"])){
    (new Redirect())->logout("login");
}

