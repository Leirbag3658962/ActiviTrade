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
        $pdo = new PDO("mysql:host=$host;port=3307;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;

    } catch(PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
        exit;
    }
}

function testValidationForm($data){
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

function traitementFormActivite(){
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (!empty($_POST['inputNom']) && !empty($_POST['inputDate']) && !empty($_POST['inputDuree']) && !empty($_POST['inputCategorie']) 
        && !empty($_POST['inputNbrParticipant']) && !empty($_POST['Groupe']) && !empty($_POST['inputDescription'])) {
            $nom = testValidationForm($_POST['inputNom']);
            $date = testValidationForm($_POST['inputDate']);
            $duree = testValidationForm($_POST['inputDuree']);
            $categorie = testValidationForm($_POST['inputCategorie']);
            $nbrParticipant = testValidationForm($_POST['inputNbrParticipant']);
            $groupe = testValidationForm($_POST['Groupe']);
            $descrption = testValidationForm($_POST['inputDescrption']);
    
            $sql = "INSERT INTO activite (nom, DateDeNaissance, Num) VALUES (:nom, :datetest, :numTel)";
            $stmt = $pdo->prepare($sql);
    
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':datetest', $date);
            $stmt->bindParam(':numTel', $numTel);
            
            $stmt->execute();
            
        } else {
            echo "Veuillez remplir tous les champs.";
        }
    }
}

?>