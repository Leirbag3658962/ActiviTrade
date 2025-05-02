<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../Style/FAQ.css">
<link rel="stylesheet" href="../Style/Navbar.css">
<link rel="stylesheet" href="../Style/Footer.css">
<title>FAQ</title>
</head>
<body>
<header id="navbar" class="navbar"></header>
<h1 id="titrefaq"> FAQ </h1>
<div class="box">
	<?php
	require_once "../../Modele/LienPDO.php";
	$pdo = lienPDO();
	afficheFaq($pdo);
	
	?>
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