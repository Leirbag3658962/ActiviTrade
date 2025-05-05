<?php
session_start();
require_once "../../Modele/LienPDO.php";
$pdo = lienPDO();

$sql = "SELECT threads.id, threads.title, threads.date_added, users.username 
        FROM threads 
        JOIN users ON threads.user_id = users.id
        ORDER BY threads.date_added DESC";  
$stmt = $pdo->prepare($sql);
$stmt->execute();
$threads = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Style/Forum.css">
    <link rel="stylesheet" href="../Style/Navbar.css">
    <link rel="stylesheet" href="../Style/Footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Forum</title>
</head>
<body>
<header id="navbar" class="navbar"></header>

<div class="main-content">
    <div class="forum-container">
        <br>
        <h1>Forum</h1><br>
        <div class="forum-box">
            <?php foreach ($threads as $thread): ?>
                <div class="context">
                    <div class="context-title">
                        <a href=>Pour les clubs de lecture, faut-il ramener du matériel ?</a>
                        <a href="thread-detail.php?id=<?php echo $thread['id']; ?>"><?php echo htmlspecialchars($thread['title']); ?></a>
                    </div>
                    <div class="post-info">
                        <p><strong>Par:</strong> <?php echo htmlspecialchars($thread['username']); ?></p>
                        <p><strong>Le:</strong> <?php echo htmlspecialchars($thread['date_added']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<br><br>
<footer id="footer" class="footer"></footer>

<script src="../Components/Navbar.js"></script>
<script>
    document.getElementById("navbar").innerHTML = Navbar();
</script>
<script src="../Components/NavbarAnim.js"></script>
<script src="../Components/Footer.js"></script>
</body>
</html>
