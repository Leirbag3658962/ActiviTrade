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
    $port = '3306';
    $pageActuelle = "utilisateur";

    try {

        $pdo = new PDO("mysql:host=$host;port=3306;dbname=$dbname;charset=utf8", $username, $password);

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        error_log("Connexion à la base de données réussie");
        return $pdo;

    } catch(PDOException $e) {
        error_log("Erreur de connexion à la base de données: " . $e->getMessage());
        throw new Exception("Erreur de connexion à la base de données: " . $e->getMessage());
    }
}
?>