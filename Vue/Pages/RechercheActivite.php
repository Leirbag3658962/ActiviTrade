<?php
session_start();
require_once(__DIR__ . '/../../Modele/Database.php');
require_once(__DIR__ . '/../../Modele/LienPDO.php');
require_once(__DIR__ . '/../../Modele/Research.php');
require_once(__DIR__ . '/../../Controlleur/RechercheActiviteController.php');
require_once(__DIR__ . '../../Components/Navbar2.php');

$activityIds = verifRechercheSoumise($_GET['q']); 

?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../Style/RechercheActivite.css">
<link rel="stylesheet" href="../Style/navbar2.css">
<link rel="stylesheet" href="../Style/footer2.css">
<script src="../Components/Caroussel.js"></script>

<title>RechercheActivite</title>
</head>

<body>
<header id="navbar" class="navbar">
	<?php echo Navbar2(); ?>
</header>

<div class="conteneur">
	<?php	
    	echo listeActivites($activityIds);
	?>
</div>

<footer id="footer" class="footer"></footer>
</body>

<script src="../Components/NavbarAnim.js"></script>
<script src="../Components/Footer2.js"></script>
<script>
	document.getElementById("footer").innerHTML = Footer2();
</script>
</html>