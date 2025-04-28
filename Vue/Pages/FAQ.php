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
	// Pour erreur
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);


	$host = 'localhost';
	$dbname = 'activitrade';
	$username = 'root';
	$password = '';
	$pageActuelle = "utilisateur";

	try {
    	$pdo = new PDO("mysql:host=$host;port=3307;dbname=$dbname;charset=utf8", $username, $password);
    	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch(PDOException $e) {
    	echo "Erreur de connexion : " . $e->getMessage();
    	exit;
	}

	$sqlfaq = "SELECT * FROM faq";
	$stmtfaq = $pdo->query($sqlfaq); 
	if($stmtfaq){
		while ($row = $stmtfaq->fetch(PDO::FETCH_ASSOC)){
			echo"<div>";
			echo"<a class=\"bulle-sms bulle-gauche\">" . $row['question'] . "</a>";
			echo"</div>";

			echo"<div>";
			echo"<a class=\"bulle-sms bulle-droite\">" . $row['reponse'] . "</a>";
			echo"</div>";
			
		}
	}
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