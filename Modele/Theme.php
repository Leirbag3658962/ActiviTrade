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
        return $sql->fetchAll(PDO::FETCH_ASSOC);
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

    public static function listeCategorie(){
        $pdo = getPDO();
        if(!$pdo){
            echo "<a> Erreur: Connexion BDD non fournie.</a>";
        }

        try{
            $sqlcategorie = "SELECT theme FROM theme";
            $stmtcategorie = $pdo->query($sqlcategorie); 
            if($stmtcategorie){
                
                while ($row = $stmtcategorie->fetch(PDO::FETCH_ASSOC)){
                    echo "<option value=\"".$row['theme']."\">".$row['theme']."</option>";
                }
            } 
        }catch (PDOException $e) {
            echo "<a> Erreur BDD lors de l'affichage des catégories: " . htmlspecialchars($e->getMessage()) . "</a>";
        }
    }

    public static function categorieActivite($idAct, $theme){
        $pdo = getPDO();
        if(!$pdo){
            echo "<a> Erreur: Connexion BDD non fournie.</a>";
        }

        try{
            $sqlcategorie = "SELECT idTheme FROM theme WHERE theme = :theme";
            $stmtcategorie = $pdo->prepare($sqlcategorie);
            $stmtcategorie ->execute(['theme' => $theme]);
            if($stmtcategorie){
                
                $row = $stmtcategorie->fetch(PDO::FETCH_ASSOC);
                $sqltheme = $pdo->prepare(" INSERT INTO `activite_theme`(`idActivite`, `idTheme`) VALUES (:idactivite, :idtheme)");
                
                $sqltheme->bindValue(':idactivite', $idAct, PDO::PARAM_STR);
                $sqltheme->bindValue(':idtheme', $row['idTheme'], PDO::PARAM_STR);

                $sqltheme->execute();
            } 
        }catch (PDOException $e) {
            echo "<a> Erreur BDD lors du lien catégorie-activité: " . htmlspecialchars($e->getMessage()) . "</a>";
        }
    }
}
?>