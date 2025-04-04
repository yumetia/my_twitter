<?php
$model = new MessagesModel();
$currentUser = $_SESSION['username'];
$contact = $_GET['contact'] ?? '';
$messages = [];
if ($contact) {
    $messages = $model->getConversation($currentUser, $contact);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Messages</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="View/css/global/skeleton.css">

    <link rel="stylesheet" href="View/css/global/header.css">
    <link rel="stylesheet" href="View/css/global/footer.css">
    <link rel="stylesheet" href="View/css/pages/messages.css">

    <script src="View/js/global/logout.js" defer></script>
    <script src="View/js/global/theme.js" defer></script>
    <script src="View/js/pages/searchContact.js" defer></script>
</head>
<body>
<div class="container">
    <?= (new HtmlHelper())->renderHeader();?>

    <div class="four columns channels-col">
        <h2>Messages</h2>
        <div class="search-contact-wrapper">
            <input type="text" id="searchContact" placeholder="Rechercher un contact">
            <ul id="searchResults"></ul>
        </div>

        <!-- getting the current contacts  -->
        <ul class="channel-list">
            <?php
            $contacts = $model->getConversations($currentUser);
            foreach ($contacts as $c): ?>
                <li class="contact-item"><a href="messages?contact=<?= urlencode($c) ?>"><?= htmlspecialchars($c) ?></a></li>
            <?php endforeach; ?>
        </ul>

    </div>

    <div class="four columns conversation-col">
        <div class="conversation-wrapper">
            <?php if ($contact): ?>
                <h2><?=$contact?></h2>
                <?php foreach ($messages as $msg):
                    $class = ($msg['sender_username'] === $currentUser) ? 'sent' : 'received';
                    $author = ($class === 'sent') ? 'Moi' : $msg['sender_username']; ?>
                    <div class="message <?= $class ?>">
                        <div class="message-author"><?= htmlspecialchars($author) ?></div>
                        <div class="message-text"><?= htmlspecialchars($msg['content']) ?></div>
                        <div class="message-created_at"><?= htmlspecialchars($msg['formatted_date']) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun contact sélectionné.</p>
            <?php endif; ?>
        </div>
            <!-- Send button -->

        <?php if ($contact): ?>
        <form class="message-form" action="Messages" method="post">
            <input type="hidden" name="recipient" value="<?= htmlspecialchars($contact) ?>">
            <input type="hidden" name="action" value="send">
            <input type="text" name="content" placeholder="Écrivez un message...">
            <button type="submit">Envoyer</button>
        </form>
        <?php endif; ?>
    </div>
</div>
<?= (new HtmlHelper())->renderFooter();?>
</body>
</html>
