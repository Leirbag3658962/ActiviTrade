<?php
session_start();
require_once "../../Modele/LienPDO.php";
$pdo = lienPDO();

$thread_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$thread = null;
if ($thread_id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM threads WHERE id = ?");
    $stmt->execute([$thread_id]);
    $thread = $stmt->fetch(PDO::FETCH_ASSOC);
}

$replies = [];
if ($thread_id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM replies WHERE thread_id = ? ORDER BY date_added ASC");
    $stmt->execute([$thread_id]);
    $replies = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Style/Forum.css">
    <link rel="stylesheet" href="../Style/Navbar.css">
    <link rel="stylesheet" href="../Style/Footer.css">
    <title>Détails du sujet</title>
</head>
<body>
<header id="navbar" class="navbar"></header>

<div class="main-content">
    <?php if ($thread): ?>
    <div class="thread-detail">
        
        <div class="post-info-container">
            <div class="post-info">
                <p><strong>Par:</strong> <?php echo htmlspecialchars($thread['username']); ?></p>
                <p><strong>Date:</strong> <?php echo htmlspecialchars($thread['date_added']); ?></p>
            </div>
        </div>
    
        <div class="title-context-container">
            <div class="title-container">
                <h2><?php echo htmlspecialchars($thread['title']); ?></h2>
                <h2>Pour les clubs de lecture, faut-il ramener du matériel ?</h2>
            </div>
        
            <div class="thread-context">
                <?php echo nl2br(htmlspecialchars($thread['context'])); ?>
            </div>
        </div>
    </div>

    <hr>

    <div class="replies">
        <h3>RE: <?php echo htmlspecialchars($thread['title']); ?></h3>
        <?php foreach ($replies as $reply): ?>
            <div class="reply">
                
                <div class="post-info-container">
                    <div class="post-info">
                        <p><strong>Par:</strong> <?php echo htmlspecialchars($reply['username']); ?></p>
                        <p><strong>Date:</strong> <?php echo htmlspecialchars($reply['date_added']); ?></p>
                    </div>
                </div>

                <div class="title-context-container">
                    <div class="thread-context">
                        <?php echo nl2br(htmlspecialchars($reply['content'])); ?>
                        <p>Pour les clubs de lecture, faut-il ramener du matériel ?</p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <button class="reply-button" onclick="toggleReplyForm('main')">Répondre</button>

    <div class="reply-form" id="reply-form-main" style="display: none;">
        <form action="submit-reply.php" method="POST">
            <input type="hidden" name="thread_id" value="<?php echo $thread['id']; ?>">
            <textarea name="reply_content" rows="4" cols="50" required placeholder="Écrivez votre réponse ici..."></textarea><br>
            <button type="submit">Envoyer</button>
        </form>
    </div>
    <?php else: ?>
        <p>Sujet non trouvé.</p>
    <?php endif; ?>
</div>

<footer id="footer" class="footer"></footer>

<script src="../Components/Navbar.js"></script>
<script>
    document.getElementById("navbar").innerHTML = Navbar();
</script>
<script src="../Components/Forum.js"></script>
<script src="../Components/NavbarAnim.js"></script>
<script src="../Components/Footer.js"></script>
</body>
</html>
