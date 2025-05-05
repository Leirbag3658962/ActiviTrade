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
