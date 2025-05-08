
<?php
session_start();
require_once "../../Modele/LienPDO.php";
$pdo = lienPDO();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = (int) $_POST['idActivite'];
    $nom = $_POST['inputNom'] ?? '';
    $adresse = $_POST['inputAdresse'] ?? '';
    $duree = $_POST['inputDuree'] ?? '';
    $nbrParticipant = (int) ($_POST['inputNbrParticipant'] ?? 0);
    $description = $_POST['inputDescription'] ?? '';
    $isPublic = ($_POST['Groupe'] === 'Public') ? 1 : 0;

    $sql = "UPDATE activite 
            SET nomActivite = ?, adresse = ?, duree = ?, nbrParticipantMax = ?, description = ?, IsPublic = ?
            WHERE idActivite = ?";
    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute([$nom, $adresse, $duree, $nbrParticipant, $description, $isPublic, $id]);

    if ($success) {
        header("Location: confirmation.php"); 
        exit;
    } else {
        echo "La mise à jour a échoué.";
    }
} else {
    echo "Demande illégale.";
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
	<form method="POST" action="modifier_activite.php">
		<label for="labNomActivite">Nom d'activité </label>
		<br>
		<input class="input" type="text" id="inputNom" name="inputNom"><br>
		<br>
		<label for="labDate">Date </label>
		<br>
		<input class="input" type="date" id="inputDate" name="inputDate"><br>
		<br>
		<label for="labDuree">Durée </label>
		<br>
		<input class="input" type="text" id="inputDuree" name="inputDuree"><br>
		<br>
		<label for="labCategorie">Catégorie </label>
		<br>
		<select class="input" id="inputCategorie" name="inputCategorie">
			<option value="test1">Test1</option>
			<option value="test2">Test2</option>
		</select><br>
		<br>
		<label for="labAdresse">Adresse </label>
		<br>
		<input class="input" type="text" id="inputAdresse" name="inputAdresse"><br>
		<br>
		<label for="labNbrParticipant">Nombre de participants </label>
		<br>
		<input class="input" type="text" id="inputNbrParticipant" name="inputNbrParticipant"><br>
	</form>
	</div>
	<div class="droite">
		<label for="labType">Type</label>
		<br><br>
		<input type="radio" id="Public" name="Groupe" value="Public">
		<label for="Public">Public</label>
		<input type="radio" id="Privée" name="Groupe" value="Privée">
		<label for="Privée">Privée</label>
		<br><br><br>
		
		<label for="labDescription">Description de l'activité </label>
		<br>
		<textarea type="text" id="inputDescription" name="inputDescription"rows="6" cols="80"></textarea><br>
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
<button id="saveButton">Enregistrer</button>
</body>

<script src="../Components/Navbar.js"></script>
<script>
	document.getElementById("navbar").innerHTML = Navbar();
</script>
<script src="../Components/NavbarAnim.js"></script>
<script src="../Components/Footer.js"></script>
</html>

