<?php
session_start();
header('Content-Type: application/json');

require_once "../../Modele/LienPDO.php"; 
$pdo = lienPDO(); 

$response = ['success' => false]; 


if (!isset($_POST['table']) || empty($_POST['table']) ||
    !isset($_POST['pkValue']) || $_POST['pkValue'] === '' || 
    !isset($_POST['column']) || empty($_POST['column']) ||
    !isset($_POST['value'])  ) {

    $response['message'] = 'Erreur : Informations manquantes (table, pkValue, column ou value).';
    echo json_encode($response);
    exit;
}

$nomTable = $_POST['table'];
$valeurPk = $_POST['pkValue'];
$nomColonne = $_POST['column'];
$nouvelleValeur = $_POST['value'];


if (!$pdo) {
    $response['message'] = 'Erreur critique : La connexion PDO n\'est pas établie.';
    echo json_encode($response);
    exit;
}


try {
    
    $stmtCheckTable = $pdo->prepare("SHOW TABLES LIKE ?");
    $stmtCheckTable->execute([$nomTable]);
    if ($stmtCheckTable->rowCount() == 0) {
        $response['message'] = 'Erreur : La table "' . htmlspecialchars($nomTable) . '" n\'existe pas ou n\'est pas autorisée.';
        echo json_encode($response);
        exit;
    }

    
    $nomClePrimaire = null;
    $colonneExiste = false;
    $estColonnePk = false;

    
    $stmtCols = $pdo->query("DESCRIBE `$nomTable`");
    if ($stmtCols) {
        while ($col = $stmtCols->fetch(PDO::FETCH_ASSOC)) {
            if (strtoupper($col['Key']) === 'PRI') {
                $nomClePrimaire = $col['Field'];
            }
            if ($col['Field'] === $nomColonne) {
                $colonneExiste = true;
                 if (strtoupper($col['Key']) === 'PRI') {
                     $estColonnePk = true; 
                 }
            }
        }
    } else {
        $response['message'] = 'Impossible de récupérer la structure de la table.';
        echo json_encode($response);
        exit;
    }

    if (!$nomClePrimaire) {
        $response['message'] = 'Erreur : Aucune clé primaire trouvée pour la table "' . htmlspecialchars($nomTable) . '".';
        echo json_encode($response);
        exit;
    }
    if (!$colonneExiste) {
        $response['message'] = 'Erreur : La colonne "' . htmlspecialchars($nomColonne) . '" n\'existe pas dans la table "' . htmlspecialchars($nomTable) . '".';
        echo json_encode($response);
        exit;
    }

    if ($estColonnePk) {
        $response['message'] = 'Erreur : La modification directe de la clé primaire (' . htmlspecialchars($nomColonne) . ') n\'est pas autorisée.';
        echo json_encode($response);
        exit;
    }


    $sqlUpdate = "UPDATE `$nomTable` SET `$nomColonne` = ? WHERE `$nomClePrimaire` = ?";
    $stmtUpdate = $pdo->prepare($sqlUpdate);

    $valeurAInserer = ($nouvelleValeur === '') ? null : $nouvelleValeur;

    //MAj
    if ($stmtUpdate->execute([$valeurAInserer, $valeurPk])) {

        if ($stmtUpdate->rowCount() > 0) {
            $response['success'] = true;
            $response['message'] = 'Enregistrement mis à jour avec succès.';
            $response['newValue'] = ($valeurAInserer === null) ? '' : $nouvelleValeur;

        } else {
             $response['success'] = true; 
             $response['message'] = 'Aucune modification nécessaire (valeur identique ou enregistrement non trouvé).';
             $response['newValue'] = $nouvelleValeur; 
            }
    } else {
        $response['message'] = 'La requête de mise à jour a échoué.';
    }

} catch (PDOException $e) {
    $response['message'] = 'Erreur SQL lors de la mise à jour: ' . $e->getMessage();
}

echo json_encode($response);
?>
