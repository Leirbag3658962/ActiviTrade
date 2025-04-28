<!DOCTYPE html>
<html>
<head>
    <title>Administrateur</title>
    <link rel="stylesheet" href="Admin.css"/>
    <script src="AdminPagination.js"></script>
</head>
<body class>
<?php
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
} catch(PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    exit;
}
?>  
<div id="conteneur">
    <!--Barre à gauche-->
    <div class="barreGauche">
        <div id="divGauche">
            <h2 id="titreBarre">Base de données</h2>
            <?php
                $recuptable = 'SHOW TABLES';
                $nomtable = $pdo->query($recuptable);
    
                if($nomtable){
                    while ($ligne = $nomtable->fetch(PDO::FETCH_COLUMN)) {
                    echo "<a id=\"$ligne\" class =\"deroulement\">". htmlspecialchars($ligne) ."</a>";
                } 
            }else{
                echo "<a> Echec de récupération </a>";
            }
            ?>
        </div>
    </div>

    <!--Tableau de données-->
    <div class="barreDroite">
        <p>Sélectionner une table</p>
    </div>
</div>
<div id="add-modal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <span class="close-modal-button">×</span>
        <div id="modal-form-content">
        </div>
    </div>
</div>
</body>
</html>