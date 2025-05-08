<?php
session_start();
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
    <header id="navbar" class="navbar"></header>
    <div class="boxform">
        <form id="resetForm" method="post">
            <h1>Mot de passe oubli&eacute;</h1>
            <p class="instructions">Nous vous enverrons un email de r&eacute;cup&eacute;ration afin de r&eacute;initialiser votre mot de passe.</p>
            
            <div class="input-control">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="david.smith@gmail.com" required>
                <div class="error"></div>
            </div>
            <p>Vous n'avez pas encore de compte ? <a href="SignIn.html">Inscrivez-vous</a></p>
        <p>Vous avez d&eacute;j&agrave; un compte ? <a href="LogIn.html">Connectez-vous</a></p>

            <button type="submit" id="sendButton">Envoyer</button>
        </form>

        <div id="codeInputs" style="display: none;">
            <p>Entrez le code que vous avez re&ccedil;u par email :</p>
            <div class="code-container">
                <input type="text" maxlength="1" class="code-input">
                <input type="text" maxlength="1" class="code-input">
                <input type="text" maxlength="1" class="code-input">
                <input type="text" maxlength="1" class="code-input">
                <input type="text" maxlength="1" class="code-input">
                <input type="text" maxlength="1" class="code-input">
            </div>
            <br>
            <button type="submit">V&eacute;rifier</button>
        </div>

        
    </div>
    <footer id="footer" class="footer"></footer>
</body>

<script src="../Components/Navbar2.js"></script>
<script src="../Components/CodePassword.js"></script>
<script>
    document.getElementById("navbar").innerHTML = Navbar2();
    document.getElementById("sendButton").addEventListener("click", CodePassword);
</script>
<script src="../Components/NavbarAnim.js"></script>
<script src="../Components/Footer2.js"></script>
<script>
    document.getElementById("footer").innerHTML = Footer2();
</script>
</html>