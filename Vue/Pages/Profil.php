<?php
$host = 'localhost';
$port = '3306'; 
$dbname = 'activitrade';
$user = 'root';
$password = ''; 

session_start();
require_once(__DIR__ . '../../../Modele/Database.php');
require_once(__DIR__ . '../../Components/Navbar2.php');
require_once(__DIR__ . '../../Components/Footer2.php');

$pdo = getPDO();

// Récupérer l'ID de l'utilisateur depuis l'URL
$idUtilisateur = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Si aucun ID n'est spécifié et que l'utilisateur est connecté, afficher son propre profil
if ($idUtilisateur === 0 && isset($_SESSION['user_id'])) {
    $idUtilisateur = $_SESSION['user_id'];
}

// Vérifier si l'ID est valide
if ($idUtilisateur <= 0) {
    // Rediriger vers une page d'erreur ou la page d'accueil
    header('Location: ../index.php');
    exit;
}

// Récupérer les informations de l'utilisateur
$stmtUser = $pdo->prepare("SELECT * FROM utilisateur WHERE idUtilisateur = :idUtilisateur");
$stmtUser->bindParam(':idUtilisateur', $idUtilisateur, PDO::PARAM_INT);
$stmtUser->execute();
$user = $stmtUser->fetch(PDO::FETCH_ASSOC);

// Vérifier si l'utilisateur existe
if (!$user) {
    // Utilisateur non trouvé, rediriger
    header('Location: ../index.php');
    exit;
}

// Récupérer les activités créées par l'utilisateur
$stmtActivites = $pdo->prepare("SELECT * FROM activite WHERE idCreateur = :idUtilisateur");
$stmtActivites->bindParam(':idUtilisateur', $idUtilisateur, PDO::PARAM_INT);
$stmtActivites->execute();
$activites = $stmtActivites->fetchAll(PDO::FETCH_ASSOC);

// Déterminer le chemin de la photo de profil
$photoProfil = !empty($user['photoProfil']) ? $user['photoProfil'] : '../img/pfp.jpeg';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></title>
    <link rel="stylesheet" href="../style/Profil.css">
    <link rel="stylesheet" href="../style/Navbar2.css">
    <link rel="stylesheet" href="../style/Footer2.css">
</head>
<body>
    <header id="navbar" class="navbar">
        <?php echo Navbar2(); ?>
    </header>    
    <div class="container profile-container">
        <div class="user-profile">
            <img src="<?= htmlspecialchars($photoProfil) ?>" alt="Photo de profil" class="profile-picture">
            <h2><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></h2>
            
            <?php if (!empty($user['telephone'])): ?>
                <h3>Contact: <?= htmlspecialchars($user['telephone']) ?></h3>
            <?php endif; ?>
            
            <h3>Email: <?= htmlspecialchars($user['email']) ?></h3>
            
            <?php if (!empty($user['ville']) ): ?>
                <h3><?= htmlspecialchars($user['ville'] ?? '') ?></h3>
            <?php endif; ?>
            
            <?php if (!empty($user['description'])): ?>
                <h3>Description: <?= htmlspecialchars($user['description']) ?></h3>
            <?php endif; ?>
        </div>
        
        <div class="user-activities">
            <h3>Activités créées</h3>
            
            <?php if (count($activites) > 0): ?>
                <div class="activities-list">
                    <?php foreach ($activites as $activite): ?>
                        <div class="activity-card">
                            <h4><?= htmlspecialchars($activite['nomActivite']) ?></h4>
                            <p><?= htmlspecialchars(substr($activite['description'], 0, 100)) . (strlen($activite['description']) > 100 ? '...' : '') ?></p>
                            <p>Date: <?= htmlspecialchars($activite['date']) ?></p>
                            <a href="activite.php?id=<?= $activite['idActivite'] ?>">Voir les détails</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Cet utilisateur n'a pas encore créé d'activités.</p>
            <?php endif; ?>
        </div>
    </div>
    <footer id="footer" class="footer"><?php echo Footer2(); ?></footer>

</body>
</html>