<?php

session_start();
require_once "../../ModeleB/LienPDO.php";
$pdo = lienPDO();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$note = htmlspecialchars($_POST['inputNote']);
	$commentaire = htmlspecialchars($_POST['inputCommentaire']);

	
	if (is_numeric($note) && $note >= 0 && $note <= 5) {
		echo "<div class='result'>";
		echo "<h3>Merci pour votre avis !</h3>";
		echo "<p><strong>Note :</strong> " . $note . "</p>";
		echo "<p><strong>Commentaire :</strong> " . nl2br($commentaire) . "</p>";
		echo "</div>";
	} else {
		echo "<p style='color:red;'>Veuillez saisir une note valide entre 0 et 5.</p>";
	}
}
?>
    


<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../Style/Avis.css">
<link rel="stylesheet" href="../Style/Navbar.css">
<link rel="stylesheet" href="../Style/Footer.css">
<title>Avis</title>
</head>
<body>
<header id="navbar" class="navbar"></header>

<div class="boxform">
	<form method="POST" action="">
		<label for="inputNote">Note d'activité</label>
		<br>
		<input class="input" type="text" id="inputNote" name="inputNote" required>&nbsp;&nbsp;&nbsp;&nbsp;
		<span class="note-text">Saisir une note de 0 à 5</span>
		<br><br>
		<label for="inputCommentaire">Commentaire</label>
		<br>
		<textarea id="inputCommentaire" name="inputCommentaire" rows="10" cols="80" required></textarea><br><br>
		<div class="button-container">
			<button id="Button" type="submit">Confirmer</button>
		</div>
	</form>



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
