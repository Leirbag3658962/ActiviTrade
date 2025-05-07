<?php
session_start();
$_SESSION['idUser'] = 1;

require_once "../../Modele/LienPDO.php";
$pdo = lienPDO();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $theme = htmlspecialchars($_POST['theme']);
    $contenu = htmlspecialchars($_POST['contenu']);
    $idUser = $_SESSION['idUser']; 
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
