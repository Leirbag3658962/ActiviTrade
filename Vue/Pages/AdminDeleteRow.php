<?php
header('Content-Type: application/json');

require_once "../../ModeleB/LienPDO.php";
$pdo = lienPDO();


if (!isset($_POST['table']) || empty($_POST['table']) || !isset($_POST['pkValue']) || $_POST['pkValue'] === '') {
    echo json_encode(['success' => false, 'message' => 'Erreur : Informations manquantes (table ou clé primaire).']);
    exit;
}

$nomTable = $_POST['table'];
$valeurPk = $_POST['pkValue'];
$response = ['success' => false]; 




try {
    
    $stmtCheck = $pdo->prepare("SHOW TABLES LIKE ?");
    $stmtCheck->execute([$nomTable]);
    if ($stmtCheck->rowCount() == 0) {
        $response['message'] = 'Erreur : Table non valide ou non trouvée.';
        echo json_encode($response);
        exit;
    }

    // Recherche dans la bdd
    $nomClePrimaire = null;
    $stmtKeys = $pdo->query("SHOW KEYS FROM `$nomTable` WHERE Key_name = 'PRIMARY'");
    if ($stmtKeys && $keyInfo = $stmtKeys->fetch(PDO::FETCH_ASSOC)) {
        $nomClePrimaire = $keyInfo['Column_name'];
    }

    //En cas d'erreur
    if (!$nomClePrimaire) {
        $response['message'] = 'Erreur : Impossible de trouver la clé primaire pour la table ' . htmlspecialchars($nomTable) . '.';
        echo json_encode($response);
        exit;
    }

    
    $sqlDelete = "DELETE FROM `$nomTable` WHERE `$nomClePrimaire` = ?";
    $stmtDelete = $pdo->prepare($sqlDelete);
    $stmtDelete->execute([$valeurPk]);

    
    if ($stmtDelete->rowCount() > 0) {
        $response['success'] = true;
        $response['message'] = 'Enregistrement supprimé avec succès.';
    } else {
        //En cas d'erreur
        $response['message'] = 'Aucun enregistrement trouvé avec cet ID pour suppression.';
    }

} catch (PDOException $e) {
    $response['message'] = 'Erreur SQL lors de la suppression: ' . $e->getMessage();
}

// Envoyer réponse 
echo json_encode($response);
?>