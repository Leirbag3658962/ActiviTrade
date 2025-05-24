<?php
require_once(__DIR__ . '/Database.php');

class Theme {
    
    public static function create($theme) {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            INSERT INTO `theme`(`theme`) VALUES (:theme)
        ");
        $sql->bindValue(':theme', $theme, PDO::PARAM_STR);

        $sql->execute();
        return $pdo->lastInsertId(); // Retourne le dernier id
    }

    public static function get($id) {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            SELECT * FROM theme WHERE idTheme = :idTheme
        ");
        $sql->bindValue(':idTheme', $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAll() {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            SELECT * FROM theme
        ");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function update($id, $theme) {
        $pdo = getPDO();
        $sql = $pdo->prepare("UPDATE theme SET theme = :theme WHERE idTheme = :idTheme");
        $sql->bindValue(':theme', $theme, PDO::PARAM_STR);
        $sql->bindValue(':idTheme', $id, PDO::PARAM_INT);
        $sql->execute();
        return;
    }

    public static function delete($id) {
        $pdo = getPDO();
        $sql = $pdo->prepare("DELETE FROM theme WHERE idTheme = :idTheme");
        $sql->bindValue(':idTheme', $id, PDO::PARAM_INT);
        $sql->execute();
        return;
    }
}
?>