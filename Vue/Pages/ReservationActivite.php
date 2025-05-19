<?php
ob_start();
session_start();

require_once(__DIR__ . '../../../Modele/Database.php');
require_once(__DIR__ . '../../Components/Navbar2.php');
$pdo = getPDO(); 


if (!isset($_SESSION['idUser'])) {
    header("Location: LogIn.php");
    exit;
}

$idUser = $_SESSION['idUser']; 

if (isset($_GET['idActivite'])) {
   $_SESSION['idActivite'] = (int)$_GET['idActivite'];
}

$idActivite = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$nomActivite = "Nom de l'activité";

if ($idActivite > 0) {
    $stmt = $pdo->prepare("SELECT nomActivite FROM activite WHERE idActivite = ?");
    $stmt->execute([$idActivite]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $nomActivite = $result['nomActivite'];
    } else {
        $nomActivite = "Activité inconnue";
    }
}

$participants = [];
$participant_count = 0;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $age = htmlspecialchars($_POST['age']);

    if (!empty($nom) && !empty($prenom) && !empty($age) && !empty($idActivite)) {
        $stmt = $pdo->prepare("INSERT INTO reservation (nom, prenom, age, idUser, idActivite) VALUES (:nom, :prenom, :age, :idUser, :idActivite)");
        $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':age' => $age,
            ':idUser' => $idUser,  
            ':idActivite' => $idActivite  
        ]);
    }
    
}

$stmt = $pdo->prepare("SELECT * FROM reservation WHERE idUser = :idUser AND idActivite = :idActivite ORDER BY date DESC");
$stmt->execute([
    ':idUser' => $idUser,
    ':idActivite' => $idActivite
]);
$participants = $stmt->fetchAll(PDO::FETCH_ASSOC);
$participant_count = count($participants);

ob_end_flush(); 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Style/Navbar2.css">
    <link rel="stylesheet" href="../Style/Footer2.css">
    <link rel="stylesheet" href="../Style/ReservationActivite.css">
    <title>ReservationActivite</title>
</head>

<body>
<header id="navbar" class="navbar">
    <?php echo Navbar2(); ?>
</header>
<br><br>
<h1>Réservation: <?php echo htmlspecialchars($nomActivite); ?></h1>
<div class="container">
    <div class="boxform">
        <h2>Participant</h2>
        <form method="post">
            <label for="nom">Nom</label><br>
            <input type="text" id="nom" name="nom" placeholder="Smith" required><br>
            <label for="prenom">Prénom</label><br>
            <input type="text" id="prenom" name="prenom" placeholder="David" required><br>
            <label for="age">Age</label><br>
            <input type="number" id="age" name="age" placeholder="" required><br><br>
            <button id="Button" type="submit">Ajouter un participant</button>
        </form>
    </div>
    <table class="info-table">
        <tr>
        <th>Nombre de participants: <?php echo isset($participant_count) ? $participant_count : '0'; ?></th>
        </tr>
        <?php if ($participant_count > 0): ?>
            <?php foreach ($participants as $p): ?>
                <tr>
                    <td><?php echo htmlspecialchars($p['nom'] . ' ' . $p['prenom'] . ' (' . $p['age'] . ' ans)'); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td>Aucun participant pour le moment.</td>
            </tr>
        <?php endif; ?>
        <tr>
            <td></td>
        </tr>
    </table>
</div>
<div class="button-confirmer">
    <div class="button-container">
        <button id="confirmer">Confirmer</button>
    </div>
</div>
<footer id="footer" class="footer"></footer>
</body>
<!-- <script src="../Components/Navbar2.js"></script>
<script>
    document.getElementById("navbar").innerHTML = Navbar2();
</script> -->
<script src="../Components/NavbarAnim.js"></script>
<script src="../Components/Footer2.js"></script>
<script>
    document.getElementById("footer").innerHTML = Footer2();
</script>
</html>

