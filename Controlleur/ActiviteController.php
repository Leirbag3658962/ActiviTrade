<?php
session_start(); 

require_once(__DIR__ . '../../../Modele/Database.php'); 
require_once(__DIR__ . '../../../Modele/LienPDO.php');   
require_once(__DIR__ . '../../Components/Navbar2.php');
require_once(__DIR__ . '../../../Modele/ActiviteModele.php'); 

$pdo = getPDO(); 

// if (!function_exists('listeCategorie')) { // Définition de secours
//     function listeCategorie($pdo_param) { echo "<option value=''>Chargement...</option>"; /* Implémenter si besoin */ }
// }


$messageSucces = '';
$messageErreur = '';


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!$pdo) {
        $messageErreur = "Erreur critique: Connexion PDO non établie pour le traitement.";
    } else {
        
        $nomActivite = testValidationForm($_POST['inputNom']);
        $dateActivite = testValidationForm($_POST['inputDate']);
        $duree = testValidationForm($_POST['inputDuree']);
        $adresse = testValidationForm($_POST['inputAdresse']);
        $ville = testValidationForm($_POST['inputVille']);
        $categorie = testValidationForm($_POST['inputCategorie']); 
        $nbrParticipant = testValidationForm($_POST['inputNbrParticipant']);
        $groupe = testValidationForm($_POST['Groupe']);
        $prix = testValidationForm($_POST['inputPrix']);
        $descriptionact = testValidationForm($_POST['inputDescription']); 
        $idCreator = $_SESSION['user']['id'];

        
        if (empty($nomActivite) || empty($dateActivite) || empty($adresse) || empty($ville) || empty($duree) || empty($categorie) || empty($nbrParticipant) || empty($groupe) || empty($descriptionact) || $prix === '') {
            $messageErreur = "Veuillez remplir tous les champs obligatoires.";
        } elseif (!filter_var($nbrParticipant, FILTER_VALIDATE_INT) || $nbrParticipant <= 0) {
            $messageErreur = "Le nombre de participants doit être un entier positif.";
        } elseif (!is_numeric($prix) || $prix < 0) {
            $messageErreur = "Le prix doit être un nombre positif ou zéro.";
        } elseif (empty($idCreator)) {
            header("Location: LogIn.php");
        } else {
            
            $uploadDir = __DIR__ . '/../../Uploads/Activites/';
            $uploadedFilesPathsDB = [];

            if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
                $messageErreur = 'Erreur serveur: Impossible de créer le dossier d\'upload (' . $uploadDir .').';
            } else {
                if (isset($_FILES['imageActivite']) && is_array($_FILES['imageActivite']['name'])) {
                    $totalFilesSent = count($_FILES['imageActivite']['name']);
                    $processedFilesCount = 0;

                    for ($i = 0; $i < $totalFilesSent && $processedFilesCount < 3; $i++) {
                        if (isset($_FILES['imageActivite']['tmp_name'][$i]) && is_uploaded_file($_FILES['imageActivite']['tmp_name'][$i]) && $_FILES['imageActivite']['error'][$i] === UPLOAD_ERR_OK) {
                            $fileTmpName = $_FILES['imageActivite']['tmp_name'][$i];
                            $fileName = basename($_FILES['imageActivite']['name'][$i]);
                            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                            $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                            if (!in_array($fileExt, $allowedExt)) { $messageErreur .= "Ext non autorisée: $fileName. "; continue; }
                            if ($_FILES['imageActivite']['size'][$i] > 5 * 1024 * 1024) { $messageErreur .= "Fichier trop gros: $fileName. "; continue; }
                            if (exif_imagetype($fileTmpName) === false) { $messageErreur .= "Fichier non image: $fileName. "; continue; }

                            $newFileName = uniqid('act_' . $idCreator . '_', true) . '.' . $fileExt;
                            $destination = $uploadDir . $newFileName;

                            if (move_uploaded_file($fileTmpName, $destination)) {
                                // CHEMIN POUR LA BDD : Relatif à la racine de votre site web.
                                // Si Uploads est à la racine du projet (ex: ActiviTrade/Uploads)
                                // et que votre site est localhost/ActiviTrade/
                                // et que CreationActivite.php est dans Vue/Pages/
                                // alors le chemin depuis la racine web est 'Uploads/Activites/'
                                $uploadedFilesPathsDB[] = 'Uploads/Activites/' . $newFileName; // A ADAPTER
                                $processedFilesCount++;
                            } else {
                                $messageErreur .= "Erreur déplacement fichier: $fileName. Vérifiez les permissions sur $uploadDir. ";
                            }
                        } elseif (isset($_FILES['imageActivite']['error'][$i]) && $_FILES['imageActivite']['error'][$i] !== UPLOAD_ERR_NO_FILE) {
                            $messageErreur .= "Erreur upload fichier #$i: code " . $_FILES['imageActivite']['error'][$i] . ". ";
                        }
                    }
                }
            }

            
            if (empty($messageErreur)) {
                try {
                    //Il reste les images à gérer
                    create($nomActivite, $dateActivite, $adresse, $ville, $prix, $nbrParticipant,$descriptionact, $duree, $groupe, $idCreator);
                    categorieActivite($idAct, $theme);

                    $stmt->bindParam(':idTheme', $categorie, PDO::PARAM_INT); // Lier l'ID de la catégorie

                    $img1 = $uploadedFilesPathsDB[0] ?? null;
                    $img2 = $uploadedFilesPathsDB[1] ?? null;
                    $img3 = $uploadedFilesPathsDB[2] ?? null;
                    $stmt->bindParam(':img1', $img1);
                    $stmt->bindParam(':img2', $img2);
                    $stmt->bindParam(':img3', $img3);

                    $stmt->execute();
                    $lastIdActivite = $pdo->lastInsertId();

                

                    $messageSucces = "Activité \"".htmlspecialchars($nomActivite)."\" créée avec succès !";
                    $_POST = array();

                } catch (PDOException $e) {
                    $messageErreur = "Erreur BDD insertion : " . $e->getMessage();
                    foreach ($uploadedFilesPathsDB as $webPath) {
                        // Reconstruire le chemin serveur pour unlink
                        // Si $uploadDir est __DIR__ . '/../../Uploads/Activites/'
                        // et $webPath est 'Uploads/Activites/nom_fichier.jpg'
                        // alors il faut reconstruire le chemin serveur à partir de la racine web.
                        // Ceci est une simplification, adaptez à votre structure.
                        // Si __DIR__ est C:/xampp/htdocs/ActiviTrade/Vue/Pages
                        // et Uploads est C:/xampp/htdocs/ActiviTrade/Uploads
                        $serverPath = realpath(__DIR__ . '/../../' . $webPath);
                        if ($serverPath && file_exists($serverPath)) {
                            @unlink($serverPath);
                        }
                    }
                }
            }
        }
    } 
}

function
?>