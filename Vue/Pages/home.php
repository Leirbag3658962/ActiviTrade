<link rel="stylesheet" href="style/navbar.css">
<link rel="stylesheet" href="style/footer.css">
<link rel="stylesheet" href="style/home.css">


<?php
// Connexion à la base de données
$host = 'localhost';
$port = '3306'; // important ! 3306 probably
$dbname = 'activitrade_demo';
$user = 'root';
$password = 'hello'; // change selon ta config locale

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $user, $password);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupérer les activités
$sql = "SELECT * FROM activite";
$stmt = $pdo->query($sql);
$activites = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/home.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ActiviTrade</title>
</head>
<body>
    <header></header>

    <div class="banner">
        <h1>ActiviTrade</h1>
        <p>Proposez des activités, et participez à celles qui vous sont proposées.</p>
    </div>

    <div class="content">
        <section class="activities">
            <h2>Activités</h2>
            <p>Découvrez tous les événements ici, ou sélectionnez l'événement qui vous intéresse par type et consultez les informations détaillées.</p>
            
            <div class="tags">
                <button class="tag active">Nouveau</button>
                <button class="tag">Sport</button>
                <button class="tag">Cuisine</button>
                <button class="tag">Culture</button>
            </div>
            
            <div class="activities-grid">
                <?php foreach ($activites as $activite): ?>
                    <div class="activity">
                        <img src="img/activity.jpeg" alt="<?= htmlspecialchars($activite['nomActivite']) ?>">
                        <h3><?= htmlspecialchars($activite['nomActivite']) ?></h3>
                        <p>Coût d'inscription: <?= $activite['prix'] == 0 ? 'gratuit' : $activite['prix'] . '€' ?></p>
                        <p>Description: <?= htmlspecialchars($activite['description']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="slider-dots">
                <span class="dot active"></span>
                <span class="dot"></span>
                <span class="dot"></span>
                <span class="dot"></span>
            </div>
        </section>

        <section class="about">
            <h2>À propos</h2>
            <div class="about-grid">
                <div class="about-item">
                    <h3>Autogestion</h3>
                    <p>Suivez et participez aux activités qui vous intéressent, choisissez celles que vous voulez joindre.</p>
                </div>
                <div class="about-item">
                    <h3>Flexibilité</h3>
                    <p>Créez votre propre événement et invitez votre famille et vos amis à se joindre à vous.</p>
                </div>
                <div class="about-item">
                    <h3>Sécurité</h3>
                    <p>Protégez vos informations en suivant nos normes européennes de cybersécurité.</p>
                </div>
                <div class="about-item">
                    <h3>Intéressant</h3>
                    <p>Trouvez de nombreux amis partageant les mêmes idées.</p>
                </div>
            </div>
        </section>

        <div class="cta-container">
            <button class="cta-button">Créer votre événement</button>
        </div>
    </div>
</body>
</html>
