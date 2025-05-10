<?php
//Demarrage session PHP
session_start();
if(isset($_SESSION['user'])) {
    header('Location: home.php');
    exit;
}
require_once(__DIR__ . '/../Modele/User.php');
//formulaire envoyé
if(!empty($_POST)) {
    // Vérification des champs
    if(isset($_POST['email'], $_POST['password'])
        && !empty($_POST['email']) && !empty($_POST['password'])) {

            // email valide ?
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            echo "L'email n'est pas valide.";
            exit;
        }
        
        $user = User::getByEmail($_POST['email']);
        
        if(!$user) {
            echo "Email ou mot de passe incorrect";
            exit;
        }

        if(!password_verify($_POST['password'], $user['password'])) {
            echo "Email ou mot de passe incorrect";
            exit;
        }

        //les 2 sont correctes
        //Stockage dans $_SESSION les infos
        $_SESSION["user"] = [
            'id' => $user['idUtilisateur'],
            'lastname' => $user['nom'],
            'firstname' => $user['prenom'],
            'email' => $user['email'],
            'role' => $user['role'],
            'photoprofil' => $user['photoprofil'],
            'isbanned' => $user['isbanned']
        ];

        //Redirection vers la page d'accueil
        header('Location: ../../Vue/Pages/home.php');
        
    } else { // Si un champ est vide
        echo "Veuillez remplir tous les champs.";
        exit;
    }
}
?>