<?php
session_start();

require_once(__DIR__ . '../../../Modele/Database.php');
require_once(__DIR__ . '../../Components/Navbar2.php');
$pdo = getPDO(); 

$idUser = $_SESSION['idUser'] ?? null;

$sql = "SELECT forum.idForum, forum.theme, forum.date, utilisateur.nom, utilisateur.prenom 
        FROM forum 
        JOIN utilisateur ON forum.idUser = utilisateur.idUtilisateur
        WHERE forum.idParent IS NULL
        ORDER BY forum.date DESC";  
$stmt = $pdo->prepare($sql);
$stmt->execute();
$forums = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Style/Forum.css">
    <link rel="stylesheet" href="../Style/navbar2.css">
    <link rel="stylesheet" href="../Style/footer2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Forum</title>
</head>
<body>
<header id="navbar" class="navbar">
    <?php echo Navbar2(); ?>
</header>

<div class="main-content">
    <div class="forum-container">
        <br>
        <h1>Forum</h1><br>
        <div class="forum-box">
            <?php foreach ($forums as $forum): ?>
                <div class="context">
                    <div class="context-title">
                        <a href="ForumDetail.php?id=<?php echo $forum['idForum']; ?>">
                            <?php echo htmlspecialchars($forum['theme']); ?>
                        </a>
                    </div>
                    <div class="post-info">
                        <p><strong>Par:</strong> <?php echo htmlspecialchars($forum['prenom'] . ' ' . $forum['nom']); ?></p>
                        <p><strong>Date:</strong> <?php echo htmlspecialchars($forum['date']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<a href="CreationForum.php" class="floating-button">
    <i class="fas fa-plus"></i> Créer un sujet
</a>
<br><br>
<footer id="footer" class="footer"></footer>
</body>

<!-- <script src="../Components/Navbar2.js"></script>
<script>
	document.getElementById("navbar").innerHTML = Navbar2();
</script> -->
<script src="../Components/NavbarAnim.js"></script>
<script src="../Components/DragAndDrop.js"></script>
<script src="../Components/Footer2.js"></script>
<script>
	document.getElementById("footer").innerHTML = Footer2();
</script>
</html>
