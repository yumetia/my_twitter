<?php

class Likes extends GenericModel
{
    public function toggleLike($data)
    {
        $userId = $_SESSION["user_id"];
        $tweetId = $data["tweet_id"] ?? null;
        $commentId = $data["comment_id"] ?? null;

        if (!$tweetId && !$commentId) {
            echo json_encode(["error" => "Tweet ou commentaire non spécifié."]);
            return;
        }

        // check if the like exists

        $existingLike = $this->query("
            SELECT like_id
            FROM Likes
            WHERE user_id = $userId AND
            tweet_id " . ($tweetId ? "= $tweetId" : "IS NULL") . "
            AND comment_id " . ($commentId ? "= $commentId" : "IS NULL") . "
        ");

        if ($existingLike) {
            // unlike
            $like = $existingLike[0]["like_id"];
            $this->query("DELETE FROM Likes WHERE like_id = $like");
            echo json_encode(["status" => "unliked"]);
        } else {
            // like
            $this->addRow([
                "table" => "Likes",
                "user_id" => $userId,
                "tweet_id" => $tweetId,
                "comment_id" => $commentId
            ]);
            echo json_encode(["status" => "liked"]);
        }
    }

    public function countLikes($tweetId = null, $commentId = null)
    {
        $condition = $tweetId ? "tweet_id = $tweetId" : "comment_id = $commentId";

        $result = $this->query("SELECT COUNT(*) as total FROM Likes WHERE $condition");
        return $result[0]["total"] ?? 0;
    }

    public function userHasLiked($userId, $tweetId = null, $commentId = null)
    {
        $condition = $tweetId ? "tweet_id = $tweetId" : "comment_id = $commentId";

        $result = $this->query("SELECT like_id FROM Likes WHERE user_id = $userId AND $condition");
        return !empty($result);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data["tweet_id"]) || isset($data["comment_id"])) {
        (new Likes())->toggleLike($data);
    }
}
