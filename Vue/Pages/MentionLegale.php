<?php
session_start();
require_once(__DIR__ . '../../Components/Navbar2.php');
require_once(__DIR__ . '../../../Modele/MentionsLegales.php');
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../Style/MentionLegale.css"/>
<link rel="stylesheet" href="../Style/navbar2.css">
<link rel="stylesheet" href="../Style/footer2.css">
<title>MentionsLégales</title>
</head>
<body>
<header id="navbar" class="navbar">
    <?php echo Navbar2(); ?>
</header>

<h1>Mentions Légales</h1>
    <?php
        MentionsLegales::afficherMentions();
    ?>


<footer id="footer" class="footer"></footer>
</body>
<!-- <script src="../Components/Navbar2.js"></script>
<script>
	document.getElementById("navbar").innerHTML = Navbar2();
</script> -->
<script src="../Components/NavbarAnim.js"></script>
<script src="../Components/DragAndDrop.js"></script>
<script src="../Components/Footer2.js"></script>
<script>
	document.getElementById("footer").innerHTML = Footer2();
</script>
</html>