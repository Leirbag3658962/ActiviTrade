<?php
session_start();
require_once(__DIR__ . '../../Components/Navbar2.php');
require_once(__DIR__ . '../../../Modele/AdminModele.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Administrateur</title>
    <link rel="stylesheet" href="../Style/Admin.css"/>
    <link rel="stylesheet" href="../Style/navbar2.css"/>
    <script src="../Components/AdminPagination.js"></script>
</head>
<body class>
<div id="conteneur">
    <header id="navbar" class="navbar">
        <?php echo Navbar2(); ?>
    </header>
    <!--Barre à gauche-->
    <div class="barreGauche">
        <div id="divGauche">
            <h2 id="titreBarre">Base de données</h2>
            <?php
                recuperationTable(); 
            ?>
        </div>
    </div>

    <!--Tableau de données-->
    <div class="barreDroite">
        <p>Sélectionner une table</p>
    </div>

    <div id="add-modal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <span class="close-modal-button">×</span>
            <div id="modal-form-content">
            </div>
        </div>
    </div>
</div>
</body>
</html>