<?php
require_once(__DIR__ . '/Database.php');

class User {
    
    public static function create($lastname, $firstname, $email, $birthdate, $ville, $telephone, $password_hash) {
        $pdo = getPDO();
        $sql = "INSERT INTO `utilisateur`(`nom`, `prenom`, `email`, `dateNaissance`, `ville`, `telephone`, `role`, `password`) VALUES (:lastname, :firstname, :email, :birthdate, :ville, :telephone, 'user', '$password_hash')";

        $query = $pdo->prepare($sql);

        $query->bindValue(':lastname', $lastname, PDO::PARAM_STR);
        $query->bindValue(':firstname', $firstname, PDO::PARAM_STR);
        $query->bindValue(':email', $email, PDO::PARAM_STR);
        $query->bindValue(':birthdate', $birthdate, PDO::PARAM_STR);
        $query->bindValue(':ville', $ville, PDO::PARAM_STR);
        $query->bindValue(':telephone', $telephone, PDO::PARAM_STR);

        $query->execute();
        return $pdo->lastInsertId(); // Retourne le dernier id
    }

    public static function emailExists($email) {
        $pdo = getPDO();
        $stmt = $pdo->prepare("
            SELECT email FROM utilisateur WHERE email = ?
        ");
        $stmt->execute([$email]);
        return $stmt->fetch() !== false;
    }

    public static function getUserById($id) {
        $pdo = getPDO();
        $stmt = $pdo->prepare("
            SELECT * FROM utilisateur WHERE idUtilisateur = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public static function getUserByEmail($email) {
        $pdo = getPDO();
        $stmt = $pdo->prepare("
            SELECT * FROM utilisateur WHERE email = ?
        ");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>