<?php
session_start();
if(isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit;
}
require_once(__DIR__ . '/../Modele/User.php');

//formulaire envoyé
if(!empty($_POST)) {
    // Vérification des champs
    if(isset($_POST['lastname'], $_POST['firstname'], $_POST['email'], $_POST['birthdate'], $_POST['ville'], $_POST['telephone'], $_POST['password'], $_POST['password2'])
        && !empty($_POST['lastname']) && !empty($_POST['firstname']) && !empty($_POST['email']) && !empty($_POST['birthdate']) && !empty($_POST['ville']) && !empty($_POST['telephone']) && !empty($_POST['password']) && !empty($_POST['password2'])) {
        $lastname = testValidationForm($_POST['lastname']);
        $firstname = testValidationForm($_POST['firstname']);
        
        // email valide ?
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            echo "L'email n'est pas valide.";
            exit;
        }
        $email = testValidationForm($_POST['email']);
        $birthdate = testValidationForm($_POST['birthdate']);
        $ville = testValidationForm($_POST['ville']);
        $telephone = testValidationForm($_POST['telephone']);
        $password = testValidationForm($_POST['password']);
        $password2 = testValidationForm($_POST['password2']);

        if($password !== $password2) {
            echo "Les mots de passe ne correspondent pas.";
            exit;
        }

        $password_hash = password_hash($password, PASSWORD_ARGON2ID);

        //Controles
        // Vérification mot de passe conforme aux normes
        $isGoodPassword = true;
        if(!preg_match("#[0-9]+#", $password)) {
            $_SESSION["erreur"] = "Le mot de passe doit contenir au moins un chiffre.";
            header('Location: ../../Vue/Pages/SignIn.php');
            $isGoodPassword = false;
            // exit;
        }
        if(!preg_match("#[a-z]+#", $password)) {
            $_SESSION["erreur"] = "Le mot de passe doit contenir au moins une lettre minuscule.";
            header('Location: ../../Vue/Pages/SignIn.php');
            $isGoodPassword = false;
            // exit;
        }
        if(!preg_match("#[A-Z]+#", $password)) {
            $_SESSION["erreur"] = "Le mot de passe doit contenir au moins une lettre majuscule.";
            header('Location: ../../Vue/Pages/SignIn.php');
            $isGoodPassword = false;
            // exit;
        }
        if(!preg_match("#\W+#", $password)) {
            $_SESSION["erreur"] = "Le mot de passe doit contenir au moins un caractère spécial.";
            header('Location: ../../Vue/Pages/SignIn.php');
            $isGoodPassword = false;
            // exit;
        }
        if(strlen($password) < 8) {
            $_SESSION["erreur"] = "Le mot de passe doit contenir au moins 8 caractères.";
            header('Location: ../../Vue/Pages/SignIn.php');
            $isGoodPassword = false;
            // exit;
        }

        if(User::emailExists($email)) {
            $_SESSION["erreur"] = "L'email existe déjà.";
            header('Location: ../../Vue/Pages/SignIn.php');
            $isGoodPassword = false;
            // exit;
        }

        if(!$isGoodPassword) {
            header('Location: ../../Vue/Pages/SignIn.php');
            exit;
        }

        $id = User::create($lastname, $firstname, $email, $birthdate, $ville, $telephone, $password_hash);

        //Stockage dans $_SESSION les infos
        $_SESSION["user"] = [
            'id' => $id,
            'lastname' => $lastname,
            'firstname' => $firstname,
            'email' => $_POST['email'],
            'role' => ['user'],
            'photoprofil' => $user['photoprofil'] || null,
            'isbanned' => 0
        ];

        //Redirection vers la page d'accueil
        header('Location: ../../index.php');

    } else { // Si un champ est vide
        echo "Veuillez remplir tous les champs.";
        exit;
    }
}
?>