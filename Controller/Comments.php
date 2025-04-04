<?php
$data = json_decode(file_get_contents("php://input"),true);


class Comments extends Tweets
{
    public function saveTweetInfos(){
        global $data;
        echo json_encode(["success" => 200]);
        $_SESSION["tweet_id"]       = $data["tweetInfos"]["tweetId"];
        $_SESSION["tweet_username"] = $data["tweetInfos"]["username"];
        $_SESSION["tweet_date"]     = $data["tweetInfos"]["date"];
        $_SESSION["tweet_content"]  = $data["tweetInfos"]["content"];
    }

    public function saveCommentInfos(){
        global $data;
        echo json_encode(["success" => 200]);
        $_SESSION["comment_id"]       = $data["commentInfos"]["commentId"];
        $_SESSION["comment_username"] = $data["commentInfos"]["username"];
        $_SESSION["comment_date"]     = $data["commentInfos"]["date"];
        $_SESSION["comment_content"]  = $data["commentInfos"]["content"];
    }

    public function addComment($data){
        $commentContent = isset($data["commentContent2"]) ? $data["commentContent2"] : ($data["commentContent"] ?? "");
        $created_at = date("Y-m-d H:i:s");

        $row = [
            "table"      => "Comments",
            "tweet_id"   => $_SESSION["tweet_id"],
            "user_id"    => $_SESSION["user_id"],
            "content"    => $commentContent,
            "created_at" => $created_at
        ];

        if(isset($data["commentContent2"])){
            $data["commentContent2"];
            // answer(comment) add to the comment
            $row["parent_comment_id"] = intval($_SESSION["comment_id"]);
        }

        $this->addRow($row);
        echo json_encode(["commentContent" => $commentContent]);
    }

    public function loadComments($reply = false)
    {
        $tweetId = $_SESSION["tweet_id"];
        $condition = $reply ? "c.parent_comment_id = " . intval($_SESSION["comment_id"]) : "c.parent_comment_id IS NULL";

        $comments = $this->query("
            SELECT u.username, c.created_at, c.content, c.id
            FROM Comments c
            JOIN Tweets t ON c.tweet_id = t.tweet_id
            JOIN Users u ON c.user_id = u.user_id
            WHERE c.tweet_id = $tweetId AND $condition
            ORDER BY c.created_at DESC
        ");

        if ($comments) {
            // comment class add to make it easier for the js

            $additionalClass = " comment";
            foreach ($comments as $comment) {
                $id = $comment["id"];
                $commentCount = $this->count("Comments", "WHERE parent_comment_id = $id");
                $likeCount = (new Likes())->countLikes(null, $id);
                $retweetCount = (new Retweets())->countRetweets($id);

                $username = $comment["username"];
                $content = $comment["content"];
                $created_at = $comment["created_at"];
                $formatted_date = $this->timeElapsed($created_at);

                echo "
                <div class='tweet{$additionalClass}' id='$id'>
                    <div class='tweet-header'>
                        <p class='tweet-username'><strong>@$username</strong></p>
                        <p class='tweet-date' style='font-size:smaller;'>$formatted_date</p>
                    </div>
                    <p class='tweet-content'>$content</p>
                    <div class='icon'>
                        <i class='fa-regular fa-comment'><strong> $commentCount</strong></i>
                        <i class='fa-solid fa-retweet retweet-button' data-comment-id='$id'><strong> $retweetCount</strong></i>
                        <i class='fa-regular fa-heart like-button' data-comment-id='$id'><strong> $likeCount</strong></i>
                    </div>
                </div>";
            }
        } else {
            echo "<p id='no-response' style='text-align:center;'>Aucune reponse.</p>";
        }
    }


}

if($_SERVER["REQUEST_METHOD"] === "POST") {
    global $data;
    if(isset($data["tweetInfos"])){
        (new Comments())->saveTweetInfos();
    } elseif(isset($data["commentInfos"])){
        (new Comments())->saveCommentInfos();
    } elseif(isset($data["commentContent"]) || isset($data["commentContent2"])){
        (new Comments())->addComment($data);
    }
}
