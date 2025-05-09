<?php 
session_start();
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
    <header id="navbar" class="navbar"></header>
    <div class="boxform">
    <form method="post" id="resetPasswordForm">
    <h1>R&eacute;initialisation de votre mot de passe</h1>
    <p class="instructions">Saisissez un nouveau mot de passe</p>

    <div class="input-control">
        <label for="newpassword1">Nouveau mot de passe</label>
        <div class="password-input">
            <input type="password" id="newpassword1" name="newpassword1" required>
            <img src="../img/Hide.svg" class="toggle-password" data-target="newpassword1" alt="toggle">
        </div>
        <div class="error"></div>
    </div>

    <div class="input-control">
        <label for="newpassword2">Confirmer le nouveau mot de passe</label>
        <div class="password-input">
            <input type="password" id="newpassword2" name="newpassword2" required>
            <img src="../img/Hide.svg" class="toggle-password" data-target="newpassword2" alt="toggle">
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
<script src="../Components/Navbar2.js"></script>
<script>
    document.getElementById("navbar").innerHTML = Navbar2();
</script>
<script src="../Components/NavbarAnim.js"></script>
<script src="../Components/Footer2.js"></script>
<script>
    document.getElementById("footer").innerHTML = Footer2();
</script>
</html>