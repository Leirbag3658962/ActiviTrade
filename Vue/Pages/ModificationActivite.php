<?php
session_start();
require_once "../../Modele/LienPDO.php";
$pdo = lienPDO();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$nom = htmlspecialchars($_POST['inputNom']);
	$date = htmlspecialchars($_POST['inputDate']);
	$duree = htmlspecialchars($_POST['inputDuree']);
	$categorie = htmlspecialchars($_POST['inputCategorie']);
	$adresse = htmlspecialchars($_POST['inputAdresse']);
	$participants = htmlspecialchars($_POST['inputNbrParticipant']);
	$type = isset($_POST['Groupe']) ? htmlspecialchars($_POST['Groupe']) : 'Non spécifié';
	$description = htmlspecialchars($_POST['inputDescription']);

	echo "<div style='color:green;'><strong>Activité modifiée avec succès !</strong></div>";
	echo "<div><strong>Détails reçus :</strong><br>";
	echo "Nom : $nom<br>";
	echo "Date : $date<br>";
	echo "Durée : $duree<br>";
	echo "Catégorie : $categorie<br>";
	echo "Adresse : $adresse<br>";
	echo "Participants : $participants<br>";
	echo "Type : $type<br>";
	echo "Description : $description<br></div>";
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
