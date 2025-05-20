<?php 
session_start();
require_once(__DIR__ . '../../Components/Navbar2.php');
if(isset($_SESSION['user'])) {
    header('Location: Home.php');
    exit;
}
$token = isset($_GET["token"]) ? $_GET["token"] : "";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Style/NewPassword.css">
    <link rel="stylesheet" href="../Style/Navbar2.css">
    <link rel="stylesheet" href="../Style/Footer2.css">
    <title>Mot de passe oubli&eacute;</title>
</head>

<body>
    <header id="navbar" class="navbar">
        <?php echo Navbar2(); ?>
    </header>
    <div class="boxform">
    <form method="post" id="resetPasswordForm" action="../../Controlleur/ProcessNewPasswordController.php">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
    <h1>R&eacute;initialisation de votre mot de passe</h1>
    <p class="instructions">Saisissez un nouveau mot de passe</p>

    <div class="input-control">
        <label for="password">Nouveau mot de passe</label>
        <div class="password-input">
            <input type="password" id="password" name="password" required>
            <img src="../img/Hide.svg" class="toggle-password" data-target="password" alt="toggle">
        </div>
        <div class="error"></div>
        <div class="password-check">
            <div class="check-length">
                <img src="../img/close.svg" />&nbsp;Votre mot de passe doit contenir au moins 8 caract&egrave;res
            </div>
            <div class="check-uppercase">
                <img src="../img/close.svg" />&nbsp;Votre mot de passe doit contenir au moins 1 lettre majuscule
            </div>
            <div class="check-lowercase">
                <img src="../img/close.svg" />&nbsp;Votre mot de passe doit contenir au moins 1 lettre minuscule
            </div>
            <div class="check-number">
                <img src="../img/close.svg" />&nbsp;Votre mot de passe doit contenir au moins 1 chiffre
            </div>
            <div class="check-special">
                <img src="../img/close.svg" />&nbsp;Votre mot de passe doit contenir au moins 1 caract&egrave;re sp&eacute;cial
            </div>
        </div>
    </div>

    

    <div class="input-control">
        <label for="password2">Confirmer le nouveau mot de passe</label>
        <div class="password-input">
            <input type="password" id="password2" name="password2" required>
            <img src="../img/Hide.svg" class="toggle-password" data-target="password2" alt="toggle">
        </div>
        <div class="error"></div>
    </div>

    <button type="submit">Envoyer</button>
</form>

    </div>
    <footer id="footer" class="footer"></footer>
</body>
<script>
    const toggleIcons = document.querySelectorAll('.toggle-password');

    toggleIcons.forEach(icon => {
        icon.addEventListener('click', () => {
            const targetId = icon.getAttribute('data-target');
            const passwordInput = document.getElementById(targetId);
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            icon.src = isPassword ? '../img/Show.svg' : '../img/Hide.svg';
        });
    });
</script>
<script src="../Components/NewPasswordValidation.js"></script>
<script src="../Components/NavbarAnim.js"></script>
<script src="../Components/Footer2.js"></script>
<script>
    document.getElementById("footer").innerHTML = Footer2();
</script>
</html>