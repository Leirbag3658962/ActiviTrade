<?php
session_start();
require_once(__DIR__ . '../../../Modele/Database.php');
require_once(__DIR__ . '../../Components/Navbar2.php');
$pdo = getPDO();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

//$_SESSION['idActivite'] = 1;
//$_SESSION['idUser'] = 1;

$idUser = $_SESSION['idUser'] ?? null;
$idActivite = $_SESSION['idActivite'] ?? null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $note = htmlspecialchars($_POST['inputNote']);
    $commentaire = htmlspecialchars($_POST['inputCommentaire']);

    if (is_numeric($note) && $note >= 0 && $note <= 5) {
        if ($idUser && $idActivite) {
            $sql = "INSERT INTO avis (note, contenu, date, idUser, idActivite) 
                    VALUES (:note, :contenu, NOW(), :idUser, :idActivite)";
            try {
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':note', $note, PDO::PARAM_INT);
                $stmt->bindParam(':contenu', $commentaire, PDO::PARAM_STR);
                $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
                $stmt->bindParam(':idActivite', $idActivite, PDO::PARAM_INT);
                $stmt->execute();

                echo "<div class='result'>";
                echo "<h3>Merci pour votre avis !</h3>";
                echo "<p><strong>Note :</strong> " . htmlspecialchars($note) . "</p>";
                echo "<p><strong>Commentaire :</strong> " . nl2br(htmlspecialchars($commentaire)) . "</p>";
                echo "</div>";
            } catch (PDOException $e) {
                echo "<p style='color:red;'>Erreur PDO : " . $e->getMessage() . "</p>";
            }
        } else {
            echo "<p style='color:red;'>Utilisateur ou activité non défini(e).</p>";
        }
    } else {
        echo "<p style='color:red;'>Veuillez saisir une note valide entre 0 et 5.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../Style/Avis.css">
<link rel="stylesheet" href="../Style/Navbar2.css">
<link rel="stylesheet" href="../Style/Footer2.css">
<title>Avis</title>
</head>
<body>
<header id="navbar" class="navbar">
    <?php echo Navbar2(); ?>
</header>

<div class="boxform">
    <form method="POST" action="">
        <label for="inputNote" class="label-bold">Note d'activité</label>
        <br>
        <input class="input" type="text" id="inputNote" name="inputNote" required>&nbsp;&nbsp;&nbsp;&nbsp;
        <span class="note-text">Saisir une note de 0 à 5</span>
        <br><br>
        <label for="inputCommentaire" class="label-bold">Commentaire</label>
        
        <textarea id="inputCommentaire" name="inputCommentaire" rows="10" cols="80" required></textarea><br><br>
        <div class="button-container">
            <button id="Button" type="submit">Confirmer</button>
        </div>
    </form>
</div>

<footer id="footer" class="footer"></footer>

</body>
<!-- <script src="../Components/Navbar2.js"></script>
<script>
    document.getElementById("navbar").innerHTML = Navbar2();
</script> -->
<script src="../Components/NavbarAnim.js"></script>
<script src="../Components/Footer2.js"></script>
<script>
    document.getElementById("footer").innerHTML = Footer2();
</script>
</html>

