<?php
require_once(__DIR__ . '/Database.php');

class Avis {
    
    public static function create($note, $contenu, $date, $idUser, $idActivite) {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            INSERT INTO `avis`(`note`, `contenu`, `date`, `idUser`, `idActivite`) VALUES (:note, :contenu, :date, :idUser, :idActivite)
        ");
        $sql->bindValue(':note', $note, PDO::PARAM_INT);
        $sql->bindValue(':contenu', $contenu, PDO::PARAM_STR);
        $sql->bindValue(':date', $date, PDO::PARAM_STR);
        $sql->bindValue(':idUser', $idUser, PDO::PARAM_INT);
        $sql->bindValue(':idActivite', $idActivite, PDO::PARAM_INT);

        $sql->execute();
        return $pdo->lastInsertId(); // Retourne le dernier id
    }

    public static function get($id) {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            SELECT * FROM avis WHERE idAvis = :idAvis
        ");
        $sql->bindValue(':idAvis', $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAll() {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            SELECT * FROM avis
        ");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function update($id, $note, $contenu, $date, $idUser, $idActivite) {
        $pdo = getPDO();
        $sql = $pdo->prepare("UPDATE avis SET note = :note, contenu = :contenu, date = :date, idUser = :idUser, idActivite = :idActivite WHERE idAvis = :idAvis");
        $sql->bindValue(':note', $note, PDO::PARAM_INT);
        $sql->bindValue(':contenu', $contenu, PDO::PARAM_STR);
        $sql->bindValue(':date', $date, PDO::PARAM_STR);
        $sql->bindValue(':idUser', $idUser, PDO::PARAM_INT);
        $sql->bindValue(':idActivite', $idActivite, PDO::PARAM_INT);
        $sql->bindValue(':idAvis', $id, PDO::PARAM_INT);
        $sql->execute();
        return;
    }

    public static function delete($id) {
        $pdo = getPDO();
        $sql = $pdo->prepare("DELETE FROM avis WHERE idAvis = :idAvis");
        $sql->bindValue(':idAvis', $id, PDO::PARAM_INT);
        $sql->execute();
        return;
    }
}
?>