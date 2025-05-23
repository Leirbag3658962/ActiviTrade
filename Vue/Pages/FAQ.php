<?php
session_start();
require_once(__DIR__ . '../../Components/Navbar2.php');
require_once(__DIR__ . '../../../Modele/FaqModele.php');
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../Style/FAQ.css">
<link rel="stylesheet" href="../Style/navbar2.css">
<link rel="stylesheet" href="../Style/footer2.css">
<title>FAQ</title>
</head>
<body>
<header id="navbar" class="navbar">
	<?php echo Navbar2(); ?>
</header>
<h1 id="titrefaq"> FAQ </h1>
<div class="box">
	<?php
	FAQ::afficheFaq();
	?>
</div>
<footer id="footer" class="footer"></footer>
</body>
<script src="../Components/NavbarAnim.js"></script>
<script src="../Components/DragAndDrop.js"></script>
<script src="../Components/Footer2.js"></script>
<script>
	document.getElementById("footer").innerHTML = Footer2();
</script>
</html>