<?php
require_once(__DIR__ . '/../config/database.php');

class User {
    public static function getPDO() {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "activitrade";
        $db = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    }

    public static function emailExists($email) {
        $pdo = self::getPDO();
        $stmt = $pdo->prepare("
            SELECT email FROM utilisateur WHERE email = ?
            UNION
            SELECT email FROM pending_users WHERE email = ?
        ");
        $stmt->execute([$email, $email]);
        return $stmt->fetch() !== false;
    }

    public static function createPendingUser($data) {
        $pdo = self::getPDO();
        $stmt = $pdo->prepare("
            INSERT INTO pending_users
            (nom, prenom, email, dateNaissance, numeroRue, nomRue, codePostal, ville, pays, indicatif, telephone, password_hash, token, expires_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['lastname'],
            $data['firstname'],
            $data['email'],
            $data['birthdate'],
            $data['numeroRue'],
            $data['nomRue'],
            $data['codePostal'],
            $data['ville'],
            $data['pays'],
            $data['indicatif'],
            $data['telephone'],
            $data['password_hash'],
            $data['token'],
            $data['expires_at']
        ]);
    }

    public static function getPendingByToken($token) {
        $pdo = self::getPDO();
        $stmt = $pdo->prepare("
            SELECT * FROM pending_users
            WHERE token = ? AND expires_at > NOW()
        ");
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function activatePendingUser($data) {
        $pdo = self::getPDO();
        $stmt = $pdo->prepare("
            INSERT INTO utilisateur
            (nom, prenom, email, dateNaissance, numeroRue, nomRue, codePostal, ville, pays, indicatif, telephone, password, role, photoprofil, isbanned)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'user', NULL, 0)
        ");
        $stmt->execute([
            $data['nom'],
            $data['prenom'],
            $data['email'],
            $data['dateNaissance'],
            $data['numeroRue'],
            $data['nomRue'],
            $data['codePostal'],
            $data['ville'],
            $data['pays'],
            $data['indicatif'],
            $data['telephone'],
            $data['password_hash']
        ]);
    }

    public static function deletePending($id) {
        $pdo = self::getPDO();
        $stmt = $pdo->prepare("DELETE FROM pending_users WHERE id = ?");
        $stmt->execute([$id]);
    }
}
?>