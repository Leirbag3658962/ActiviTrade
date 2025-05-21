<?php
require_once(__DIR__ . '/Database.php');

function recherche($mot){
    $pdo = getPDO();
    if(!$pdo){
        echo "<a> Erreur: Connexion BDD non fournie.</a>";
    }
    testValidationForm($mot);
    try{
        $idList = array();

        $sqlrecherche = "SELECT idActivite FROM activite WHERE nomActivite LIKE :mot ORDER BY idActivite DESC";
        $stmtrecherche = $pdo->prepare($sqlrecherche);
        $motrecherche = "%".$mot."%";
        $stmtrecherche->bindParam(':mot', $motrecherche);

        $stmtrecherche->execute();

	    
	    if($stmtrecherche){
		    while ($row = $stmtrecherche->fetch(PDO::FETCH_ASSOC)){
                $idList[] = $row['idActivite'];
                // echo "<a>".$row['idActivite']."</a>";
		    }
            return $idList;
	    }
    } catch (PDOException $e) {
        echo "<a> Erreur BDD lors de l'affichage des mentions légales: " . htmlspecialchars($e->getMessage()) . "</a>";
   }
}

function rechercheTheme($mot){
    $pdo = getPDO();
    if(!$pdo){
        echo "<a> Erreur: Connexion BDD non fournie.</a>";
    }
    testValidationForm($mot);
    try{
        $idList = array();

        $sqlrecherche = "SELECT at.idActivite FROM activite as a 
                        JOIN theme as t JOIN activite_theme as at 
                        ON a.idActivite = at.idActivite and at.idTheme = t.idTheme
                        WHERE t.theme LIKE :mot ORDER BY at.idActivite DESC";

        $stmtrecherche = $pdo->prepare($sqlrecherche);
        $motrecherche = "%".$mot."%";
        $stmtrecherche->bindParam(':mot', $motrecherche);

        $stmtrecherche->execute();

	    
	    if($stmtrecherche){
		    while ($row = $stmtrecherche->fetch(PDO::FETCH_ASSOC)){
                $idList[] = $row['idActivite'];
                // echo "<a>".$row['idActivite']."</a>";
		    }
            return $idList;
	    }
    } catch (PDOException $e) {
        echo "<a> Erreur BDD lors de l'affichage des mentions légales: " . htmlspecialchars($e->getMessage()) . "</a>";
   }
}
?>