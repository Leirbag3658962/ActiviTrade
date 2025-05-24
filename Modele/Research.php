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

function rechercheTheme($theme){
    $pdo = getPDO();
    if(!$pdo){
        echo "<a> Erreur: Connexion BDD non fournie.</a>";
    }
    testValidationForm($theme);
    try{
        $idList = array();

        $sqlrecherche = "SELECT at.idActivite FROM activite as a 
                        JOIN theme as t JOIN activite_theme as at 
                        ON a.idActivite = at.idActivite and at.idTheme = t.idTheme
                        WHERE t.theme LIKE :mot ORDER BY at.idActivite DESC";

        $stmtrecherche = $pdo->prepare($sqlrecherche);
        $motrecherche = "%".$theme."%";
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

function recherchePrix($prixInf, $prixSup){
    $pdo = getPDO();
    if(!$pdo){
        echo "<a> Erreur: Connexion BDD non fournie.</a>";
    }
    testValidationForm($prixInf);
    testValidationForm($prixSup);
    try{
        $idList = array();

        $sqlrecherche = "SELECT idActivite FROM activite as a 
                        WHERE prix>=:prixinf AND prix<=:prixsup ORDER BY a.idActivite DESC";

        $stmtrecherche = $pdo->prepare($sqlrecherche);
        $stmtrecherche->bindParam(':prixinf', $prixInf);
        $stmtrecherche->bindParam(':prixsup', $prixSup);

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

function rechercheVille($ville){
    $pdo = getPDO();
    if(!$pdo){
        echo "<a> Erreur: Connexion BDD non fournie.</a>";
    }
    testValidationForm($ville);
    try{
        $idList = array();

        $sqlrecherche = "SELECT idActivite FROM activite as a WHERE ville=:ville ORDER BY a.idActivite DESC;";

        $stmtrecherche = $pdo->prepare($sqlrecherche);
        $stmtrecherche->bindParam(':ville', $ville);

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