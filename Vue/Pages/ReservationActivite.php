<?php
session_start();
$_SESSION['idUser'] = 1;
$_SESSION['idActivite'] = 1;

//if (isset($_GET['idActivite'])) {
//    $_SESSION['idActivite'] = (int)$_GET['idActivite'];
//}

require_once "../../Modele/LienPDO.php";
$pdo = lienPDO();  

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

$idActivite = $_SESSION['idActivite']; 
$idUser = $_SESSION['idUser']; 

$stmt = $pdo->prepare("SELECT * FROM reservation WHERE idUser = :idUser AND idActivite = :idActivite ORDER BY date DESC");
$stmt->execute([
    ':idUser' => $idUser,
    ':idActivite' => $idActivite
]);
$participants = $stmt->fetchAll(PDO::FETCH_ASSOC);
$participant_count = count($participants);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Style/Navbar.css">
    <link rel="stylesheet" href="../Style/Footer.css">
    <link rel="stylesheet" href="../Style/ReservationActivite.css">
    <title>ReservationActivite</title>
</head>

<body>
<header id="navbar" class="navbar">
</header>
<br><br>
<h1>Réservation: Club de lecture</h1>
<div class="container">
    <div class="boxform">
        <h2>Participant</h2><br>
        <form method="post">
            <label for="nom">Nom</label><br>
            <input type="text" id="nom" name="nom" placeholder="Smith" required><br>
            <label for="prenom">Prénom</label><br>
            <input type="text" id="prenom" name="prenom" placeholder="David" required><br>
            <label for="age">Age</label><br>
            <input type="int" id="age" name="age" placeholder="" required><br><br>
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
        <button id="confirmer">Comfirmer</button>
    </div>
</div>
<footer id="footer" class="footer"></footer>
</body>
<script src="../Components/Navbar.js"></script>
<script>
    document.getElementById("navbar").innerHTML = Navbar();
</script>
<script src="../Components/NavbarAnim.js"></script>
<script src="../Components/Footer.js"></script>
</html>
