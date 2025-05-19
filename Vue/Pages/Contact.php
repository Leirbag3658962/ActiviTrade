<?php

session_start();
require_once(__DIR__ . '../../../Modele/Database.php');
require_once(__DIR__ . '../../Components/Navbar2.php');
require_once(__DIR__ . '../../Components/Footer2.php');

// Configuration de la base de données
$host = 'localhost';
$port = '3306';
$dbname = 'activitrade';
$user = 'root';
$password = '';


// Variables pour stocker les messages
$statusMessage = "";
$statusClass = "";




// Modifier ces lignes dans votre code
$userLoggedIn = isset($_SESSION['user']) && !empty($_SESSION['user']['id']);
$userNom = $userLoggedIn ? $_SESSION['user']['lastname'] . ' ' . $_SESSION['user']['firstname'] : '';
$userEmail = $userLoggedIn ? $_SESSION['user']['email'] : '';


// Traitement du formulaire lors de la soumission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = htmlspecialchars($_POST['nom'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');
    
    // Validation basique
    if (empty($nom) || empty($email) || empty($message)) {
        $statusMessage = "Veuillez remplir tous les champs obligatoires.";
        $statusClass = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $statusMessage = "L'adresse e-mail n'est pas valide.";
        $statusClass = "error";
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
            
            $stmt->execute();
            
            $statusMessage = "Votre message a été envoyé avec succès!";
            $statusClass = "success";
            
            // Réinitialiser les champs si ce n'est pas un utilisateur connecté
            if (!$userLoggedIn) {
               $nom = $email = $message = "";
            }
            
        } catch(PDOException $e) {
            $statusMessage = "Erreur lors de l'envoi du message: " . $e->getMessage();
            $statusClass = "error";
        }
        
        $conn = null;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactez-nous | ActiVitrade</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../Style/Contact.css">
    <link rel="stylesheet" href="/Vue/Style/Navbar2.css">
    <link rel="stylesheet" href="/Vue/Style/Footer2.css">
    
</head>
<body>
    <header id="navbar" class="navbar">
        <?php echo Navbar2(); ?>
    </header>
 <!-- Contact Section -->
 <section class="contact-section">
        <div class="container">
            <div class="contact-container">
                <div class="contact-info">
                    <h1>Contactez-nous</h1>
                    <p>N'hésitez pas à utiliser le formulaire ou à nous contacter directement. 
                       Les appels téléphoniques traditionnels fonctionnent aussi.</p>
                    
                    <div class="contact-details">
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div class="contact-text">
                                <a href="tel:+33123456789">+33 1 23 45 67 89</a>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-text">
                                <a href="mailto:contact@activitrade.com">contact@activitrade.com</a>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-text">
                                <p>123 Rue du Commerce<br>75015 Paris, France</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="contact-form-container">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label for="nom">Nom</label>
                            <input type="text" id="nom" name="nom" value="<?php echo $userLoggedIn ? $userNom : ($nom ?? ''); ?>" required <?php echo $userLoggedIn ? 'readonly' : ''; ?>>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?php echo $userLoggedIn ? $userEmail : ($email ?? ''); ?>" required <?php echo $userLoggedIn ? 'readonly' : ''; ?>>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" required><?php echo $message ?? ''; ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn">Envoyer</button>
                        
                        <?php if (!empty($statusMessage)): ?>
                        <div class="message-status <?php echo $statusClass; ?>">
                            <?php echo $statusMessage; ?>
                        </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>ActiviTrade</h3> //mettre logo activitrave
                    <p>Votre partenaire dans le développement commercial et la croissance de votre entreprise.</p> // mettre les logo reseaux sociaux svg telecharge
                </div>
                
                <div class="footer-column">
                    <h3>Liens rapides</h3> // activites
                    <ul class="footer-links">
                        <li><a href="index.php">Accueil</a></li> //diff theme ex sport
                        <li><a href="services.php">Services</a></li> // looisir (prendre de la bdd)
                        <li><a href="about.php">À propos</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Services</h3> // toutes les autres pages comme creer activites, faq, mon compte etc
                    <ul class="footer-links">
                        <li><a href="services.php#consulting">Consulting</a></li>
                        <li><a href="services.php#marketing">Marketing</a></li>
                        <li><a href="services.php#development">Développement</a></li>
                        <li><a href="services.php#analytics">Analytique</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Contact</h3> //enlever
                    <ul class="footer-links">
                        <li><i class="fas fa-map-marker-alt"></i> 123 Rue du Commerce, Paris</li>
                        <li><i class="fas fa-phone"></i> +33 1 23 45 67 89</li>
                        <li><i class="fas fa-envelope"></i> contact@activitrade.com</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> ActiviTrade. Tous droits réservés.</p> // vous avez une questions? contacytez nous
            </div>
        </div>
    </footer>
</body>
</html>