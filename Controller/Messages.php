<?php

class Messages extends MessagesModel
{
    // SEARCH USER AJAX

    public function searchUser() {
        $query = $_GET['query'] ?? '';
        if (empty($query)) {
            echo json_encode([]);
            exit;
        }
        $results = $this->searchUsersByName($query);
        echo json_encode($results);
        exit;
    }

    // POST

    public function handleRequest()
    {
        $action = $_POST['action'] ?? null;
        if ($action === 'send') {
            $this->send();
        }
    }

    // MESSAGE SEND
    public function send()
    {
        $sender = $_SESSION['username'];
        $recipient = $_POST['recipient'] ?? '';
        $content = $_POST['content'] ?? '';

        if ($recipient && $content) {
            $this->sendMessage($sender, $recipient, $content);
        }
        (new Redirect())->redirectPath('messages?contact=' . $recipient);
    }
}

$messages = new Messages();

if ($_SERVER["REQUEST_METHOD"] === "GET" &&
 isset($_GET['query'])) {
    $messages->searchUser();
} elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    $messages->handleRequest();
}
