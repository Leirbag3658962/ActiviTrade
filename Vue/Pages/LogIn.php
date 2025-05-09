<?php
session_start();
if(isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit;
}
require_once(__DIR__ . '../../Components/Navbar2.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Style/LogIn.css">
    <link rel="stylesheet" href="../Style/Navbar2.css">
    <link rel="stylesheet" href="../Style/Footer2.css">
    <title>Inscription</title>
</head>
<body>
    <header id="navbar" class="navbar">
        <?php echo Navbar2(); ?>
    </header>

    <div class="boxform">
        <form id="form" method="post" action="../../Controlleur/Connexion.php">
            <h1>Connexion</h1>

            <div class="input-control">
                <label for="email">Email</label><br>
                <input type="email" id="email" name="email" placeholder="david.smith@gmail.com" required><br>
                <div class="error"></div>
            </div>

            <div class="input-control">
                <label for="password">Mot de passe</label>
                <div class="password-input">
                    <input type="password" id="password" name="password" placeholder="Votre mot de passe" required>
                    <img src="../img/Hide.svg" id="showHide">
                </div>
                <div class="error"></div>
            </div>
            <p>Mot de passe oublié ? Cliquez <a href="ResetPassword.php">ici</a></p>
            <button type="submit">Se connecter</button>
            <p>Vous n'avez pas encore de compte ? Inscrivez-vous <a href="SignIn.php">ici</a></p>
        </form>
    </div>

    <footer id="footer" class="footer"></footer>
</body>
<script src="../Components/LogIn.js"></script>
<!-- <script src="../Components/Navbar2.js"></script>
<script>
    document.getElementById("navbar").innerHTML = Navbar2();
</script> -->
<script src="../Components/NavbarAnim.js"></script>
<script src="../Components/Footer2.js"></script>
<script>
    document.getElementById("footer").innerHTML = Footer2();
</script>
</html>