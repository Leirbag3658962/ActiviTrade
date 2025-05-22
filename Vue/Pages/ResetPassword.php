<?php
session_start();
if(isset($_SESSION['user'])) {
    header('Location: home.php');
    exit;
}
require_once(__DIR__ . '../../Components/Navbar2.php');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Style/ResetPassword.css">
    <link rel="stylesheet" href="../Style/Navbar2.css">
    <link rel="stylesheet" href="../Style/Footer2.css">
    <title>Mot de passe oubli&eacute;</title>
</head>
<body>
    <header id="navbar" class="navbar">
        <?php echo Navbar2(); ?>
    </header>
    <div class="boxform">
        <?php if (!isset($_GET['sent'])): ?>
        <form id="resetForm" method="post" action="../../Controlleur/ResetPasswordController.php">
            <h1>Mot de passe oubli&eacute;</h1>
            <p class="instructions">Nous vous enverrons un email de r&eacute;cup&eacute;ration afin de r&eacute;initialiser votre mot de passe.</p>
            
            <div class="input-control">
                <label for="email">Email</label><br>
                <div class="password-input">
                    <input type="email" id="email" name="email" placeholder="david.smith@gmail.com" ><br>
                </div>
                <div class="error"></div>
            </div>
            <p>Vous n'avez pas encore de compte ? <a href="SignIn.php">Inscrivez-vous</a></p>
            <p>Vous avez d&eacute;j&agrave; un compte ? <a href="LogIn.php">Connectez-vous</a></p>

            <button type="submit" id="sendButton">Envoyer</button>
        </form>        
        <?php elseif (isset($_GET['sent']) && $_GET['sent'] == 1): ?>
            <div id="mailSentMsg" style="color: green; text-align: center; margin-bottom: 1em;">
                Un email vous a été envoyé, vous pouvez fermer cette page.
            </div>
        <?php endif; ?>
    </div>
    <footer id="footer" class="footer"></footer>
</body>

<!-- <script src="../Components/Navbar2.js"></script> -->
<!-- <script src="../Components/CodePassword.js"></script> -->
<!-- <script>
    // document.getElementById("navbar").innerHTML = Navbar2();
    document.getElementById("sendButton").addEventListener("click", CodePassword);
</script> -->
<script src="../Components/NavbarAnim.js"></script>
<script src="../Components/Footer2.js"></script>
<script>
    document.getElementById("footer").innerHTML = Footer2();
</script>
</html>