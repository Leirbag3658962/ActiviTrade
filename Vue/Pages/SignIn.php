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
    <link rel="stylesheet" href="../Style/SignIn.css">
    <link rel="stylesheet" href="../Style/Navbar.css">
    <link rel="stylesheet" href="../Style/Footer.css">
    <title>Inscription</title>
</head>
<body>
    <header id="navbar" class="navbar"></header>
    <div class="boxform">
        <h1>Formulaire d'inscription</h1>
        <form method="post" action="../../Controlleur/Inscription.php">
            <label for="lastname">Nom</label><br>
            <input type="text" id="lastname" name="lastname" placeholder="Smith" required><br>

            <label for="firstname">Pr&eacute;nom</label><br>
            <input type="text" id="firstname" name="firstname" placeholder="David" required><br>

            <label for="email">Email</label><br>
            <input type="email" id="email" name="email" placeholder="david.smith@gmail.com" required><br>

            <label for="birthdate">Date de naissance</label><br>
            <input type="date" id="birthdate" name="birthdate" required><br>

            <label for="numeroRue">Num&eacute;ro de rue</label><br>
            <input type="text" id="numeroRue" name="numeroRue" placeholder="1" required><br>

            <label for="nomRue">Nom de la rue</label><br>
            <input type="text" id="nomRue" name="nomRue" placeholder="Avenue des Champs-Élysées" required><br>

            <label for="codePostal">Code postal</label><br>
            <input type="text" id="codePostal" name="codePostal" placeholder="75001" required><br>

            <label for="ville">Ville</label><br>
            <input type="text" id="ville" name="ville" placeholder="Paris" required><br>

            <label for="pays">Pays</label><br>
            <input type="text" id="pays" name="pays" placeholder="France" required><br>

            <label for="indicatif">Indicatif t&eacute;l&eacute;phonique</label><br>
            <input type="text" id="indicatif" name="indicatif" placeholder="+33" required><br>

            <label for="telephone">Num&eacute;ro de t&eacute;l&eacute;phone</label><br>
            <input type="tel" id="telephone" name="telephone" placeholder="612345678" required><br>

            <label for="password">Mot de passe</label><br>
            <input type="password" id="password" name="password" placeholder="Votre mot de passe" required><br>

            <label for="password2">Confirmer le mot de passe</label><br>
            <input type="password" id="password2" name="password2" placeholder="Confirmez votre mot de passe" required><br>

            <!-- <div id="checkboxdiv" class="checkboxdiv">
                <input type="checkbox" id="cgu" value="CGU" name="condition[]" required>
                <label for="cgu">J'ai lu et j'accepte les <a href="">Conditions Générales d'Utilisation</a></label><br>
            </div> -->
            <br>
            <input type="submit" value="S'inscrire">
        </form>
        <br>
        <p>Vous avez d&eacute;j&agrave; un compte ? Connectez-vous <a href="LogIn.php">ici</a></p>
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
