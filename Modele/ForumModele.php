<?php
require_once(__DIR__ . '/Database.php');

class Forum {
    
    public static function create($theme, $date, $contenu, $idUser, $idParent) {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            INSERT INTO `forum`(`theme`, `date`, `contenu`, `idUser`, `idParent`) VALUES (:theme, :date, :contenu, :idUser, :idParent)
        ");
        $sql->bindValue(':theme', $theme, PDO::PARAM_STR);
        $sql->bindValue(':date', $date, PDO::PARAM_STR);
        $sql->bindValue(':contenu', $contenu, PDO::PARAM_STR);
        $sql->bindValue(':idUser', $idUser, PDO::PARAM_INT);
        $sql->bindValue(':idParent', $idParent, PDO::PARAM_INT);

        $sql->execute();
        return $pdo->lastInsertId(); // Retourne le dernier id
    }

    public static function get($id) {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            SELECT * FROM forum WHERE idForum = :idForum
        ");
        $sql->bindValue(':idForum', $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAll() {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            SELECT * FROM forum
        ");
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public static function update($id, $theme, $date, $contenu, $idUser, $idParent) {
        $pdo = getPDO();
        $sql = $pdo->prepare("UPDATE forum SET theme = :theme, date = :date, contenu = :contenu, idUser = :idUser, idParent = :idParent WHERE idForum = :idForum");
        $sql->bindValue(':theme', $theme, PDO::PARAM_STR);
        $sql->bindValue(':date', $date, PDO::PARAM_STR);
        $sql->bindValue(':contenu', $contenu, PDO::PARAM_STR);
        $sql->bindValue(':idUser', $idUser, PDO::PARAM_INT);
        $sql->bindValue(':idParent', $idParent, PDO::PARAM_INT);
        $sql->bindValue(':idForum', $id, PDO::PARAM_INT);
        $sql->execute();
        return;
    }

    public static function delete($id) {
        $pdo = getPDO();
        $sql = $pdo->prepare("DELETE FROM forum WHERE idForum = :idForum");
        $sql->bindValue(':idForum', $id, PDO::PARAM_INT);
        $sql->execute();
        return;
    }
}
?>