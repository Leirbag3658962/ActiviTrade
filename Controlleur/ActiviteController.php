<?php
session_start(); 

require_once(__DIR__ . '/../Modele/Database.php');
require_once(__DIR__ . '/../Modele/LienPDO.php');
require_once(__DIR__ . '/../Modele/ActiviteModele.php');

$pdo = getPDO(); 

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
        // } elseif (empty($idCreator)) {
        //     header("Location: ../../Vue/Pages/LogIn.php");
        } else {
            
            $uploadDir = __DIR__ . '/../Vue/img/Uploads/Activites/';
            $uploadedFilesPathsDB = [];

            if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
                $messageErreur = 'Erreur serveur: Impossible de créer le dossier d\'upload (' . $uploadDir .').';
            } else {
                if (isset($_FILES['ImageInput']) && is_array($_FILES['ImageInput']['name'])) {
                    //var_dump($_FILES['ImageInput']); exit;

                    $totalFilesSent = count($_FILES['ImageInput']['name']);
                    $processedFilesCount = 0;

                    for ($i = 0; $i < $totalFilesSent && $processedFilesCount < 3; $i++) {
                        if (isset($_FILES['ImageInput']['tmp_name'][$i]) && is_uploaded_file($_FILES['ImageInput']['tmp_name'][$i]) && $_FILES['ImageInput']['error'][$i] === UPLOAD_ERR_OK) {
                            $fileTmpName = $_FILES['ImageInput']['tmp_name'][$i];
                            $fileName = basename($_FILES['ImageInput']['name'][$i]);
                            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                            $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                            if (!in_array($fileExt, $allowedExt)) { $messageErreur .= "Ext non autorisée: $fileName. "; continue; }
                            if ($_FILES['ImageInput']['size'][$i] > 5 * 1024 * 1024) { $messageErreur .= "Fichier trop gros: $fileName. "; continue; }
                            if (exif_imagetype($fileTmpName) === false) { $messageErreur .= "Fichier non image: $fileName. "; continue; }

                            $newFileName = uniqid('act_' . $idCreator . '_', true) . '.' . $fileExt;
                            $destination = $uploadDir . $newFileName;
                            

                            if (move_uploaded_file($fileTmpName, $destination)) {
                                $uploadedFilesPathsDB[] = '/Vue/img/Uploads/Activites/' . $newFileName; // A ADAPTER
                                $processedFilesCount++;
                            } else {
                                $messageErreur .= "Erreur déplacement fichier: $fileName. Vérifiez les permissions sur $uploadDir. ";
                            }
                        } elseif (isset($_FILES['ImageInput']['error'][$i]) && $_FILES['ImageInput']['error'][$i] !== UPLOAD_ERR_NO_FILE) {
                            $messageErreur .= "Erreur upload fichier #$i: code " . $_FILES['ImageInput']['error'][$i] . ". ";
                        }
                    }
                }
            }

            
            if (empty($messageErreur)) {
                try {
                    //Il reste les images à gérer
                    $idAct = Activite::create($nomActivite, $dateActivite, $adresse, $ville, $prix, $nbrParticipant,$descriptionact, $duree, $groupe, $idCreator);
                    
                    $img1 = $uploadedFilesPathsDB[0] ?? null;
                    $img2 = $uploadedFilesPathsDB[1] ?? null;
                    $img3 = $uploadedFilesPathsDB[2] ?? null;
                    if($img1){
                       imageActivite($idAct, $img1); 
                    }
                    if($img2){
                       imageActivite($idAct, $img2); 
                    }
                    if($img3){
                       imageActivite($idAct, $img3); 
                    }

                    categorieActivite($idAct, $categorie);

                    $messageSucces = "Activité \"".htmlspecialchars($nomActivite)."\" créée avec succès !";
                    $_POST = array();

                    header('Location: ../../Vue/Pages/Home.php');

                } catch (PDOException $e) {
                    $messageErreur = "Erreur BDD insertion : " . $e->getMessage();
                }
            }
        }
    } 
}
?>