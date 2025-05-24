<?php
// Fichier de traitement du formulaire à placer dans un dossier Components
// Exemple: Components/processContact.php

// Configuration de la base de données
$host = 'localhost';
$port = '3306';
$dbname = 'activitrade';
$user = 'root';
$password = '';

// Initialisation de la réponse
$response = array(
    'status' => 'error',
    'message' => 'Une erreur est survenue.'
);

// Vérification si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération et nettoyage des données
    $nom = isset($_POST['nom']) ? htmlspecialchars(trim($_POST['nom'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $message = isset($_POST['message']) ? htmlspecialchars(trim($_POST['message'])) : '';
    
    // Validation des données
    if (empty($nom)) {
        $response['message'] = 'Le nom est requis.';
    } elseif (empty($email)) {
        $response['message'] = 'L\'email est requis.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'L\'email n\'est pas valide.';
    } elseif (empty($message)) {
        $response['message'] = 'Le message est requis.';
    } else {
        try {
            // Connexion à la base de données
            $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Préparation et exécution de la requête
            $stmt = $conn->prepare("INSERT INTO contact (nom, email, message, date) 
                                   VALUES (:nom, :email, :message, NOW())");
            
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':message', $message);
            
            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Votre message a été envoyé avec succès!';
                
                // Envoi d'un email de notification (optionnel)
                $to = "admin@activitrade.com"; // Remplacer par votre adresse email
                $subject = "Nouveau message de contact de $nom";
                $messageBody = "Nouveau message de contact reçu :\n\n";
                $messageBody .= "Nom: $nom\n";
                $messageBody .= "Email: $email\n";
                $messageBody .= "Message:\n$message";
                $headers = "From: $email";
                
                mail($to, $subject, $messageBody, $headers);
            }
            
        } catch(PDOException $e) {
            $response['message'] = 'Erreur lors de l\'envoi du message: ' . $e->getMessage();
        }
        
        $conn = null;
    }
}

// Retour de la réponse en JSON
header('Content-Type: application/json');
echo json_encode($response);
exit;
?>