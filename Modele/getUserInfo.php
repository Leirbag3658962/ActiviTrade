<?php
session_start();
require_once(__DIR__ . '/Database.php');
$pdo = getPDO();

$idUser = $_SESSION['idUser'] ?? 0;

$stmt = $pdo->prepare("SELECT nom, prenom, dateNaissance FROM utilisateur WHERE idUtilisateur = ?");
$stmt->execute([$idUser]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($user);
