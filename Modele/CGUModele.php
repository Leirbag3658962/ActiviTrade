<?php
require_once(__DIR__ . '/Database.php');

class CGU {
    
    public static function create($numero, $titreCgu, $description) {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            INSERT INTO `mentionlegale`(`numero`, `titreCgu`, `description`) VALUES (:numero, :titreCgu, :description)
        ");
        $sql->bindValue(':numero', $numero, PDO::PARAM_INT);
        $sql->bindValue(':titreCgu', $titreCgu, PDO::PARAM_STR);
        $sql->bindValue(':description', $description, PDO::PARAM_STR);

        $sql->execute();
        return $pdo->lastInsertId(); // Retourne le dernier id
    }

    public static function get($id) {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            SELECT * FROM mentionlegale WHERE idCgu = :idCgu
        ");
        $sql->bindValue(':idCgu', $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAll() {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            SELECT * FROM mentionlegale
        ");
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public static function update($id, $numero, $titreCgu, $description) {
        $pdo = getPDO();
        $sql = $pdo->prepare("UPDATE mentionlegale SET numero = :numero, titreCgu = :titreCgu, description = :description WHERE idCgu = :idCgu");
        $sql->bindValue(':numero', $numero, PDO::PARAM_INT);
        $sql->bindValue(':titreCgu', $titreCgu, PDO::PARAM_STR);
        $sql->bindValue(':description', $description, PDO::PARAM_STR);
        $sql->bindValue(':idCgu', $id, PDO::PARAM_INT);
        $sql->execute();
        return;
    }

    public static function delete($id) {
        $pdo = getPDO();
        $sql = $pdo->prepare("DELETE FROM mentionlegale WHERE idCgu = :idCgu");
        $sql->bindValue(':idCgu', $id, PDO::PARAM_INT);
        $sql->execute();
        return;
    }
}
?>