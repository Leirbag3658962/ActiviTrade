<?php
session_start();


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: LogIn.php"); // 或者：die("Aucune activité spécifiée.");
    exit;
}

require_once(__DIR__ . '../../../Modele/Database.php');
require_once(__DIR__ . '../../Components/Navbar2.php');
$pdo = getPDO();

$idUser = (int) $_GET['id'];
$_SESSION['idUser'] = $idUser;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $theme = htmlspecialchars($_POST['theme']);
    $contenu = htmlspecialchars($_POST['contenu']);
//    $idUser = $_SESSION['idUser'];
    $date = date("Y-m-d H:i:s");

    $idParent = NULL;

    $sql = "INSERT INTO forum (theme, date, contenu, idUser, idParent) 
            VALUES (:theme, :date, :contenu, :idUser, :idParent)";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':theme', $theme);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':contenu', $contenu);
    $stmt->bindParam(':idUser', $idUser);
    $stmt->bindParam(':idParent', $idParent);

    if ($stmt->execute()) {
        echo "Le sujet a été créé avec succès.";
    } else {
        echo "Erreur lors de la création du sujet.";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Style/Forum.css">
    <link rel="stylesheet" href="../Style/navbar2.css">
    <link rel="stylesheet" href="../Style/footer2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Créer un Forum</title>
</head>
<body>
<header id="navbar" class="navbar">
    <?php echo Navbar2(); ?>
</header>

<div class="main-content">
    <h1>Création d'un Nouveau Sujet</h1>
    <div class="createforum-container">

        <form action="CreationForum.php" method="POST">
            <label for="theme">Thème:</label>
            <input type="text" id="theme" name="theme" required><br>

            <label for="contenu">Contenu:</label>
            <textarea id="contenu" name="contenu" rows="4" cols="50" required></textarea><br>

            <button id="Button" type="submit">Confirmer</button>
        </form>
    </div>
</div>

<footer id="footer" class="footer"></footer>


</body>

<!--<script src="../Components/Navbar2.js"></script>-->
<!--<script>-->
<!--    document.getElementById("navbar").innerHTML = Navbar2();-->
<!--</script>-->
<script src="../Components/NavbarAnim.js"></script>
<script src="../Components/DragAndDrop.js"></script>
<script src="../Components/Footer2.js"></script>
<script>
    document.getElementById("footer").innerHTML = Footer2();
</script>

</html>

