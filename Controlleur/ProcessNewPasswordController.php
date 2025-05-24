<?php
require_once("../Modele/User.php");

$token = $_POST["token"];
$token_hash = hash("sha256", $token);

var_dump($token);
var_dump($token_hash);

$result = User::getResetToken($token_hash);

if ($result ==  null){
    die("token not found");
}
if (strtotime($result["reset_token_expires_at"]) < time()){
    die("token expired");
}

$password = $_POST["password"];
$password2 = $_POST["password2"];
if($password != $password2){
    die("Les mots de passe ne correspondent pas");
}

if(strlen($password) < 8){
    $_SESSION["erreur"] = "Le mot de passe doit contenir au moins 8 caractères";
    header("Location: ../../Vue/Pages/SignIn.php");
    exit;
}

if(!preg_match("/[0-9]/", $password)){
    $_SESSION["erreur"] = "Le mot de passe doit contenir au moins un chiffre";
    header("Location: ../../Vue/Pages/SignIn.php");
    exit;
}

if(!preg_match("/[A-Z]/", $password)){
    $_SESSION["erreur"] = "Le mot de passe doit contenir au moins une majuscule";
    header("Location: ../../Vue/Pages/SignIn.php");
    exit;
}

if(!preg_match("/[a-z]/", $password)){
    $_SESSION["erreur"] = "Le mot de passe doit contenir au moins une minuscule";
    header("Location: ../../Vue/Pages/SignIn.php");
    exit;
}

if(!preg_match("/[!@#$%^&*(),.?\":{}|<>]/", $password)){
    $_SESSION["erreur"] = "Le mot de passe doit contenir au moins un caractère spécial";
    header("Location: ../../Vue/Pages/SignIn.php");
    exit;
}

$pass = password_hash($password, PASSWORD_ARGON2ID);
User::updatePassword($result["idUtilisateur"], $pass);

header("Location: ../../Vue/Pages/LogIn.php");

echo "Mot de passe mis à jour avec succès !";
?>