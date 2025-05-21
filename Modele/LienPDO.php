<?php
function lienPDO(){
    // Pour erreur
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);


    $host = 'localhost';
    $dbname = 'activitrade';
    $username = 'root';
    $password = '';
    $pageActuelle = "utilisateur";

    try {
        $pdo = new PDO("mysql:host=$host;port=3307;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;

    } catch(PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
        exit;
    }
}
?>