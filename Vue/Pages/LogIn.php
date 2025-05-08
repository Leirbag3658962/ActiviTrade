<?php
session_start();
if(isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Style/LogIn.css">
    <link rel="stylesheet" href="../Style/Navbar.css">
    <link rel="stylesheet" href="../Style/Footer.css">
    <title>Inscription</title>
</head>
<body>
    <header id="navbar" class="navbar">
    </header>
    <div class="boxform">
        <h1>Connexion</h1>
        <br>
        <form method="post" action="../../Controlleur/Connexion.php">
            <label for="email">Email</label><br>
            <input type="email" id="email" name="email" placeholder="david.smith@gmail.com" required></input><br>
            <label for="password">Mot de passe</label><br>
            <input type="password" id="password" name="password" placeholder="" required><br>
            <br>
            <input type="submit" value="Se connecter">
        </form>
        <p>Vous n'avez pas encore de compte? Inscrivez-vous <a href="SignIn.php">ici</a></p>
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