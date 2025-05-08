<?php
session_start();
// <<<<<<< Jingjing
// require_once "../../Modele/LienPDO.php";
// $pdo = lienPDO();

// $idForum = isset($_GET['id']) ? intval($_GET['id']) : 0;

// $forum = null;
// if ($idForum > 0) {
//     $stmt = $pdo->prepare("SELECT f.*, u.nom, u.prenom 
//                             FROM forum f
//                             JOIN utilisateur u ON f.idUser = u.idUtilisateur
//                             WHERE f.idForum = ?");
//     $stmt->execute([$idForum]);
//     $forum = $stmt->fetch(PDO::FETCH_ASSOC);
// }

// $replies = [];
// if ($idForum > 0) {

//     $stmt = $pdo->prepare("SELECT f.*, u.nom, u.prenom
//                            FROM forum f
//                            JOIN utilisateur u ON f.idUser = u.idUtilisateur
//                            WHERE f.idParent = ? 
//                            ORDER BY f.date ASC");
//     $stmt->execute([$idForum]);
//     $replies = $stmt->fetchAll(PDO::FETCH_ASSOC);
// }

// $idForumPost = isset($_POST['idForum']) ? intval($_POST['idForum']) : 0;
// $contenu = isset($_POST['contenu']) ? trim($_POST['contenu']) : '';

// if ($idForumPost > 0 && !empty($contenu)) {

//     $idUser = $_SESSION['idUser']; 
//     $stmt = $pdo->prepare("INSERT INTO forum (idUser, contenu, date, idParent) 
//                            VALUES (?, ?, NOW(), ?)");
//     $stmt->execute([$idUser, $contenu, $idForum]);

//     header("Location: ForumDetail.php?id=$idForum");
//     exit();
// } else if ($idForumPost > 0) {
//     echo "Erreur: Le contenu de la réponse est vide.";
// }
// =======
require_once "../../ModeleB/LienPDO.php";
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
<!-- <<<<<<< Jingjing
    <?php //if ($forum): ?>
======= -->
    <?php if ($thread): ?>
<!-- >>>>>>> main -->
    <div class="thread-detail">
        
        <div class="post-info-container">
            <div class="post-info">
<!-- <<<<<<< Jingjing
                <p><strong>Par:</strong> <?php //echo htmlspecialchars($forum['prenom'] . ' ' . $forum['nom']); ?></p>
                <p><strong>Date:</strong> <?php //echo htmlspecialchars($forum['date']); ?></p>
======= -->
                <p><strong>Par:</strong> <?php echo htmlspecialchars($thread['username']); ?></p>
                <p><strong>Date:</strong> <?php echo htmlspecialchars($thread['date_added']); ?></p>
<!-- >>>>>>> main -->
            </div>
        </div>
    
        <div class="title-context-container">
            <div class="title-container">
<!-- <<<<<<< Jingjing
                <h2><?php //echo htmlspecialchars($forum['theme']); ?></h2>
            </div>
        
            <div class="thread-context">
                <?php //echo nl2br(htmlspecialchars($forum['contenu'])); ?>
======= -->
                <h2><?php echo htmlspecialchars($thread['title']); ?></h2>
                <h2>Pour les clubs de lecture, faut-il ramener du matériel ?</h2>
            </div>
        
            <div class="thread-context">
                <?php echo nl2br(htmlspecialchars($thread['context'])); ?>
<!-- >>>>>>> main -->
            </div>
        </div>
    </div>

    <hr>

    <div class="replies">
<!-- <<<<<<< Jingjing
        <h3>RE: <?php //echo htmlspecialchars($forum['theme']); ?></h3><br>
        <?php //foreach ($replies as $reply): ?>
            <div class="reply">
                <div class="post-info-container">
                    <div class="post-info">
                        <p><strong>Par:</strong> <?php //echo htmlspecialchars($reply['prenom'] . ' ' . $reply['nom']); ?></p>
                        <p><strong>Date:</strong> <?php //echo htmlspecialchars($reply['date']); ?></p>
======= -->
        <h3>RE: <?php echo htmlspecialchars($thread['title']); ?></h3>
        <?php foreach ($replies as $reply): ?>
            <div class="reply">
                
                <div class="post-info-container">
                    <div class="post-info">
                        <p><strong>Par:</strong> <?php echo htmlspecialchars($reply['username']); ?></p>
                        <p><strong>Date:</strong> <?php echo htmlspecialchars($reply['date_added']); ?></p>
<!-- >>>>>>> main -->
                    </div>
                </div>

                <div class="title-context-container">
                    <div class="thread-context">
<!-- <<<<<<< Jingjing
                        <?php //echo nl2br(htmlspecialchars($reply['contenu'])); ?>
======= -->
                        <?php echo nl2br(htmlspecialchars($reply['content'])); ?>
                        <p>Pour les clubs de lecture, faut-il ramener du matériel ?</p>
<!-- >>>>>>> main -->
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <button class="reply-button" onclick="toggleReplyForm('main')">Répondre</button>

    <div class="reply-form" id="reply-form-main" style="display: none;">
<!-- <<<<<<< Jingjing
        <form action="ForumDetail.php?id=<?php //echo $idForum; ?>" method="POST">
            <input type="hidden" name="idForum" value="<?php //echo $forum['idForum']; ?>">
            <textarea name="contenu" rows="4" cols="50" required placeholder="Écrivez votre réponse ici..."></textarea><br>
======= -->
        <form action="submit-reply.php" method="POST">
            <input type="hidden" name="thread_id" value="<?php echo $thread['id']; ?>">
            <textarea name="reply_content" rows="4" cols="50" required placeholder="Écrivez votre réponse ici..."></textarea><br>
<!-- >>>>>>> main -->
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
<!-- <<<<<<< Jingjing
<script src="../Components/ForumAnswer.js"></script>
======= -->
<script src="../Components/Forum.js"></script>
<!-- >>>>>>> main -->
<script src="../Components/NavbarAnim.js"></script>
<script src="../Components/Footer.js"></script>
</body>
</html>
