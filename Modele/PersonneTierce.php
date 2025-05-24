<?php
require_once(__DIR__ . '/Database.php');

class PersonneTierce {
    
    public static function create($nom, $prenom, $age, $idUser) {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            INSERT INTO `personnetierce`(`nom`, `prenom`, `age`, `idUser`) VALUES (:nom, :prenom, :age, :idUser)
        ");
        $sql->bindValue(':nom', $nom, PDO::PARAM_STR);
        $sql->bindValue(':prenom', $prenom, PDO::PARAM_STR);
        $sql->bindValue(':age', $age, PDO::PARAM_INT);
        $sql->bindValue(':idUser', $idUser, PDO::PARAM_INT);

        $sql->execute();
        return $pdo->lastInsertId(); // Retourne le dernier id
    }

    public static function get($id) {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            SELECT * FROM personnetierce WHERE idPersonneTierce = :idPersonneTierce
        ");
        $sql->bindValue(':idPersonneTierce', $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAll() {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            SELECT * FROM personnetierce
        ");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function update($id, $nom, $prenom, $age, $idUser) {
        $pdo = getPDO();
        $sql = $pdo->prepare("UPDATE personnetierce SET nom = :nom, prenom = :prenom, age = :age, idUser = :idUser WHERE idPersonneTierce = :idPersonneTierce");
        $sql->bindValue(':nom', $nom, PDO::PARAM_STR);
        $sql->bindValue(':prenom', $prenom, PDO::PARAM_STR);
        $sql->bindValue(':age', $age, PDO::PARAM_INT);
        $sql->bindValue(':idUser', $idUser, PDO::PARAM_INT);
        $sql->bindValue(':idPersonneTierce', $id, PDO::PARAM_INT);
        $sql->execute();
        return;
    }

    public static function delete($id) {
        $pdo = getPDO();
        $sql = $pdo->prepare("DELETE FROM personnetierce WHERE idPersonneTierce = :idPersonneTierce");
        $sql->bindValue(':idPersonneTierce', $id, PDO::PARAM_INT);
        $sql->execute();
        return;
    }
}
?>