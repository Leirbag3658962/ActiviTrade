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
    $pageActuelle = "utilisateur";

    try {
        $pdo = new PDO("mysql:host=$host;port=3306;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;

    } catch(PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
        exit;
    }
}

// function testValidationForm($data){
//     $data = trim($data);
//     $data = stripslashes($data);
//     $data = strip_tags($data);
//     $data = htmlspecialchars($data);
//     return $data;
// }


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
        echo "<a> Erreur BDD lors de l'affichage des mentions légales: " . htmlspecialchars($e->getMessage()) . "</a>";
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
        echo "<a> Erreur BDD lors de l'affichage des mentions légales: " . htmlspecialchars($e->getMessage()) . "</a>";
        }
}

function traitementFormActivite($pdo){
    try{
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (!empty($_POST['inputNom']) && !empty($_POST['inputDate']) && !empty($_POST['inputDuree']) && !empty($_POST['inputCategorie']) 
        && !empty($_POST['inputNbrParticipant']) && !empty($_POST['Groupe']) && !empty($_POST['inputDescription'])) {
            $nomActivite = testValidationForm($_POST['inputNom']);
            $dateActivite = testValidationForm($_POST['inputDate']);
            $duree = testValidationForm($_POST['inputDuree']);
            $adresse = testValidationForm($_POST['inputAdresse']);
            $ville = testValidationForm($_POST['inputVille']);
            $categorie = testValidationForm($_POST['inputCategorie']);
            $nbrParticipant = testValidationForm($_POST['inputNbrParticipant']);
            $groupe = testValidationForm($_POST['Groupe']);
            $prix = testValidationForm($_POST['inputPrix']);
            $descriptionact = testValidationForm($_POST['inputDescrption']);
            $idCreator = $_SESSION['user']['id'];
            

            if (empty($nomActivite) || empty($dateActivite) || empty($adresse) || empty($ville) || empty($duree) || empty($categorie) 
            || empty($nbrParticipants) || empty($groupe) || empty($descriptionact) || empty($prix)) {
                exit("Remplissez tous les champs!");
            }
            if($nbrParticipant <= 0){
                exit("Le nombre de participants doit être supérieur à 0!");
            }
            if($prix <= 0){
                exit("Mettre votre prix à 0 si votre activité est gratuite!");
            }
    
            $sql = "INSERT INTO activite (nomActivite, adresse, ville, prix, nbrParticipantMax, description, duree, IsPublic, idCreateur) 
            VALUES (:nom1, :adresse1, :ville1, :prix1, :nbrParticipantMax1, :description1, :duree, :groupe1, :idCreateur1)";
            $stmt = $pdo->prepare($sql);
    
            $stmt->bindParam(':nom1', $nomActivite);
            $stmt->bindParam(':adresse1', $adresse);
            $stmt->bindParam(':ville1', $ville);
            $stmt->bindParam(':prix1', $prix);
            $stmt->bindParam(':nbrParticipantMax1', $nbrParticipant);
            $stmt->bindParam(':description1', $descriptionact);
            $stmt->bindParam(':duree', $duree);
            $stmt->bindParam(':groupe1', $groupe);
            $stmt->bindParam(':idCreateur1', $idCreator);

            $stmt->execute();


            
        }else{
            echo "Veuillez remplir tous les champs.";
        }
    }
    } catch (PDOException $e) {
        echo "<a> Erreur BDD lors de l'affichage des mentions légales: " . htmlspecialchars($e->getMessage()) . "</a>";
    }
    
}
?>