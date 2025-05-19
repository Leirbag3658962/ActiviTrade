<?php
require_once(__DIR__ . '/Database.php');

class Signalement {
    
    public static function create($raison, $description, $idSignaleur, $idSignalee) {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            INSERT INTO `signalement`(`raison`, `description`, `idSignaleur`, `idSignalee`) VALUES (:raison, :description, :idSignaleur, :idSignalee)
        ");
        $sql->bindValue(':raison', $raison, PDO::PARAM_STR);
        $sql->bindValue(':description', $description, PDO::PARAM_STR);
        $sql->bindValue(':idSignaleur', $idSignaleur, PDO::PARAM_INT);
        $sql->bindValue(':idSignalee', $idSignalee, PDO::PARAM_INT);

        $sql->execute();
        return $pdo->lastInsertId(); // Retourne le dernier id
    }

    public static function get($id) {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            SELECT * FROM signalement WHERE idSignalement = :idSignalement
        ");
        $sql->bindValue(':idSignalement', $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAll() {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            SELECT * FROM signalement
        ");
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public static function update($id, $raison, $description, $idSignaleur, $idSignalee) {
        $pdo = getPDO();
        $sql = $pdo->prepare("UPDATE signalement SET raison = :raison, description = :description, idSignaleur = :idSignaleur, idSignalee = :idSignalee WHERE idSignalement = :idSignalement");
        $sql->bindValue(':raison', $raison, PDO::PARAM_STR);
        $sql->bindValue(':description', $description, PDO::PARAM_STR);
        $sql->bindValue(':idSignaleur', $idSignaleur, PDO::PARAM_INT);
        $sql->bindValue(':idSignalee', $idSignalee, PDO::PARAM_INT);
        $sql->bindValue(':idSignalement', $id, PDO::PARAM_INT);
        $sql->execute();
        return;
    }

    public static function delete($id) {
        $pdo = getPDO();
        $sql = $pdo->prepare("DELETE FROM signalement WHERE idSignalement = :idSignalement");
        $sql->bindValue(':idSignalement', $id, PDO::PARAM_INT);
        $sql->execute();
        return;
    }
}
?>