<?php
session_start();

require_once(__DIR__ . '../../../Modele/Database.php');
require_once(__DIR__ . '../../../Modele/Theme.php');
require_once(__DIR__ . '../../Components/Navbar2.php');

// require_once "../../../Controller/ActiviteController.php";
$pdo = getPDO();
$idCreator = $_SESSION['user']['id'];
if(empty($idCreator)){
	header("Location: LogIn.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../Style/CreationActivite.css">
<link rel="stylesheet" href="../Style/navbar2.css">
<link rel="stylesheet" href="../Style/footer2.css">
<title>Créer une nouvelle activité</title>
</head>
<body>
<header id="navbar" class="navbar">
	<?php echo Navbar2(); ?>
</header>
<h1 id="titrecreation">Création d'une nouvelle activité</h1>


<form method="post" action="../../Controlleur/ActiviteController.php" enctype="multipart/form-data">
<div class="conteneurForm">
	<div class="gauche">
		<label for="labNomActivite">Nom d'activité </label>
		<br>
		<input class="input" type="text" id="inputNom" name="inputNom"><br>
		<br>
		<label for="labDate">Date </label>
		<br>
		<input class="input" type="date" id="inputDate" name="inputDate"><br>
		<br>
		<label for="labAdresse">Adresse </label>
		<br>
		<input class="input" type="text" id="inputAdresse" name="inputAdresse"><br>
		<br>
		<label for="labVille">Ville </label>
		<br>
		<input class="input" type="text" id="inputVille" name="inputVille"><br>
		<br>
		<label for="labDuree">Durée </label>
		<br>
		<input class="input" type="time" id="inputDuree" name="inputDuree"><br>
		<br>
		<label for="labCategorie">Catégorie </label>
		<br>
		<select class="input" id="inputCategorie" name="inputCategorie">
			<?php
				Theme::listeCategorie();
			?>
		</select><br>
		<br>
		
		<label for="labNbrParticipant">Nombre de participants </label>
		<br>
		<input class="input" type="number" id="inputNbrParticipant" name="inputNbrParticipant"><br>
	
	</div>
	<div class="droite">
		<label for="labPrix">Prix </label>
		<br>
		<input class="input" type="number" id="inputPrix" name="inputPrix"><br>
		<br>
		<label for="labType">Type</label>
		<br><br>
		<input type="radio" id="Public" name="Groupe" value="Public" checked>
		<label id="labPublic" for="Public">Public</label>
		<input type="radio" id="Privée" name="Groupe" value="Privée">
		<label id="labPrivée" for="Privée">Privée</label>
		<br><br><br>
		
		<label for="labDescription">Description de l'activité </label>
		<br>
		<textarea type="text" id="inputDescription" name="inputDescription"></textarea><br>
	</div>
</div>
<br><br>
<label id="labPicture" for="labPhoto">Déposer 3 images pour introduire l'activité (300*224)</label> 
<br><br>
<div class="conteneurImage">
	<div class="Cells">
		<p class="Paragraph"><label for="ImageInput1">Déposez ou cliquez pour parcourir</label></p>
        <input type="file" id="ImageInput1" name="ImageInput[]" class="ImageInput" accept="image/*" hidden>
        <img class="ImageActivite" src="" alt="" style="display: none;">
	</div>
	<div class="Cells">
		<p class="Paragraph"><label for="ImageInput2">Déposez ou cliquez pour parcourir</label></p>
        <input type="file" id="ImageInput2" name="ImageInput[]" class="ImageInput" accept="image/*" hidden>
        <img class="ImageActivite" src="" alt="" style="display: none;">
	</div>
	<div class="Cells">
		<p class="Paragraph"><label for="ImageInput3">Déposez ou cliquez pour parcourir</label></p>
        <input type="file" id="ImageInput3" name="ImageInput[]" class="ImageInput" accept="image/*" hidden>
        <img class="ImageActivite" src="" alt="" style="display: none;">
	</div>
</div>
<br>
<button type="submit" id="createButton">Créer</button>
</form>
<br><br>

<footer id="footer" class="footer"></footer>
</body>

<!-- <script src="../Components/NavbarAnim.js"></script> -->
<script src="../Components/DragAndDrop.js"></script>
<script src="../Components/Footer2.js"></script>
<script src="../Components/CreationActivite.js"></script>
<script>
	document.getElementById("footer").innerHTML = Footer2();
</script>
</html>