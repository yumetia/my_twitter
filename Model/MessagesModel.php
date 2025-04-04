<?php

class MessagesModel extends GenericModel {
    // User
    public function searchUsersByName($query) {
        $sql = "SELECT username
                FROM Users
                WHERE username LIKE :query
                ORDER BY username ASC
                LIMIT 10";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue(':query', $query . '%');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    // Message
    public function getConversations($username) {
        $sql = "SELECT DISTINCT
                   IF(sender_username = :user, recipient_username, sender_username) AS contact
                FROM Messages
                WHERE sender_username = :user
                   OR recipient_username = :user
                ORDER BY contact ASC";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue(':user', $username);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getConversation($user, $contact) {
        $sql = "SELECT *,
                DATE_FORMAT(created_at, '%d/%m/%Y %H:%i:%s') AS formatted_date
                FROM Messages
                WHERE (sender_username = :user AND recipient_username = :contact)
                   OR (sender_username = :contact AND recipient_username = :user)
                ORDER BY created_at ASC";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue(':user', $user);
        $stmt->bindValue(':contact', $contact);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function sendMessage($sender, $recipient, $content) {
        $sql = "INSERT INTO Messages (sender_username, content, recipient_username, created_at, is_read)
                VALUES (:sender, :content, :recipient, NOW(), 0)";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue(':sender', $sender);
        $stmt->bindValue(':content', $content);
        $stmt->bindValue(':recipient', $recipient);
        $stmt->execute();
    }
}
