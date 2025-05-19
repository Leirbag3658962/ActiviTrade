<?php


function lienPDO(){
    // Pour erreur
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);


    $host = 'localhost';
    $dbname = 'activitrade';
    $username = 'root';
    $password = '';
    $port = '3306';
    $pageActuelle = "utilisateur";

    try {

        $pdo = new PDO("mysql:host=$host;port=3306;dbname=$dbname;charset=utf8", $username, $password);

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        error_log("Connexion à la base de données réussie");
        return $pdo;

    } catch(PDOException $e) {
        error_log("Erreur de connexion à la base de données: " . $e->getMessage());
        throw new Exception("Erreur de connexion à la base de données: " . $e->getMessage());
    }
}

function testValidationForm2($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = strip_tags($data);
    $data = htmlspecialchars($data);
    return $data;
}


function recuperationTable($pdo){
    if(!$pdo){
        echo "<a> Erreur: Connexion BDD non fournie.</a>";
    }

    try{
        $recuptable = 'SHOW TABLES';
        $nomtable = $pdo->query($recuptable);
        if($nomtable){
            while ($ligne = $nomtable->fetch(PDO::FETCH_COLUMN)) {
                echo "<a href='javascript:void(0);' id=\"" . htmlspecialchars($ligne) . "\" class=\"deroulement\">" . htmlspecialchars($ligne) . "</a>";
            } 
        }else{
            echo "<a> Echec de récupération </a>";
        }
    } catch (PDOException $e) {
        echo "<a> Erreur BDD lors de la récupération des tables: " . htmlspecialchars($e->getMessage()) . "</a>";
    }  
}

function afficheFaq($pdo){
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

function afficherMentions($pdo){
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

function afficherCgu($pdo){
    if(!$pdo){
        echo "<a> Erreur: Connexion BDD non fournie.</a>";
    }

    try{
        $sqlcgu = "SELECT titreCgu, description FROM cgu ORDER BY numero ASC";
	    $stmtcgu = $pdo->query($sqlcgu); 
	    if($stmtcgu){
            echo "<div id=\"box\">";
		    while ($row = $stmtcgu->fetch(PDO::FETCH_ASSOC)){
                echo"<h2 class=\"titre2\">" . htmlspecialchars($row['titreCgu']) . "</h2>";

                echo"<p>" . $row['description'] . "</p>";
                echo"<br>";
		    }
            echo "</div>";
	    }
    } catch (PDOException $e) {
        echo "<a> Erreur BDD lors de l'affichage des CGU: " . htmlspecialchars($e->getMessage()) . "</a>";
   }
}

function listeCategorie($pdo){
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
function categorieActivite($idAct, $theme){
    $pdo = lienPDO();
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
function imageActivite($idAct, $image){
    $pdo = lienPDO();
    if(!$pdo){
        echo "<a> Erreur: Connexion BDD non fournie.</a>";
    }
    try{
        $sqlimage = "INSERT INTO `image`(`idActivite`, `image`) VALUES (:idactivite, :image)";
	    $stmtimage = $pdo->prepare($sqlimage);
        $stmtimage ->execute(['idactivite' => $idAct, 'image' => $image]);
    }catch (PDOException $e) {
        echo "<a> Erreur BDD lors du lien image-activité: " . htmlspecialchars($e->getMessage()) . "</a>";
    }
}
?>