<?php
require_once(__DIR__ . '/Database.php');

class Image {
    
    public static function create($idActivite, $image) {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            INSERT INTO `image`(`idActivite`, `image`) VALUES (:idActivite, :image)
        ");
        $sql->bindValue(':idActivite', $idActivite, PDO::PARAM_INT);
        $sql->bindValue(':image', $image, PDO::PARAM_INT);

        $sql->execute();
        return $pdo->lastInsertId(); // Retourne le dernier id
    }

    public static function get($id) {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            SELECT image FROM image WHERE idActivite = :idActivite
        ");
        $sql->bindValue(':idActivite', $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAll() {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            SELECT * FROM image
        ");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function delete($id) {
        $pdo = getPDO();
        $sql = $pdo->prepare("DELETE FROM image WHERE idActivite = :idActivite");
        $sql->bindValue(':idActivite', $id, PDO::PARAM_INT);
        $sql->execute();
        return;
    }
}
?>