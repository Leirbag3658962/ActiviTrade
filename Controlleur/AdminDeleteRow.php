<?php
header('Content-Type: application/json');
session_start(); 

require_once(__DIR__ . '/../Modele/LienPDO.php');     
require_once(__DIR__ . '/../Modele/AdminModele.php'); 


$response = ['success' => false, 'message' => 'Initialisation échouée.']; 


if (!isset($_POST['table']) || empty($_POST['table']) ||
    !isset($_POST['pkValue']) || $_POST['pkValue'] === '') {
    $response['message'] = 'Erreur : Informations POST manquantes (table ou pkValue).';
    echo json_encode($response);
    exit;
}

$nomTable = $_POST['table'];
$valeurPk = $_POST['pkValue'];


$finalResponse = deleteRow($nomTable, $valeurPk, $response);

echo json_encode($finalResponse);
?>