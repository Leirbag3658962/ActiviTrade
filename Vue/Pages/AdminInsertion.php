<?php
session_start();
header('Content-Type: application/json');

// Pour erreur
ini_set('display_errors', 1); error_reporting(E_ALL);

$host = 'localhost'; 
$dbname = 'activitrade'; 
$username = 'root'; 
$password = '';

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
    $pdo = new PDO("mysql:host=$host;port=3307;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $response['message'] = 'Erreur Connexion DB: ' . $e->getMessage();
    echo json_encode($response); exit;
}

try {
    // Validation existence table
    $stmtCheck = $pdo->prepare("SHOW TABLES LIKE ?");
    $stmtCheck->execute([$nomTable]);
    if ($stmtCheck->rowCount() == 0) {
        $response['message'] = 'Table non valide.';
        echo json_encode($response); exit;
    }

    // Récupérer colonnes 
    $validColumns = [];
    $stmtCols = $pdo->query("DESCRIBE `$nomTable`");
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

    $sqlInsert = "INSERT INTO `$nomTable` ($colsString) VALUES ($placeholders)";

    $stmtInsert = $pdo->prepare($sqlInsert);

    
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