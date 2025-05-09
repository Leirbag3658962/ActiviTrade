<?php
session_start();
$_SESSION['idUser'] = 1;

require_once "../../Modele/LienPDO.php";
$pdo = lienPDO();

$message = "";

if (!isset($_SESSION['idUser'])) {
    header("Location: LogIn.php");
    exit;
}
$idUser = $_SESSION['idUser']; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $telephone = htmlspecialchars($_POST['telephone']);
    $dateNaissance = $_POST['dateNaissance'];
    $ville = htmlspecialchars($_POST['ville']);

    if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($telephone) && !empty($dateNaissance) && !empty($ville)) {
        try {
            $stmt = $pdo->prepare("UPDATE utilisateur 
                SET nom = ?, prenom = ?, email = ?, telephone = ?, dateNaissance = ?, ville = ? 
                WHERE idUtilisateur = ?");
            
            $stmt->execute([$nom, $prenom, $email, $telephone, $dateNaissance, $ville, $idUser]);

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
    <link rel="stylesheet" href="../Style/Navbar2.css">
    <link rel="stylesheet" href="../Style/Footer2.css">
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
        <label for="nom">Nom</label><br>
        <input type="text" id="nom" name="nom" placeholder="Smith" required><br>

        <label for="prenom">Pr&eacute;nom</label><br>
        <input type="text" id="prenom" name="prenom" placeholder="David" required><br>

        <label for="email">Email</label><br>
        <input type="email" id="email" name="email" placeholder="david.smith@gmail.com" required><br>

        <label for="telephone">Num&eacute;ro de t&eacute;l&eacute;phone</label><br>
        <input type="tel" id="telephone" name="telephone" placeholder="612345678" required><br>

        <label for="dateNaissance">Date de naissance</label><br>
        <input type="date" id="dateNaissance" name="dateNaissance" required><br>

        <label for="ville">Ville</label><br>
        <input type="text" id="ville" name="ville" placeholder="Paris" required><br>
        <br>
        <button id="Button" type="submit">Enregistrer</button>
    </form>
    <br>
</div>
<footer id="footer" class="footer"></footer>
</body>
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
