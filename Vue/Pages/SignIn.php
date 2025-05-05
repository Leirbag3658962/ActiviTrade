<?php
session_start();

$messageErreur = $_SESSION["erreur"] ?? "";
unset($_SESSION["erreur"]);

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
        <form id="form" method="post" action="../../Controlleur/Inscription.php">
            <h1>Inscription</h1>
            <div class="input-control">
                <label for="lastname">Nom</label><br>
                <input type="text" id="lastname" name="lastname" placeholder="Smith" ><br>
                <div class="error"></div>
            </div>

            <div class="input-control">
                <label for="firstname">Pr&eacute;nom</label><br>
                <input type="text" id="firstname" name="firstname" placeholder="David" ><br>
                <div class="error"></div>
            </div>

            <div class="input-control">
                <label for="email">Email</label><br>
                <input type="email" id="email" name="email" placeholder="david.smith@gmail.com" ><br>
                <div class="error"></div>
            </div>

            <div class="input-control">
                <label for="birthdate">Date de naissance</label><br>
                <input type="date" id="birthdate" name="birthdate" ><br>
                <div class="error"></div>
            </div>

            <div class="input-control">
                <label for="ville">Ville</label><br>
                <input type="text" id="ville" name="ville" placeholder="Paris" ><br>
                <div class="error"></div>
            </div>

            <div class="input-control">
                <label for="telephone">Num&eacute;ro de t&eacute;l&eacute;phone</label><br>
                <input type="tel" id="telephone" name="telephone" placeholder="612345678" ><br>
                <div class="error"></div>
            </div>

            <div class="input-control">
                <label for="password">Mot de passe</label><br>
                <input type="password" id="password" name="password" placeholder="Votre mot de passe" ><br>
                <div class="error"></div>
            </div>

            <div class="input-control">
                <label for="password">Confirmer le mot de passe</label><br>
                <input type="password" id="password2" name="password2" placeholder="Confirmez votre mot de passe" ><br>
                <div class="error"></div>
            </div>

            <!-- <div id="checkboxdiv" class="checkboxdiv">
                <input type="checkbox" id="cgu" value="CGU" name="condition[]" >
                <label for="cgu">J'ai lu et j'accepte les <a href="">Conditions Générales d'Utilisation</a></label><br>
            </div> -->
            <br>
            <button type="submit">S'inscrire</button>
            <p>Vous avez d&eacute;j&agrave; un compte ? Connectez-vous <a href="LogIn.php">ici</a></p>
        </form>
        <br>
    </div>
    <footer id="footer" class="footer"></footer>
</body>
<script src="../Components/InscriptionValidation.js"></script>
<script src="../Components/Navbar.js"></script>
<script>
    document.getElementById("navbar").innerHTML = Navbar();
</script>
<script src="../Components/NavbarAnim.js"></script>
<script src="../Components/Footer.js"></script>
</html>
