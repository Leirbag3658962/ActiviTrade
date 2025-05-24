<?php
require_once(__DIR__ . '/Database.php');

class FAQ {
    
    public static function create($question, $reponse) {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            INSERT INTO `faq`(`question`, `reponse`) VALUES (:question, :reponse)
        ");
        $sql->bindValue(':question', $question, PDO::PARAM_STR);
        $sql->bindValue(':reponse', $reponse, PDO::PARAM_STR);

        $sql->execute();
        return $pdo->lastInsertId(); // Retourne le dernier id
    }

    public static function get($id) {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            SELECT * FROM faq WHERE idFAQ = :idFAQ
        ");
        $sql->bindValue(':idFAQ', $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAll() {
        $pdo = getPDO();
        $sql = $pdo->prepare("
            SELECT * FROM faq
        ");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function update($id, $question, $reponse) {
        $pdo = getPDO();
        $sql = $pdo->prepare("UPDATE faq SET question = :question, reponse = :reponse WHERE idFAQ = :idFAQ");
        $sql->bindValue(':question', $question, PDO::PARAM_STR);
        $sql->bindValue(':reponse', $reponse, PDO::PARAM_STR);
        $sql->bindValue(':idFAQ', $id, PDO::PARAM_INT);
        $sql->execute();
        return;
    }

    public static function delete($id) {
        $pdo = getPDO();
        $sql = $pdo->prepare("DELETE FROM faq WHERE idFAQ = :idFAQ");
        $sql->bindValue(':idFAQ', $id, PDO::PARAM_INT);
        $sql->execute();
        return;
    }

    public static function afficheFaq(){
        $pdo = getPDO();
    if(!$pdo){
        echo "<a> Erreur: Connexion BDD non fournie.</a>";
    }

    try{
        $sqlfaq = "SELECT * FROM faq";
	    $stmtfaq = $pdo->query($sqlfaq); 
	    if($stmtfaq){
		    while ($row = $stmtfaq->fetch(PDO::FETCH_ASSOC)){
			    echo"<div>";
                echo"<a class=\"bulle-sms bulle-gauche\">" . htmlspecialchars($row['question']) . "</a>";
                echo"</div>";

                echo"<div>";
                echo"<a class=\"bulle-sms bulle-droite\">" . htmlspecialchars($row['reponse']) . "</a>";
                echo"</div>";
		    }
	    }
    } catch (PDOException $e) {
        echo "<a> Erreur BDD lors de l'affichage de la FAQ: " . htmlspecialchars($e->getMessage()) . "</a>";
   }
}
}
?>