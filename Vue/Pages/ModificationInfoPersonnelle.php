<?php
session_start();
require_once "../../Modele/LienPDO.php";
$pdo = lienPDO();

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_SESSION['user_id']; 
    $lastname = htmlspecialchars($_POST['nom']);
    $firstname = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $birthdate = $_POST['dateNaissance'];
    $address = htmlspecialchars($_POST['address']);

    if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($dateNaissance) && !empty($address)) {
        try {
            $stmt = $pdo->prepare("UPDATE utilisateur SET nom = ?, prenom = ?, email = ?, dateNaissance = ?, address = ? WHERE idUtilisateur = ?");
            $stmt->execute([$nom, $prenom, $email, $dateNaissance, $address, $idUtilisateur]);
            $message = "Informations mises à jour avec succès !";
        } catch (PDOException $e) {
            $message = "Erreur lors de la mise à jour : " . $e->getMessage();
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Style/Navbar.css">
    <link rel="stylesheet" href="../Style/Footer.css">
    <link rel="stylesheet" href="../Style/ModificationInfoPersonnelle.css">
    <title>ModificationInfo</title>
</head>
<body>
<header id="navbar" class="navbar"></header>
<br><br>
<h1>Mon Profil</h1>
<div class="boxform">
    <br>
    <h2>Modification</h2>
    <?php if ($message): ?>
        <p style="color: green; font-weight: bold;"><?= $message ?></p>
    <?php endif; ?>
    <form method="post">
        <br>
        <label for="lastname">Nom</label><br>
        <input type="text" id="lastname" name="lastname" placeholder="Smith" required><br>
        <label for="firstname">Pr&eacute;nom</label><br>
        <input type="text" id="firstname" name="firstname" placeholder="David" required><br>
        <label for="email">Email</label><br>
        <input type="email" id="email" name="email" placeholder="david.smith@gmail.com" required><br>
        <label for="birthdate">Date de naissance</label><br>
        <input type="date" id="birthdate" name="birthdate" required><br>
        <label for="address">Adresse</label><br>
        <input type="text" id="address" name="address" placeholder="1 avenue des champs Elysés, Paris 75001" required><br>
        <br>
        <button id="Button" type="submit">Enregistrer</button>
    </form>
    <br>
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

