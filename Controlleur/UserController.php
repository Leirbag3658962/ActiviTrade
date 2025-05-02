<?php
require_once(__DIR__ . '/../Modele/User.php');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";
$db = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

class UserController {
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;

            // ⚠️ Vérification des mots de passe
            if ($data['password'] !== $data['password2']) {
                $error = "Les mots de passe ne correspondent pas.";
                require(__DIR__ . '/../Vue/Pages/SignIn.php');
                return;
            }

            // Vérifie si l’email existe déjà
            if (User::emailExists($data['email'])) {
                $error = "Cet email est déjà utilisé.";
                require(__DIR__ . '/../views/SignIn.php');
                return;
            }

            $token = bin2hex(random_bytes(16));
            $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);

            // Calcule expiration dans 10 minutes
            $expires_at = date('Y-m-d H:i:s', time() + 600);

            User::createPendingUser(array_merge($data, [
                'password_hash' => $password_hash,
                'token' => $token,
                'expires_at' => $expires_at
            ]));

            // 📬 Envoi du mail
            $verifyLink = "http://localhost:8000/verify?token=$token"; // adapte le domaine
            $subject = "Confirme ton inscription";
            $message = "
                <h1>Bienvenue !</h1>
                <p>Clique sur ce lien pour activer ton compte :</p>
                <a href='$verifyLink'>$verifyLink</a>
            ";
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8\r\n";
            $headers .= "From: inscription@activitrade.com\r\n";

            mail($data['email'], $subject, $message, $headers);

            echo "📧 Un email de confirmation t’a été envoyé.";
        } else {
            require(__DIR__ . '/../Vue/Pages/SignIn.php');
        }
    }

    public function verify() {
        $token = $_GET['token'] ?? '';

        if (!$token) {
            echo "Lien invalide.";
            return;
        }

        $pending = User::getPendingByToken($token);

        if (!$pending) {
            echo "Token invalide ou expiré.";
            return;
        }

        User::activatePendingUser($pending);
        User::deletePending($pending['id']);

        echo "✅ Ton compte a été activé ! Tu peux maintenant te connecter.";
    }
}
?>