<?php
require_once(__DIR__ . '/Database.php');

class Activite {
    
    public static function create($nomActivite, $date, $adresse, $ville, $prix, $nbrParticipantMax, $description, $duree, $isPublic, $idCreateur) {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            INSERT INTO `activite`(`nomActivite`, `date`, `adresse`, `ville`, `prix`, `nbrParticipantMax`, `description`, `duree`, `isPublic`, `idCreateur`) VALUES (:nomActivite, :date, :adresse, :ville, :prix, :nbrParticipantMax, :description, :duree, :isPublic, :idCreateur)
        ");
        $sql->bindValue(':nomActivite', $nomActivite, PDO::PARAM_STR);
        $sql->bindValue(':date', $date, PDO::PARAM_STR);
        $sql->bindValue(':adresse', $adresse, PDO::PARAM_STR);
        $sql->bindValue(':ville', $ville, PDO::PARAM_STR);
        $sql->bindValue(':prix', $prix, PDO::PARAM_INT);
        $sql->bindValue(':nbrParticipantMax', $nbrParticipantMax, PDO::PARAM_INT);
        $sql->bindValue(':description', $description, PDO::PARAM_STR);
        $sql->bindValue(':duree', $duree, PDO::PARAM_STR);
        $sql->bindValue(':isPublic', $isPublic, PDO::PARAM_BOOL);
        $sql->bindValue(':idCreateur', $idCreateur, PDO::PARAM_INT);

        $sql->execute();
        return $pdo->lastInsertId(); // Retourne le dernier id
    }

    public static function get($id) {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            SELECT * FROM activite WHERE idActivite = :idActivite
        ");
        $sql->bindValue(':idActivite', $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAll() {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            SELECT * FROM activite
        ");
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public static function update($id, $date, $nomActivite, $adresse, $ville, $prix, $nbrParticipantMax, $description, $duree, $isPublic, $idCreateur) {
        $pdo = getPDO();
        $sql = $pdo->prepare("UPDATE activite SET nomActivite = :nomActivite, date = :date, adresse = :adresse, ville = :ville, prix = :prix, nbrParticipantMax = :nbrParticipantMax, description = :description, duree = :duree, isPublic = :isPublic, idCreateur = :idCreateur WHERE idActivite = :idActivite");
        $sql->bindValue(':nomActivite', $nomActivite, PDO::PARAM_STR);
        $sql->bindValue(':nomActivite', $date, PDO::PARAM_STR);
        $sql->bindValue(':adresse', $adresse, PDO::PARAM_STR);
        $sql->bindValue(':ville', $ville, PDO::PARAM_STR);
        $sql->bindValue(':prix', $prix, PDO::PARAM_INT);
        $sql->bindValue(':nbrParticipantMax', $nbrParticipantMax, PDO::PARAM_INT);
        $sql->bindValue(':description', $description, PDO::PARAM_STR);
        $sql->bindValue(':duree', $duree, PDO::PARAM_STR);
        $sql->bindValue(':isPublic', $isPublic, PDO::PARAM_BOOL);
        $sql->bindValue(':idCreateur', $idCreateur, PDO::PARAM_INT);
        $sql->bindValue(':idActivite', $id, PDO::PARAM_INT);
        $sql->execute();
        return;
    }

    public static function delete($id) {
        $pdo = getPDO();
        $sql = $pdo->prepare("DELETE FROM activite WHERE idActivite = :idActivite");
        $sql->bindValue(':idActivite', $id, PDO::PARAM_INT);
        $sql->execute();
        return;
    }
}
?>