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

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Aucune activité spécifiée.");
}

$id = (int) $_GET['id'];
$sql = "SELECT * FROM activite WHERE idActivite = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$activite = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$activite) {
    die("Activité introuvable.");
}
if (isset($_POST['envoyer_commentaire'])) {
    $note = (int)$_POST['note'];
    $texte = trim($_POST['texte']);
    $idUtilisateur = $_SESSION['idUtilisateur'] ?? null; // adapte selon ton système de session

    if ($note >= 1 && $note <= 5 && !empty($texte)) {
        $sql = "INSERT INTO commentaire (idActivite, idUtilisateur, note, texte) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id, $idUtilisateur, $note, $texte]);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/Activite.css">
    <link rel="stylesheet" href="../style/Navbar2.css">
    <link rel="stylesheet" href="../style/Footer2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title><?= htmlspecialchars($activite['nomActivite']) ?></title>
</head>
<body>
    <header id="navbar" class="navbar">
        <?php echo Navbar2(); ?>
    </header>

    <div class="content">
        <div class="content-header">
            <h1><?= htmlspecialchars($activite['nomActivite']) ?></h1>
            <div class="cta-container">
                <a href="ReservationActivite.php?id=<?= $activite['idActivite'] ?>">
                    <button class="cta-button">Réserver</button>
                </a>
            </div>
        </div>

        <div class="images">
            <img src="../img/banner2.jpg" alt="Image de l'activité">
            <img src="../img/banner3.jpeg" alt="Image de l'activité">
            <img src="../img/banner4.jpeg" alt="Image de l'activité">
        </div>
        <div class="details1">
            <div class="details-item">
                <p><b>Date : </b><?= isset($activite['date']) ? htmlspecialchars($activite['date']) : '' ?></p>
                <p><b>Durée : </b><?= isset($activite['duree']) ? htmlspecialchars($activite['duree']) : '' ?></p>
            </div>
            <div class="details-item">
                <p><b>Accessibilité : </b><?= isset($activite['accessibilite']) ? htmlspecialchars($activite['accessibilite']) : '' ?></p>
                <p><b>Nombre max de membres : </b><?= isset($activite['nbMaxParticipants']) ? htmlspecialchars($activite['nbMaxParticipants']) : '' ?></p>
            </div>
            <div class="details-item">
                <p><b>Contact : </b><?= isset($activite['contact']) ? htmlspecialchars($activite['contact']) : '' ?></p>
                <p><b>Adresse : </b><?= isset($activite['adresse']) ? htmlspecialchars($activite['adresse']) : '' ?></p>
            </div>
        </div>

        <div class="details2">
            <div class="details-item">
                <p><b>Description : </b><?= isset($activite['description']) ? nl2br(htmlspecialchars($activite['description'])) : '' ?></p>
            </div>
        </div>

        <!-- Commentaires -->
        <section>
            <h2>Ajouter un commentaire</h2>
            <form method="POST">
                <label for="note">Note (1 à 5) :</label>
                <select name="note" id="note" required>
                    <option value="">Choisir une note</option>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?></option>
                    <?php endfor; ?>
                </select>

                <label for="texte">Commentaire :</label>
                <textarea name="texte" id="texte" rows="4" cols="50" required></textarea>

                <input type="submit" name="envoyer_commentaire" value="Envoyer">
            </form>
        </section>

        <?php
        $commentaires = [];

        try {
            $sql = "SELECT note, texte, dateCreation FROM commentaire WHERE idActivite = ? ORDER BY dateCreation DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des commentaires : " . $e->getMessage();
        }
        ?>

        <section>
            <h2>Commentaires</h2>
            <div class="commentaires-container" id="commentairesContainer">
                <?php
                if ($commentaires):
                    foreach ($commentaires as $commentaire):
                ?>
                    <div class="commentaire-item">
                        <div class="rating">
                            <?php 
                            // Affichage des étoiles pour la note 
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $commentaire['note']) {
                                    echo '<i class="fas fa-star"></i>';
                                } else {
                                    echo '<i class="far fa-star"></i>';
                                }
                            }
                            ?>
                        </div>
                        <p><?= nl2br(htmlspecialchars($commentaire['texte'])) ?></p>
                        <small>Posté le <?= $commentaire['dateCreation'] ?></small>
                    </div>
                <?php
                    endforeach;
                else:
                    echo "<p>Aucun commentaire pour cette activité.</p>";
                endif;
                ?>
            </div>
        </section>
    </div>
    
    <footer id="footer" class="footer"><?php echo Footer2(); ?></footer>
</body>
</html>