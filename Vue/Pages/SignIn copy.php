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
    <header id="navbar" class="navbar">
    </header>
    <div class="boxform">
        <h1>Formulaire d'inscription</h1>
        <form method="post">
            <label for="lastname">Nom</label><br>
            <input type="text" id="lastname" name="lastname" placeholder="Smith" required><br>
            <label for="firstname">Pr&eacute;nom</label><br>
            <input type="text" id="firstname" name="firstname" placeholder="David" required><br>
            <label for="email">Email</label><br>
            <input type="email" id="email" name="email" placeholder="david.smith@gmail.com" required></input><br>
            <label for="birthdate">Date de naissance</label><br>
            <input type="date" id="birthdate" name="birthdate" placeholder="mm/jj/aaaa" required><br>
            <label for="address">Adresse</label><br>
            <input type="text" id="address" name="address" placeholder="1 avenue des champs Elysés, Paris 75001" required><br>
            <label for="password">Mot de passe</label><br>
            <input type="password" id="password" name="password" placeholder="" required><br>
            <label for="password">Confirmer le mot de passe</label><br>
            <input type="password" id="password2" name="password" placeholder="" required><br>
            <div id="checkboxdiv" class="checkboxdiv">
                <input type="checkbox" value="CGU" name="condition[]" required>J'ai lu et j'accepte les <a href="">Conditions G&eacute;n&eacute;rales d'Utilisation</a><br>
            </div>
            <br>
            <input type="submit" value="S'inscrire">
        </form>
        <br>
        <p>Vous avez d&eacute;j&agrave; un compte ? Connectez-vous <a href="LogIn.html">ici</a></p>
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
