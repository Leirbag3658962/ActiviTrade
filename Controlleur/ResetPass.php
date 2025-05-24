<?php
require_once(__DIR__ . '../../Modele/User.php');

$token = $_GET["token"];
$token_hash = hash("sha256", $token);

$result = User::getResetToken($token_hash);

$result = $query->fetch(PDO::FETCH_ASSOC);
if ($result ==  null){
    die("token not found");

}
if (strtotime($result["reset_token_expires_at"]) < time()){
    die("token expired");
}
echo "token valid";
    
?>