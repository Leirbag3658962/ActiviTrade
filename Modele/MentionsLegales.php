<?php
require_once(__DIR__ . '/Database.php');

class MentionsLegales {
    
    public static function create($numero, $titreparagraphe, $description) {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            INSERT INTO `mentionlegale`(`numero`, `titreparagraphe`, `description`) VALUES (:numero, :titreparagraphe, :description)
        ");
        $sql->bindValue(':numero', $numero, PDO::PARAM_INT);
        $sql->bindValue(':titreparagraphe', $titreparagraphe, PDO::PARAM_STR);
        $sql->bindValue(':description', $description, PDO::PARAM_STR);

        $sql->execute();
        return $pdo->lastInsertId(); // Retourne le dernier id
    }

    public static function get($id) {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            SELECT * FROM mentionlegale WHERE idMention = :idMention
        ");
        $sql->bindValue(':idMention', $id, PDO::PARAM_INT);
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

    public static function update($id, $numero, $titreparagraphe, $description) {
        $pdo = getPDO();
        $sql = $pdo->prepare("UPDATE mentionlegale SET numero = :numero, titreparagraphe = :titreparagraphe, description = :description WHERE idMention = :idMention");
        $sql->bindValue(':numero', $numero, PDO::PARAM_INT);
        $sql->bindValue(':titreparagraphe', $titreparagraphe, PDO::PARAM_STR);
        $sql->bindValue(':description', $description, PDO::PARAM_STR);
        $sql->bindValue(':idMention', $id, PDO::PARAM_INT);
        $sql->execute();
        return;
    }

    public static function delete($id) {
        $pdo = getPDO();
        $sql = $pdo->prepare("DELETE FROM mentionlegale WHERE idMention = :idMention");
        $sql->bindValue(':idMention', $id, PDO::PARAM_INT);
        $sql->execute();
        return;
    }

    public static function afficherMentions(){
        $pdo = getPDO();
        if(!$pdo){
            echo "<a> Erreur: Connexion BDD non fournie.</a>";
        }

        try{
            $sqlmention = "SELECT titreparagraphe, description FROM mentionlegale ORDER BY numero ASC";
            $stmtmention = $pdo->query($sqlmention); 
            if($stmtmention){
                echo "<div id=\"box\">";
                while ($row = $stmtmention->fetch(PDO::FETCH_ASSOC)){
                    echo"<h2 class=\"titre2\">" . htmlspecialchars($row['titreparagraphe']) . "</h2>";

                    echo"<p>" . $row['description'] . "</p>";
                    echo"<br>";
                }
                echo "</div>";
            }
        } catch (PDOException $e) {
            echo "<a> Erreur BDD lors de l'affichage des mentions légales: " . htmlspecialchars($e->getMessage()) . "</a>";
        }
    }
}
?>