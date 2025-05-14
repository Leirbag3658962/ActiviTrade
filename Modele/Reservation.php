<?php
require_once(__DIR__ . '/Database.php');

class FAQ {
    
    public static function create($nombrePersonne, $date, $idUser, $idActivite) {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            INSERT INTO `reservation`(`nombrePersonne`, `date`, `idUser`, `idActivite`) VALUES (:nombrePersonne, :date, :idUser, :idActivite)
        ");
        $sql->bindValue(':nombrePersonne', $nombrePersonne, PDO::PARAM_STR);
        $sql->bindValue(':date', $date, PDO::PARAM_STR);
        $sql->bindValue(':idUser', $idUser, PDO::PARAM_INT);
        $sql->bindValue(':idActivite', $idActivite, PDO::PARAM_INT);

        $sql->execute();
        return $pdo->lastInsertId(); // Retourne le dernier id
    }

    public static function get($id) {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            SELECT * FROM reservation WHERE idReservation = :idReservation
        ");
        $sql->bindValue(':idReservation', $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAll() {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            SELECT * FROM reservation
        ");
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public static function update($id, $nombrePersonne, $date, $idUser, $idActivite) {
        $pdo = getPDO();
        $sql = $pdo->prepare("UPDATE reservation SET nombrePersonne = :nombrePersonne, date = :date, idUser = :idUser, idActivite = :idActivite WHERE idReservation = :idReservation");
        $sql->bindValue(':nombrePersonne', $nombrePersonne, PDO::PARAM_STR);
        $sql->bindValue(':date', $date, PDO::PARAM_STR);
        $sql->bindValue(':idUser', $idUser, PDO::PARAM_INT);
        $sql->bindValue(':idActivite', $idActivite, PDO::PARAM_INT);
        $sql->bindValue(':idReservation', $id, PDO::PARAM_INT);
        $sql->execute();
        return;
    }

    public static function delete($id) {
        $pdo = getPDO();
        $sql = $pdo->prepare("DELETE FROM reservation WHERE idReservation = :idReservation");
        $sql->bindValue(':idReservation', $id, PDO::PARAM_INT);
        $sql->execute();
        return;
    }
}
?>