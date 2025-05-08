<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../Style/Cgu.css"/>
<link rel="stylesheet" href="../Style/navbar2.css">
<link rel="stylesheet" href="../Style/footer2.css">
<title>CGU</title>
</head>
<body>
<header id="navbar" class="navbar"></header>

<h1>Conditions Générales d'Utilisation</h1>
<?php
    require_once "../../Modele/LienPDO.php";
    $pdo = lienPDO();
    afficherCgu($pdo);
    ?>


<footer id="footer" class="footer"></footer>
</body>
<script src="../Components/Navbar2.js"></script>
<script>
	document.getElementById("navbar").innerHTML = Navbar2();
</script>
<script src="../Components/NavbarAnim.js"></script>
<script src="../Components/DragAndDrop.js"></script>
<script src="../Components/Footer2.js"></script>
<script>
	document.getElementById("footer").innerHTML = Footer2();
</script>
</html>