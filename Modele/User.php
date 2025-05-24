<?php
require_once(__DIR__ . '/Database.php');

class User {
    
    public static function create($lastname, $firstname, $email, $birthdate, $ville, $telephone, $password_hash) {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            INSERT INTO `utilisateur`(`nom`, `prenom`, `email`, `dateNaissance`, `ville`, `telephone`, `role`, `password`) VALUES (:lastname, :firstname, :email, :birthdate, :ville, :telephone, 'user', '$password_hash')
        ");
        $sql->bindValue(':lastname', $lastname, PDO::PARAM_STR);
        $sql->bindValue(':firstname', $firstname, PDO::PARAM_STR);
        $sql->bindValue(':email', $email, PDO::PARAM_STR);
        $sql->bindValue(':birthdate', $birthdate, PDO::PARAM_STR);
        $sql->bindValue(':ville', $ville, PDO::PARAM_STR);
        $sql->bindValue(':telephone', $telephone, PDO::PARAM_STR);

        $sql->execute();
        return $pdo->lastInsertId(); // Retourne le dernier id
    }

    public static function emailExists($email) {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            SELECT email FROM utilisateur WHERE email = ?
        ");
        $sql->execute([$email]);
        return $sql->fetch() !== false;
    }

    public static function getById($id) {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            SELECT * FROM utilisateur WHERE idUtilisateur = :idUtilisateur
        ");
        $sql->bindValue(':idUtilisateur', $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->All(PDO::FETCH_ASSOC);
    }
    
    public static function getByEmail($email) {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            SELECT * FROM utilisateur WHERE email = :email
        ");
        $sql->bindValue(':email', $email, PDO::PARAM_STR);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAll() {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            SELECT * FROM utilisateur
        ");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function update($id, $lastname, $firstname, $email, $birthdate, $ville, $telephone) {
        $pdo = getPDO();
        $sql = $pdo->prepare("UPDATE utilisateur SET nom = :lastname, prenom = :firstname, email = :email, dateNaissance = :birthdate, ville = :ville, telephone = :telephone WHERE idUtilisateur = :idUtilisateur");
        $sql->bindValue(':lastname', $lastname, PDO::PARAM_STR);
        $sql->bindValue(':firstname', $firstname, PDO::PARAM_STR);
        $sql->bindValue(':email', $email, PDO::PARAM_STR);
        $sql->bindValue(':birthdate', $birthdate, PDO::PARAM_STR);
        $sql->bindValue(':ville', $ville, PDO::PARAM_STR);
        $sql->bindValue(':telephone', $telephone, PDO::PARAM_STR);
        $sql->bindValue(':idUtilisateur', $id, PDO::PARAM_INT);
        $sql->execute();
        return;
    }

    public static function delete($id) {
        $pdo = getPDO();
        $sql = $pdo->prepare("DELETE FROM utilisateur WHERE idUtilisateur = :idUtilisateur");
        $sql->bindValue(':idUtilisateur', $id, PDO::PARAM_INT);
        $sql->execute();
        return;
    }


    public static function getUserById($id) {
        $pdo = getPDO();
        $stmt = $pdo->prepare("
            SELECT * FROM utilisateur WHERE idUtilisateur = :idUtilisateur
        ");
        $stmt->bindValue(':idUtilisateur', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public static function getUserByEmail($email) {
        $pdo = getPDO();
        $stmt = $pdo->prepare("
            SELECT * FROM utilisateur WHERE email = :email
        ");
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);

    public static function updateResetToken($email, $token_hash, $expiration) {
        $pdo = getPDO();
        $sql = $pdo->prepare("UPDATE utilisateur SET reset_token_hash = :token_hash, reset_token_expires_at = :expiration WHERE email = :email");
        $sql->bindValue(':token_hash', $token_hash, PDO::PARAM_STR);
        $sql->bindValue(':expiration', $expiration, PDO::PARAM_STR);
        $sql->bindValue(':email', $email, PDO::PARAM_STR);
        $sql->execute();
        return;
    }

    public static function getResetToken($token_hash) {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            SELECT * FROM utilisateur WHERE reset_token_hash = :reset_token
        ");
        $sql->bindValue(':reset_token', $token_hash, PDO::PARAM_STR);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public static function updatePassword($id, $password_hash) {
        $pdo = getPDO();
        $sql = $pdo->prepare("UPDATE utilisateur SET password= :password, reset_token_hash = NULL, reset_token_expires_at = NULL WHERE idUtilisateur=:id");
        $sql->bindValue(':password', $password_hash, PDO::PARAM_STR);
        $sql->bindValue(':id', $id, PDO::PARAM_INT);
        $sql->execute();
        return;

    }
}
?>