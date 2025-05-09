
<?php
session_start();
require_once "../../Modele/LienPDO.php";
$pdo = lienPDO();

// $_SESSION['idActivite'] = 1;

$idActivite = $_SESSION['idActivite'] ?? null;

if (!$idActivite) {
    die("Aucune activité sélectionnée.");
}

$sql = "SELECT * FROM activite WHERE idActivite = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$idActivite]);
$activite = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$activite) {
    die("Activité introuvable.");
}

// Traitement formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['inputNom'] ?? '';
    $adresse = $_POST['inputAdresse'] ?? '';
    $duree = $_POST['inputDuree'] ?? '';
    $prix = $_POST['inputPrix'] ?? '';
    $nbrParticipant = (int) ($_POST['inputNbrParticipant'] ?? 0);
    $description = $_POST['inputDescription'] ?? '';
    $isPublic = ($_POST['Groupe'] ?? '') === 'Public' ? 1 : 0;

    $sql = "UPDATE activite 
            SET nomActivite = ?, adresse = ?, duree = ?, prix = ?, nbrParticipantMax = ?, description = ?, IsPublic = ?
            WHERE idActivite = ?";
    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute([
        $nom, $adresse, $duree, $prix, $nbrParticipant, $description, $isPublic, $idActivite
    ]);

    if ($success) {
        header("Location: ModificationActivite.php");
        exit;
    } else {
        echo "La mise à jour a échoué.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../Style/CreationActivite.css">
<link rel="stylesheet" href="../Style/Navbar.css">
<link rel="stylesheet" href="../Style/Footer.css">
<title>Modifier une activité</title>
</head>
<body>
<header id="navbar" class="navbar"></header>
<h1 id="titrecreation">Modification de ton activité</h1>
<div class="conteneurForm">
    <div class="gauche">
    <form method="POST">
        <label for="inputNom">Nom d'activité</label><br>
        <input class="input" type="text" id="inputNom" name="inputNom" value="<?= htmlspecialchars($activite['nomActivite']) ?>"><br><br>

		<label for="inputDuree">Durée</label><br>
        <input class="input" type="text" id="inputDuree" name="inputDuree" value="<?= htmlspecialchars($activite['duree']) ?>"><br><br>
        
        <label for="inputDuree">Prix</label><br>    
        <input class="input" type="text" id="inputPrix" name="inputPrix" value="<?= htmlspecialchars($activite['prix']) ?>"><br><br>

        <label for="inputAdresse">Adresse</labe><br>
        <input class="input" type="text" id="inputAdresse" name="inputAdresse" value="<?= htmlspecialchars($activite['adresse']) ?>"><br><br>

        <label for="inputNbrParticipant">Nombre de participants</label><br>
        <input class="input" type="text" id="inputNbrParticipant" name="inputNbrParticipant" value="<?= htmlspecialchars($activite['nbrParticipantMax']) ?>"><br>

        <button id="saveButton" type="submit">Enregistrer</button>
    </form>
    </div>
    <div class="droite">

        <label for="labType">Type</label><br><br>
        <input type="radio" id="Public" name="Groupe" value="Public" <?= $activite['IsPublic'] ? 'checked' : '' ?>>
        <label for="Public">Public</label>
        <input type="radio" id="Privée" name="Groupe" value="Privée" <?= !$activite['IsPublic'] ? 'checked' : '' ?>>
        <label for="Privée">Privée</label><br><br><br>

        <label for="inputDescription">Description de l'activité</label><br>
        <textarea id="inputDescription" name="inputDescription" rows="6" cols="80"><?= htmlspecialchars($activite['description']) ?></textarea><br><br>
    </div>
</div>
<br><br>
<label id="labPicture" for="labPhoto">Déposer 3 images pour introduire l'activité (300*224)</label> 
<br><br>
<div class="conteneurImage">
    <div class="Cells"></div>
    <div class="Cells"></div>
    <div class="Cells"></div>
</div>
<br><br><br>
<footer id="footer" class="footer"></footer>

</body>

<script src="../Components/Navbar.js"></script>
<script>
    document.getElementById("navbar").innerHTML = Navbar();
</script>
<script src="../Components/NavbarAnim.js"></script>
<script src="../Components/Footer.js"></script>
</html>
