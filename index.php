<?php
session_start();
require_once "autoload.php";

// Extracting path without the request chain
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request = trim($request, "/");


$page = empty($request) ? "login" : $request;

$routes = [
    "messages" => "View/messages.php",
    "login" => "View/login.php",
    "register" => "View/register.php",
    "Login" => "Controller/Login.php",
    "Register" => "Controller/Register.php",
    "Edit" => "Controller/Edit.php",
    "Messages" => "Controller/Messages.php",
    "Tweets" => "Controller/Tweets.php",
    "Comments" => "Controller/Comments.php",
    "Likes" => "Controller/Likes.php",
    "Retweets" => "Controller/Retweets.php",
    "Redirect" => "Helper/Redirect.php",
    "home" => "View/content/home.php",
    "profile" => "View/content/profile.php",
    "messages" => "View/content/messages.php",
    "comments" => "View/content/comments.php",
    "import" => "Config/import.php",
    "test" => "test.php"
];

if (array_key_exists($page, $routes)) {
    require_once $routes[$page];
} else {
    http_response_code(404);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>404</title>
    </head>
    <body>
        <img style="display:block;margin:auto;" src="View/assets/twitter-404.png" alt="404 twitter">
    </body>
    </html>
    <?php
}
?>
