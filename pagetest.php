<?php
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = 'localhost';
    $dbname = 'revisionsite';
    $user = 'root';
    $pass = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }

    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if ($login === '' || $password === '' || $password2 === '') {
        $message = "❌ Veuillez remplir tous les champs.";
    } elseif (strlen($password) < 8) {
        $message = "❌ Le mot de passe doit contenir au moins 8 caractères.";
    } elseif ($password !== $password2) {
        $message = "❌ Les mots de passe ne correspondent pas.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM Utilisateur WHERE login = :login");
        $stmt->execute([':login' => $login]);

        if ($stmt->fetch()) {
            $message = "❌ Ce login est déjà utilisé.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            try {
                $stmt = $pdo->prepare("INSERT INTO Utilisateur (login, nom, prenom, password) VALUES (:login, '', '', :password)");
                $stmt->execute([':login' => $login, ':password' => $hashedPassword]);
                $message = "✅ Inscription réussie ! Bienvenue $login.";
            } catch (PDOException $e) {
                $message = "❌ Erreur lors de l'inscription : " . $e->getMessage();
            }
        }
    }
} else {
    $message = "❌ Méthode non autorisée.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Résultat inscription</title>
    <link rel="stylesheet" href="pagetest.css">
</head>
<body>

<h1>Résultat inscription</h1>

<p><?= htmlspecialchars($message) ?></p>

<p><a href="pagetest.html">← Retour au formulaire</a></p>

</body>
</html>