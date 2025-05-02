<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
</head>
<body>
    <?php if(!isset($_SESSION['user'])): ?>
        <h1>Bienvenue sur notre site !</h1>
        <p>Veuillez vous inscrire ou vous connecter pour continuer.</p>
        <a href="/Vue/Pages/SignIn.php">Inscription</a><br>
        <a href="/Vue/Pages/LogIn.php">Connexion</a><br>
    <?php else: ?>
        <a href="/Controlleur/Deconnexion.php">Se déconnecter</a><br>
        <a href="/Vue/Pages/ResetPassword.php">Mot de passe oubli&eacute;</a><br>
    <?php endif; ?>
</body>
<script type=""></script>
</html>