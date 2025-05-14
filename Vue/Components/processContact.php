<?php
header('Content-Type: application/json');

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connexion à la base de données
$host = 'localhost';
$port = '3306'; 
$dbname = 'activitrade';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifier si la table contact existe, sinon la créer
    $tableExists = $pdo->query("SHOW TABLES LIKE 'contact'")->rowCount() > 0;
    
    if (!$tableExists) {
        $pdo->exec("CREATE TABLE contact (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL,
            sujet VARCHAR(255) NOT NULL,
            contenu TEXT NOT NULL,
            date DATETIME NOT NULL
        )");
        error_log("Table 'contact' créée avec succès");
    }

    // Récupération des données du formulaire
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';

    // Log des données reçues
    error_log("Données reçues - Nom: $name, Email: $email, Message: $message");

    // Validation des données
    if (empty($name) || empty($email) || empty($message)) {
        throw new Exception('Tous les champs sont obligatoires');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('L\'adresse email n\'est pas valide');
    }

    // Préparation et exécution de la requête
    $sql = "INSERT INTO contact (email, sujet, contenu, date) VALUES (?, ?, ?, NOW())";
    error_log("Requête SQL: " . $sql);
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email, $name, $message]);

    error_log("Message inséré avec succès dans la base de données");
    echo json_encode(['success' => true, 'message' => 'Message envoyé avec succès !']);

} catch (PDOException $e) {
    error_log("Erreur PDO: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur de base de données. Veuillez réessayer.']);
} catch (Exception $e) {
    error_log("Erreur: " . $e->getMessage());
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?> 