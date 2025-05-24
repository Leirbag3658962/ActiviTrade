<?php
require_once(__DIR__ . '/Database.php');

class Contact {
    
    public static function create($email, $sujet, $contenu, $date) {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            INSERT INTO `contact`(`email`, `sujet`, `contenu`, `date`) VALUES (:email, :sujet, :contenu, :date)
        ");
        $sql->bindValue(':email', $email, PDO::PARAM_STR);
        $sql->bindValue(':sujet', $sujet, PDO::PARAM_STR);
        $sql->bindValue(':contenu', $contenu, PDO::PARAM_STR);
        $sql->bindValue(':date', $date, PDO::PARAM_STR);

        $sql->execute();
        return $pdo->lastInsertId(); // Retourne le dernier id
    }

    public static function get($id) {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            SELECT * FROM contact WHERE idContact = :idContact
        ");
        $sql->bindValue(':idContact', $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAll() {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            SELECT * FROM contact
        ");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function update($id, $email, $sujet, $contenu, $date) {
        $pdo = getPDO();
        $sql = $pdo->prepare("UPDATE contact SET email = :email, sujet = :sujet, contenu = :contenu, date = :date WHERE idContact = :idContact");
        $sql->bindValue(':email', $email, PDO::PARAM_STR);
        $sql->bindValue(':sujet', $sujet, PDO::PARAM_STR);
        $sql->bindValue(':contenu', $contenu, PDO::PARAM_STR);
        $sql->bindValue(':date', $date, PDO::PARAM_STR);
        $sql->bindValue(':idContact', $id, PDO::PARAM_INT);
        $sql->execute();
        return;
    }

    public static function delete($id) {
        $pdo = getPDO();
        $sql = $pdo->prepare("DELETE FROM contact WHERE idContact = :idContact");
        $sql->bindValue(':idContact', $id, PDO::PARAM_INT);
        $sql->execute();
        return;
    }
}
?>