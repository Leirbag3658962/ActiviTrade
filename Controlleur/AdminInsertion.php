<?php
session_start();
header('Content-Type: application/json');
require_once(__DIR__ . '/../Modele/LienPDO.php');
require_once(__DIR__ . '/../Modele/AdminModele.php');
$pdo = lienPDO();

$response = ['success' => false]; 

// Validation existence table
if (!isset($_POST['table']) || empty($_POST['table'])) {
    $response['message'] = 'Nom de table manquant.';
    echo json_encode($response); exit;
}
$nomTable = $_POST['table'];
$formData = $_POST; 
unset($formData['table']); 




try {
    // Validation existence table
    $messErreur = '';
    $messErreur = validationTable($nomTable);
    if($messErreur != ''){
        $response['message'] = 'Table non valide.';
        echo json_encode($response); exit;
    }

    // Récupérer colonnes 
    $validColumns = [];
    $stmtCols = describeTable($nomTable);
    if ($stmtCols) {
        while ($col = $stmtCols->fetch(PDO::FETCH_ASSOC)) {
            if (!(strtoupper($col['Key']) === 'PRI' && strpos(strtolower($col['Extra']), 'auto_increment') !== false)) {
                 if(array_key_exists($col['Field'], $formData)) {
                    $validColumns[] = $col['Field'];
                 }
            }
        }
    } else {
         $response['message'] = 'Impossible de récupérer la structure de la table.';
         echo json_encode($response); exit;
    }

    if (empty($validColumns)) {
         $response['message'] = 'Aucune colonne valide à insérer trouvée ou fournie.';
         echo json_encode($response); exit;
    }


    
    $colsString = "`" . implode("`, `", $validColumns) . "`";
    $placeholders = implode(", ", array_fill(0, count($validColumns), '?'));

    $stmtInsert = insertLigne($nomTable, $colsString, $placeholders);

    
    $values = [];
    foreach ($validColumns as $colName) {
        $values[] = $formData[$colName] === '' ? null : $formData[$colName];
    }

    // Exécuter l'insertion
    if ($stmtInsert->execute($values)) {
        $response['success'] = true;
        $response['message'] = 'Enregistrement ajouté avec succès.';
    } else {
        $response['message'] = 'L\'insertion a échoué sans lever d\'exception PDO.';
    }

} catch (PDOException $e) {
    $response['message'] = 'Erreur SQL lors de l\'insertion: ' . $e->getMessage();
}

echo json_encode($response);
?>