<?php
$data = json_decode(file_get_contents("php://input"), true);

class Tweets extends GenericModel
{
    function timeElapsed($datetime, $full = false) {
        $now = new DateTime();
        $created_at = new DateTime($datetime);

        $diff = $now->diff($created_at);
        $string = [
            'y' => 'an',
            'm' => 'mois',
            'd' => 'jour',
            'h' => 'heure',
            'i' => 'minute',
            's' => 'seconde'
        ];
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
        return $string ? 'Il y a ' . implode(', ', array_slice($string, 0, $full ? count($string) : 1)) : 'À l\'instant';
    }
    
    public function addTweet($data, $parentTweetId = null) {
        $tweetContent = $data["tweetContent"];
        $created_at = date("Y-m-d H:i:s");
        $this->addRow([
            "table"      => "Tweets",
            "user_id"    => $_SESSION["user_id"],
            "content"    => $tweetContent,
            "created_at" => $created_at,
        ]);
        $lastId = $this->count("Tweets");
        echo json_encode(array(["lastId" => $lastId]));
    }

    public function loadTweets() {
        $tweets = $this->innerJoin("Tweets", "Users", "user_id", "tweet_id,username,content,Tweets.created_at,is_archived","order by created_at desc");
        
        foreach ($tweets as $tweet) {
            $id = $tweet["tweet_id"];
            $commentCount = $this->count("Tweets t", "join Comments c
                on t.tweet_id=c.tweet_id
                where t.tweet_id=$id;");
            $likeCount = (new Likes())->countLikes($id);
            $retweetCount = (new Retweets())->countRetweets($id);
            
            $username = $tweet["username"];
            $is_archived = $tweet["is_archived"];
            
            if ($is_archived == 0 && $username !== $_SESSION["username"]) {
                $content = $tweet["content"];
                $created_at = $tweet["created_at"];
                $formatted_date = $this->timeElapsed($created_at);
                
                echo "
                <div class='tweet' id='$id'>
                    <div class='tweet-header'>
                        <p class='tweet-username'><strong>@$username</strong></p>
                        <p class='tweet-date'>$formatted_date</p>
                    </div>
                    <p class='tweet-content'>$content</p>
                    <div class='icon'>
                        <i class='fa-regular fa-comment'><strong> $commentCount</strong></i>
                        <i class='fa-solid fa-retweet retweet-button' data-tweet-id='$id'><strong> $retweetCount</strong></i>
                        <i class='fa-regular fa-heart like-button' data-tweet-id='$id'><strong> $likeCount</strong></i>
                    </div>
                </div>";
            }
        }
    }
    
    // for profile page
    public function loadPersoTweets() {
        $userId = $_SESSION["user_id"];
    
        $tweets = $this->query("
            (
                SELECT
                    t.tweet_id AS id,
                    u.username,
                    t.content,
                    t.created_at,
                    'tweet' AS type
                FROM Users u
                JOIN Tweets t ON t.user_id = u.user_id
                WHERE u.user_id = $userId
            )
            
            UNION
            
            (
                SELECT
                    t.tweet_id AS id,
                    u.username,
                    t.content,
                    r.created_at,
                    'retweet_tweet' AS type
                FROM Retweets r
                JOIN Tweets t ON r.tweet_id = t.tweet_id
                JOIN Users u ON t.user_id = u.user_id
                WHERE r.user_id = $userId
            )
    
            UNION
    
            (
                SELECT
                    c.id AS id,
                    u.username,
                    c.content,
                    r.created_at,
                    'retweet_comment' AS type
                FROM Retweets r
                JOIN Comments c ON r.comment_id = c.id
                JOIN Users u ON c.user_id = u.user_id
                WHERE r.user_id = $userId
            )
    
            ORDER BY created_at DESC;
        ");
    
        foreach ($tweets as $tweet) {
            $id = $tweet["id"];
            $username = $tweet["username"];
            $content = $tweet["content"];
            $created_at = $tweet["created_at"];
            $formatted_date = $this->timeElapsed($created_at);
            $type = $tweet["type"];
    
            $commentCount = $this->count("Comments", "WHERE parent_comment_id = $id");
            $likeCount = (new Likes())->countLikes($id);
            $retweetCount = (new Retweets())->countRetweets($id);
    
            // corresponding the type we print
            $retweetLabel = "";
            if ($type === "retweet_tweet") {
                $retweetLabel = "<p class='retweet-label'><i class='fa-solid fa-retweet'></i> Retweet d'un tweet</p>";
            } elseif ($type === "retweet_comment") {
                $retweetLabel = "<p class='retweet-label'><i class='fa-solid fa-retweet'></i> Retweet d'un commentaire</p>";
            }
    
            echo "
            <div class='tweet' id='$id'>
                <div class='tweet-header'>
                    $retweetLabel
                    <p class='tweet-username'><strong>@$username</strong></p>
                    <p class='tweet-date'>$formatted_date</p>
                </div>
                <p class='tweet-content'>$content</p>
                <div class='icon'>
                    <i class='fa-regular fa-comment'><strong> $commentCount</strong></i>
                    <i class='fa-solid fa-retweet retweet-button' data-tweet-id='$id'><strong> $retweetCount</strong></i>
                    <i class='fa-regular fa-heart like-button' data-tweet-id='$id'><strong> $likeCount</strong></i>
                </div>
            </div>";
        }
    }
    
}

if ($_SERVER["REQUEST_METHOD"] === "POST" &&
isset($data["tweetContent"])) {
    (new Tweets())->addTweet($data);
}
