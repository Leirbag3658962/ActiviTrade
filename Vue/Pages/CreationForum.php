<?php

session_start();
require_once "../../ModeleB/LienPDO.php";
$pdo = lienPDO();


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = $_POST['title'];
    $context = $_POST['context'];
    $user_id = $_SESSION['user_id']; 
    $date_added = date("Y-m-d H:i:s"); 

    $sql = "INSERT INTO threads (title, context, user_id, date_added) 
            VALUES (:title, :context, :user_id, :date_added)";
    
    
    $stmt = $pdo->prepare($sql);
    
    
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':context', $context);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':date_added', $date_added);
    
    
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
    <link rel="stylesheet" href="../Style/Navbar.css">
    <link rel="stylesheet" href="../Style/Footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Créer un Forum</title>
</head>
<body>
<header id="navbar" class="navbar"></header>

<div class="main-content">
    <h1>Création d'un Nouveau Sujet</h1>
    <div class="createforum-container">
        
        <form action="create-thread.php" method="POST">
            <label for="title">Titre:</label>
            <input type="text" id="title" name="title" required><br>

            <label for="context">Description:</label>
            <textarea id="context" name="context" rows="4" cols="50" required></textarea><br>

            <button id="Button" type="submit">Confirmer</button>
        </form>
    </div>
</div>

<footer id="footer" class="footer"></footer>

<script src="../Components/Navbar.js"></script>
<script>
    document.getElementById("navbar").innerHTML = Navbar();
</script>
<script src="../Components/NavbarAnim.js"></script>
<script src="../Components/Footer.js"></script>
</body>
</html>
