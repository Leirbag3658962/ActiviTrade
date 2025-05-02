<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../Style/CreationActivite.css">
<link rel="stylesheet" href="../Style/Navbar.css">
<link rel="stylesheet" href="../Style/Footer.css">
<title>Créer une nouvelle activité</title>
</head>
<body>
<header id="navbar" class="navbar"></header>
<h1 id="titrecreation">Création d'une nouvelle activité</h1>
<div class="conteneurForm">
	<div class="gauche">
	<form method="post">
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
		<input class="input" type="text" id="inputDuree" name="inputDuree"><br>
		<br>
		<label for="labCategorie">Catégorie </label>
		<br>
		<select class="input" id="inputCategorie" name="inputCategorie">
			<option value="test1">Test1</option>
			<option value="test2">Test2</option>
		</select><br>
		<br>
		
		<label for="labNbrParticipant">Nombre de participants </label>
		<br>
		<input class="input" type="text" id="inputNbrParticipant" name="inputNbrParticipant"><br>
		<?php
                require_once "../../ModeleB/LienPDO.php";
                $pdo = lienPDO(); 
            ?>
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
		<textarea type="text" id="inputDescription" name="inputDescription"></textarea><br>
	</div>
</div>
<br><br>
<label id="labPicture" for="labPhoto">Déposer 3 images pour introduire l'activité (300*224)</label> 
<br><br>
<div class="conteneurImage">
	<div class="Cells">
		<p class="Paragraph"><label for="ImageInput">Déposez ou cliquez pour parcourir</label></p>
        <input type="file" class="ImageInput" accept="image/*" hidden>
        <img class="ImageActivite" src="" alt="" style="display: none;">
	</div>
	<div class="Cells">
		<p class="Paragraph"><label for="ImageInput">Déposez ou cliquez pour parcourir</label></p>
        <input type="file" class="ImageInput" accept="image/*" hidden>
        <img class="ImageActivite" src="" alt="" style="display: none;">
	</div>
	<div class="Cells">
		<p class="Paragraph"><label for="ImageInput">Déposez ou cliquez pour parcourir</label></p>
        <input type="file" class="ImageInput" accept="image/*" hidden>
        <img class="ImageActivite" src="" alt="" style="display: none;">
	</div>
</div>
<br>
<button id="createButton">Créer</button>
<br><br>

<footer id="footer" class="footer"></footer>
</body>

<script src="../Components/Navbar.js"></script>
<script>
	document.getElementById("navbar").innerHTML = Navbar();
</script>
<script src="../Components/NavbarAnim.js"></script>
<script src="../Components/DragAndDrop.js"></script>
<script src="../Components/Footer.js"></script>
</html>