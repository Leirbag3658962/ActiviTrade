<?php
session_start();
if(isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit;
}

//formulaire envoyé
if(!empty($_POST)) {
    // Vérification des champs
    if(isset($_POST['lastname'], $_POST['firstname'], $_POST['email'], $_POST['birthdate'], $_POST['numeroRue'], $_POST['nomRue'], $_POST['codePostal'], $_POST['ville'], $_POST['pays'], $_POST['indicatif'], $_POST['telephone'], $_POST['password'], $_POST['password2'])
        && !empty($_POST['lastname']) && !empty($_POST['firstname']) && !empty($_POST['email']) && !empty($_POST['birthdate']) && !empty($_POST['numeroRue']) && !empty($_POST['nomRue']) && !empty($_POST['codePostal']) && !empty($_POST['ville']) && !empty($_POST['pays']) && !empty($_POST['indicatif']) && !empty($_POST['telephone']) && !empty($_POST['password']) && !empty($_POST['password2'])) {
        $lastname = strip_tags($_POST['lastname']);
        $firstname = strip_tags($_POST['firstname']);
        
        // email valide ?
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            echo "L'email n'est pas valide.";
            exit;
        }
        $email = strip_tags($_POST['email']);
        $birthdate = strip_tags($_POST['birthdate']);
        $numeroRue = strip_tags($_POST['numeroRue']);
        $nomRue = strip_tags($_POST['nomRue']);
        $codePostal = strip_tags($_POST['codePostal']);
        $ville = strip_tags($_POST['ville']);
        $pays = strip_tags($_POST['pays']);
        $indicatif = strip_tags($_POST['indicatif']);
        $telephone = strip_tags($_POST['telephone']);
        $password = strip_tags($_POST['password']);
        $password2 = strip_tags($_POST['password2']);

        if($password !== $password2) {
            echo "Les mots de passe ne correspondent pas.";
            exit;
        }

        $password_hash = password_hash($password, PASSWORD_ARGON2ID);

        //Controles

        // BDD Connexion
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "activitrade";
        $db = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO `utilisateur`(`nom`, `prenom`, `email`, `dateNaissance`, `numeroRue`, `nomRue`, `codePostal`, `ville`, `pays`, `indicatif`, `telephone`, `role`, `password`) VALUES (:lastname, :firstname, :email, :birthdate, :numeroRue, :nomRue, :codePostal, :ville, :pays, :indicatif, :telephone, 'user', '$password_hash')";
        
        $query = $db->prepare($sql);

        $query->bindValue(':lastname', $lastname, PDO::PARAM_STR);
        $query->bindValue(':firstname', $firstname, PDO::PARAM_STR);
        $query->bindValue(':email', $email, PDO::PARAM_STR);
        $query->bindValue(':birthdate', $birthdate, PDO::PARAM_STR);
        $query->bindValue(':numeroRue', $numeroRue, PDO::PARAM_STR);
        $query->bindValue(':nomRue', $nomRue, PDO::PARAM_STR);
        $query->bindValue(':codePostal', $codePostal, PDO::PARAM_STR);
        $query->bindValue(':ville', $ville, PDO::PARAM_STR);
        $query->bindValue(':pays', $pays, PDO::PARAM_STR);
        $query->bindValue(':indicatif', $indicatif, PDO::PARAM_STR);
        $query->bindValue(':telephone', $telephone, PDO::PARAM_STR);

        $query->execute();

        // Récupération de l'utilisateur
        $id = $db->lastInsertId();

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