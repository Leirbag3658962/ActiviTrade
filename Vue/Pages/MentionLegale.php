<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../Style/MentionLegale.css"/>
<link rel="stylesheet" href="../Style/Navbar.css">
<link rel="stylesheet" href="../Style/Footer.css">
<title>MentionsLégales</title>
</head>
<body>
<header id="navbar" class="navbar"></header>

<h1>Mentions Légales</h1>
<?php
    require_once "../../Modele/LienPDO.php";
    $pdo = lienPDO();
    afficherMentions($pdo);
    ?>


<footer id="footer" class="footer"></footer>
</body>
<script src="../Components/Navbar.js"></script>
<script>
	document.getElementById("navbar").innerHTML = Navbar();
</script>
<script src="../Components/NavbarAnim.js"></script>
<script src="../Components/Footer.js"></script>
</html>