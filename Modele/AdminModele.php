<?php
require_once(__DIR__ . '/../Modele/LienPDO.php');


function deleteRow($nomTable, $valeurPk, $initialResponse) {
    $pdo = lienPDO();
    $response = $initialResponse; 

    try {
    
        $stmtCheck = $pdo->prepare("SHOW TABLES LIKE ?");
        $stmtCheck->execute([$nomTable]);
        if ($stmtCheck->rowCount() == 0) {
            $response['message'] = 'Erreur modèle : Table non valide ou non trouvée.';
            return $response;
        }

        // Recherche de la clé primaire
        $nomClePrimaire = null;
        $stmtKeys = $pdo->query("SHOW KEYS FROM `$nomTable` WHERE Key_name = 'PRIMARY'");
        if ($stmtKeys && $keyInfo = $stmtKeys->fetch(PDO::FETCH_ASSOC)) {
            $nomClePrimaire = $keyInfo['Column_name'];
        }

        if (!$nomClePrimaire) {
            $response['message'] = 'Erreur modèle : Impossible de trouver la clé primaire pour la table ' . htmlspecialchars($nomTable) . '.';
            return $response;
        }

        // Préparer et exécuter la suppression
        $sqlDelete = "DELETE FROM `$nomTable` WHERE `$nomClePrimaire` = ?";
        $stmtDelete = $pdo->prepare($sqlDelete);
        $stmtDelete->execute([$valeurPk]); 

        if ($stmtDelete->rowCount() > 0) {
            $response['success'] = true;
            $response['message'] = 'Enregistrement supprimé avec succès depuis le modèle.';
        } else {
            $response['message'] = 'Aucun enregistrement trouvé avec cet ID pour suppression dans le modèle (ou déjà supprimé).';
        }

    } catch (PDOException $e) {
        $response['message'] = 'Erreur SQL dans le modèle lors de la suppression: ' . $e->getMessage();
        error_log("PDOException in deleteRow for table $nomTable, pk $valeurPk: " . $e->getMessage());
    }
    return $response;
}

function validationTable($nomTableDemande){
    try {
        $pdo = lienPDO();
        $stmtCheck = $pdo->prepare("SHOW TABLES LIKE ?");
        $stmtCheck->execute([$nomTableDemande]);
        if ($stmtCheck->rowCount() == 0) {
            return '<p style="color:red;">Erreur : Table non valide.</p>';
            
        }
    } catch (PDOException $e) {
        return '<p style="color:red;">Erreur vérification table: ' . htmlspecialchars($e->getMessage()) . '</p>';
        
    }
}

function recupColonnes($nomTableDemande){
    $pdo = lienPDO();
    $stmtColumns = $pdo->query("DESCRIBE `$nomTableDemande`"); 
    if (!$stmtColumns) { throw new PDOException("Erreur lors de DESCRIBE"); }
    $colonnesDetails = $stmtColumns->fetchAll(PDO::FETCH_ASSOC);
    return $colonnesDetails;
}

function nomClePrimaire($nomTableDemande){
    $pdo = lienPDO();
    $nomClePrimaire = null;
    $stmtKeys = $pdo->query("SHOW KEYS FROM `$nomTableDemande` WHERE Key_name = 'PRIMARY'");
    if ($stmtKeys && $keyInfo = $stmtKeys->fetch(PDO::FETCH_ASSOC)) {
        $nomClePrimaire = $keyInfo['Column_name'];
        return $nomClePrimaire;
    }
}

function showColonnes($nomTableDemande){
    $pdo = lienPDO();
    $recupcol = "SHOW COLUMNS FROM `$nomTableDemande`"; 
    $stmtColonnes = $pdo->query($recupcol);
    $colonnes = $stmtColonnes->fetchAll(PDO::FETCH_COLUMN);
    return $colonnes;
}

function data($nomTableDemande){
    $pdo = lienPDO();
    $sql = "SELECT * FROM `$nomTableDemande`"; 
    $stmtData = $pdo->query($sql);
    return $stmtData;
}

function describeTable($nomTable){
    $pdo = lienPDO();
    $stmtCols = $pdo->query("DESCRIBE `$nomTable`");
    return $stmtCols;
}

function insertLigne($nomTable, $colsString, $placeholders){
    $pdo = lienPDO();
    $sqlInsert = "INSERT INTO `$nomTable` ($colsString) VALUES ($placeholders)";
    $stmtInsert = $pdo->prepare($sqlInsert);
    return $stmtInsert;
}

function updateLigne($nomTable, $nomColonne, $nomClePrimaire){
    $pdo = lienPDO();
    $sqlUpdate = "UPDATE `$nomTable` SET `$nomColonne` = ? WHERE `$nomClePrimaire` = ?";
    $stmtUpdate = $pdo->prepare($sqlUpdate);
    return $stmtUpdate;
}

function recuperationTable(){
    $pdo = getPDO();
    if(!$pdo){
        echo "<a> Erreur: Connexion BDD non fournie.</a>";
    }

    try{
        $recuptable = 'SHOW TABLES';
        $nomtable = $pdo->query($recuptable);
        if($nomtable){
            while ($ligne = $nomtable->fetch(PDO::FETCH_COLUMN)) {
                echo "<a href='javascript:void(0);' id=\"" . htmlspecialchars($ligne) . "\" class=\"deroulement\">" . htmlspecialchars($ligne) . "</a>";
            } 
        }else{
            echo "<a> Echec de récupération </a>";
        }
    } catch (PDOException $e) {
        echo "<a> Erreur BDD lors de la récupération des tables: " . htmlspecialchars($e->getMessage()) . "</a>";
    }  
}
?>