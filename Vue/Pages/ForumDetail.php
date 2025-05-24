<?php
session_start();
require_once(__DIR__ . '../../../Modele/Database.php');
require_once(__DIR__ . '../../Components/Navbar2.php');
$pdo = getPDO();

//$_SESSION['idUser'] = 2;

$idForum = isset($_GET['id']) ? intval($_GET['id']) : 0;

$forum = null;
if ($idForum > 0) {
    $stmt = $pdo->prepare("SELECT f.*, u.nom, u.prenom 
                            FROM forum f
                            JOIN utilisateur u ON f.idUser = u.idUtilisateur
                            WHERE f.idForum = ?");
    $stmt->execute([$idForum]);
    $forum = $stmt->fetch(PDO::FETCH_ASSOC);
}

$replies = [];
if ($idForum > 0) {

    $stmt = $pdo->prepare("SELECT f.*, u.nom, u.prenom
                           FROM forum f
                           JOIN utilisateur u ON f.idUser = u.idUtilisateur
                           WHERE f.idParent = ? 
                           ORDER BY f.date ASC");
    $stmt->execute([$idForum]);
    $replies = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$idForumPost = isset($_POST['idForum']) ? intval($_POST['idForum']) : 0;
$contenu = isset($_POST['contenu']) ? trim($_POST['contenu']) : '';

if ($idForumPost > 0 && !empty($contenu)) {
    if (!isset($_SESSION['idUser'])) {
        header("Location: LogIn.php");
        exit;
    }

    $idUser = $_SESSION['idUser']; 
    $stmt = $pdo->prepare("INSERT INTO forum (idUser, contenu, date, idParent) 
                           VALUES (?, ?, NOW(), ?)");
    $stmt->execute([$idUser, $contenu, $idForum]);

    header("Location: ForumDetail.php?id=$idForum");
    exit();
} else if ($idForumPost > 0) {
    echo "Erreur: Le contenu de la réponse est vide.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Style/Forum.css">
    <link rel="stylesheet" href="../Style/navbar2.css">
    <link rel="stylesheet" href="../Style/footer2.css">
    <title>Détails du sujet</title>
</head>
<body>
<header id="navbar" class="navbar">
    <?php echo Navbar2(); ?>
</header>

<div class="main-content">
    <?php if ($forum): ?>
    <div class="thread-detail">
        
        <div class="post-info-container">
            <div class="post-info">
                <p><strong>Par:</strong> <?php echo htmlspecialchars($forum['prenom'] . ' ' . $forum['nom']); ?></p>
                <p><strong>Date:</strong> <?php echo htmlspecialchars($forum['date']); ?></p>
            </div>
        </div>
    
        <div class="title-context-container">
            <div class="title-container">
                <h2><?php echo htmlspecialchars($forum['theme']); ?></h2>
            </div>
        
            <div class="thread-context">
                <?php echo nl2br(htmlspecialchars($forum['contenu'])); ?>
            </div>
        </div>
    </div>

    <hr>

    <div class="replies">
        <h3>RE: <?php echo htmlspecialchars($forum['theme']); ?></h3><br>
        <?php foreach ($replies as $reply): ?>
            <div class="reply">
                <div class="post-info-container">
                    <div class="post-info">
                        <p><strong>Par:</strong> <?php echo htmlspecialchars($reply['prenom'] . ' ' . $reply['nom']); ?></p>
                        <p><strong>Date:</strong> <?php echo htmlspecialchars($reply['date']); ?></p>
                    </div>
                </div>

                <div class="title-context-container">
                    <div class="thread-context">
                        <?php echo nl2br(htmlspecialchars($reply['contenu'])); ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php $isLoggedIn = isset($_SESSION['idUser']); ?>


    <button class="reply-button">
        Répondre
    </button>

    <?php if ($isLoggedIn): ?>
    <div class="reply-form" id="reply-form-main" style="display: none;">
        <form action="ForumDetail.php?id=<?php echo $idForum; ?>" method="POST">
            <input type="hidden" name="idForum" value="<?php echo $forum['idForum']; ?>">
            <textarea name="contenu" rows="4" cols="50" required placeholder="Écrivez votre réponse ici..."></textarea><br>
            <button type="submit">Envoyer</button>
        </form>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>

<footer id="footer" class="footer"></footer>

</body>
<script src="../Components/NavbarAnim.js"></script>
<script src="../Components/DragAndDrop.js"></script>
<script src="../Components/Footer2.js"></script>
<script>
    window.isLoggedIn = <?= json_encode($isLoggedIn); ?>;
</script>
<script src="../Components/ForumAnswer.js"></script>
<script>
	document.getElementById("footer").innerHTML = Footer2();
</script>
</html>
