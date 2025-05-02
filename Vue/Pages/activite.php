<link rel="stylesheet" href="style/navbar.css">
<link rel="stylesheet" href="style/activite.css">
<link rel="stylesheet" href="style/footer.css">
<?php
// Connexion à la base de données
$host = 'localhost';
$port = '3306'; 
$dbname = 'activitrade_demo';
$user = 'root';
$password = 'hello'; 

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $user, $password);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérifie qu'un ID est passé dans l'URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Aucune activité spécifiée.");
}

$id = (int) $_GET['id'];

// Requête SQL pour récupérer les infos de l’activité
$sql = "SELECT * FROM activite WHERE idActivite = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$activite = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$activite) {
    die("Activité introuvable.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/activite.css">
    <link rel="stylesheet" href="style/navbar.css">
    <link rel="stylesheet" href="style/footer.css">
    <title><?= htmlspecialchars($activite['nomActivite']) ?></title>
</head>
<body>
    <header id="navbar" class="navbar"></header>

    <div class="content">
        <h1><?= htmlspecialchars($activite['nomActivite']) ?></h1>

        <div class="cta-container">
            <button class="cta-button">Réserver</button>
        </div>

        <div class="images">
            <img src="img/activity.jpeg" alt="">
            <img src="img/activity.jpeg" alt="">
            <img src="img/activity.jpeg" alt="">
        </div>

        <div class="details1">
            <div class="details-item">
                <p><b>Date : </b><?= htmlspecialchars($activite['date']) ?></p>
                <p><b>Durée : </b><?= htmlspecialchars($activite['duree']) ?></p>
            </div>
            <div class="details-item">
                <p><b>Accessibilité : </b><?= htmlspecialchars($activite['accessibilite']) ?></p>
                <p><b>Nombre max de membres : </b><?= htmlspecialchars($activite['nbMaxParticipants']) ?></p>
            </div>
            <div class="details-item">
                <p><b>Contact : </b><?= htmlspecialchars($activite['contact']) ?></p>
                <p><b>Adresse : </b><?= htmlspecialchars($activite['adresse']) ?></p>
            </div>
        </div>

        <div class="details2">
            <div class="details-item">
                <p><b>Description : </b><?= nl2br(htmlspecialchars($activite['description'])) ?></p>
            </div>
            <div class="details-item">
                <p><b>Commentaires : </b><?= nl2br(htmlspecialchars($activite['commentaires'])) ?></p>
            </div>
        </div>
    </div>

    <footer id="footer" class="footer"></footer>

    <!-- Navbar & Footer -->
    <script src="../vue/templates/navbar.js"></script>
    <script>
        document.getElementById("navbar").innerHTML = Navbar2();
    </script>
    <script src="../vue/templates/footer.js"></script>
    <script>
        document.getElementById("footer").innerHTML = Footer2();
    </script>
</body>
</html>
