<?php
session_start();
require_once(__DIR__ . '../../Components/Navbar2.php');
require_once(__DIR__ . '../../../Modele/CGUModele.php');
require_once(__DIR__ . '../../Components/Footer2.php');
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../Style/Cgu.css"/>
<link rel="stylesheet" href="../Style/navbar2.css">
<link rel="stylesheet" href="../Style/footer2.css">
<title>CGU</title>
</head>
<body>
<header id="navbar" class="navbar">
    <?php echo Navbar2(); ?>
</header>

<h1>Conditions Générales d'Utilisation</h1>
    <?php
        CGU::afficherCgu();
    ?>


<footer id="footer" class="footer">
    <?php echo Footer2(); ?>
</footer>
</body>
<script src="../Components/NavbarAnim.js"></script>
<script src="../Components/DragAndDrop.js"></script>
</html>