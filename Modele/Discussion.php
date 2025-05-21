<?php
require_once(__DIR__ . '/Database.php');

class Discussion {
    
    public static function create($date, $contenu, $idUser1, $idUser2) {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            INSERT INTO `discussion`(`date`, `contenu`, `idUser1`, `idUser2`) VALUES (:date, :contenu, :idUser1, :idUser2)
        ");
        $sql->bindValue(':date', $date, PDO::PARAM_STR);
        $sql->bindValue(':contenu', $contenu, PDO::PARAM_STR);
        $sql->bindValue(':idUser1', $idUser1, PDO::PARAM_INT);
        $sql->bindValue(':idUser2', $idUser2, PDO::PARAM_INT);

        $sql->execute();
        return $pdo->lastInsertId(); // Retourne le dernier id
    }

    public static function get($id) {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            SELECT * FROM discussion WHERE idDiscussion = :idDiscussion
        ");
        $sql->bindValue(':idDiscussion', $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAll() {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            SELECT * FROM discussion
        ");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function update($id, $date, $contenu, $idUser1, $idUser2) {
        $pdo = getPDO();
        $sql = $pdo->prepare("UPDATE discussion SET date = :date, contenu = :contenu, idUser1 = :idUser1, idUser2 = :idUser2 WHERE idDiscussion = :idDiscussion");
        $sql->bindValue(':date', $date, PDO::PARAM_STR);
        $sql->bindValue(':contenu', $contenu, PDO::PARAM_STR);
        $sql->bindValue(':idUser', $idUser1, PDO::PARAM_INT);
        $sql->bindValue(':idUser2', $idUser2, PDO::PARAM_INT);
        $sql->bindValue(':idDiscussion', $id, PDO::PARAM_INT);
        $sql->execute();
        return;
    }

    public static function delete($id) {
        $pdo = getPDO();
        $sql = $pdo->prepare("DELETE FROM discussion WHERE idDiscussion = :idDiscussion");
        $sql->bindValue(':idDiscussion', $id, PDO::PARAM_INT);
        $sql->execute();
        return;
    }
}
?>