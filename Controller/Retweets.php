<?php

$data = json_decode(file_get_contents("php://input"), true);

class Retweets extends GenericModel {
    public function toggleRetweet($data) {
        $userId = $_SESSION["user_id"];
        $tweetId = $data["tweet_id"] ?? null;
        $commentId = $data["comment_id"] ?? null;
        $retweetMessage = trim($data["retweet_message"] ?? '');

        if (!$tweetId && !$commentId) {
            echo json_encode(["error" => "Tweet ou commentaire non spécifié."]);
            return;
        }

        if ($tweetId && !$commentId) {
            // if msg added with retweet,  update Tweets and Retweets tables
            if (!empty($retweetMessage)) {
                $created_at = date("Y-m-d H:i:s");
                $newTweetId = $this->addRow([
                    "table"      => "Tweets",
                    "user_id"    => $userId,
                    "content"    => $retweetMessage,
                    "created_at" => $created_at
                ]);
                $this->addRow([
                    "table"   => "Retweets",
                    "user_id" => $userId,
                    "tweet_id"=> $tweetId
                ]);
                echo json_encode(["status" => "quote_retweeted", "new_tweet_id" => $newTweetId]);
                return;
            }
            $existingRetweet = $this->query("
                SELECT retweet_id FROM Retweets
                WHERE user_id = $userId AND tweet_id = $tweetId
            ");
            if ($existingRetweet) {
                $eRetweet = $existingRetweet[0]["retweet_id"];
                $this->query("DELETE FROM Retweets WHERE retweet_id = $eRetweet");
                echo json_encode(["status" => "unretweeted"]);
            } else {
                $this->addRow([
                    "table"   => "Retweets",
                    "user_id" => $userId,
                    "tweet_id"=> $tweetId
                ]);
                echo json_encode(["status" => "retweeted"]);
            }
        } elseif ($commentId) {
            // if msg added with retweet,  update Tweets and Retweets tables
            if (!empty($retweetMessage)) {
                $created_at = date("Y-m-d H:i:s");
                $newTweetId = $this->addRow([
                    "table"      => "Tweets",
                    "user_id"    => $userId,
                    "content"    => $retweetMessage,
                    "created_at" => $created_at
                ]);
                $this->addRow([
                    "table"     => "Retweets",
                    "user_id"   => $userId,
                    "comment_id"=> $commentId
                ]);
                echo json_encode(["status" => "quote_retweeted", "new_tweet_id" => $newTweetId]);
                return;
            }
            $existingRetweet = $this->query("
                SELECT retweet_id FROM Retweets
                WHERE user_id = $userId AND comment_id = $commentId
            ");
            if ($existingRetweet) {
                $eRetweet = $existingRetweet[0]["retweet_id"];
                $this->query("DELETE FROM Retweets WHERE retweet_id = $eRetweet");
                echo json_encode(["status" => "unretweeted"]);
            } else {
                $this->addRow([
                    "table"     => "Retweets",
                    "user_id"   => $userId,
                    "comment_id"=> $commentId
                ]);
                echo json_encode(["status" => "retweeted"]);
            }
        }
    }
    public function countRetweets($tweetId = null, $commentId = null) {
        if ($tweetId) {
            $condition = "tweet_id = $tweetId";
        } elseif ($commentId) {
            $condition = "comment_id = $commentId";
        } else {
            echo json_encode(["error" => "Aucun identifiant spécifié."]);
            return;
        }
        $result = $this->query("SELECT COUNT(*) as total FROM Retweets WHERE $condition");
        return $result[0]["total"] ?? 0;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data["tweet_id"]) || isset($data["comment_id"])) {
        (new Retweets())->toggleRetweet($data);
    }
}
