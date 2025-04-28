<?php
// Pour erreur
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$dbname = 'activitrade';
$username = 'root';
$password = '';

//En cas d'erreur
if (!isset($_POST['table']) || empty($_POST['table'])) {
    echo '<p style="color:red;">Erreur : Nom de table non fourni.</p>';
    exit;
}

$nomTableDemande = $_POST['table'];

try {
    $pdo = new PDO("mysql:host=$host;port=3307;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo '<p style="color:red;">Erreur de connexion à la base de données : ' . htmlspecialchars($e->getMessage()) . '</p>';
    exit;
}


try {
    $stmtCheck = $pdo->prepare("SHOW TABLES LIKE ?");
    $stmtCheck->execute([$nomTableDemande]);
    if ($stmtCheck->rowCount() == 0) {
        //En cas d'erreur
        echo '<p style="color:red;">Erreur : La table "' . htmlspecialchars($nomTableDemande) . '" n\'existe pas ou n\'est pas autorisée.</p>';
        exit;
    }

//En cas d'erreur
} catch (PDOException $e) {
    echo '<p style="color:red;">Erreur lors de la vérification de la table : ' . htmlspecialchars($e->getMessage()) . '</p>';
    exit;
}



try {
    $nomClePrimaire = null;
    $stmtKeys = $pdo->query("SHOW KEYS FROM `$nomTableDemande` WHERE Key_name = 'PRIMARY'");
    if ($stmtKeys && $keyInfo = $stmtKeys->fetch(PDO::FETCH_ASSOC)) {
        $nomClePrimaire = $keyInfo['Column_name'];
    }
    if (!$nomClePrimaire) {
        echo '<p style="color:orange;">Attention : Aucune clé primaire trouvée pour la table ' . htmlspecialchars($nomTableDemande) . '. La suppression ne fonctionnera pas.</p>';
    }

    //Colonnes
    $recupcol = "SHOW COLUMNS FROM `$nomTableDemande`"; 
    $stmtColonnes = $pdo->query($recupcol);
    $colonnes = $stmtColonnes->fetchAll(PDO::FETCH_COLUMN); 

    //Données
    $sql = "SELECT * FROM `$nomTableDemande`"; 
    $stmtData = $pdo->query($sql);

    $html = '<button id="BoutonAjouter" type="button" data-table="'.htmlspecialchars($nomTableDemande).'">Ajouter '.htmlspecialchars($nomTableDemande).'</button>';
    $html .= '<br>';

    //Tableeau
    $html .= '<table class="tabledata">'; 
    $html .= '<tr class="titretableau">';
    foreach ($colonnes as $col) {
        $html .= "<th>" . htmlspecialchars($col) . "</th>";
    }
    $html .= '</tr>';

    $compteur = 1;
    while ($row = $stmtData->fetch(PDO::FETCH_ASSOC)) {
        $classeLigne = ($compteur % 2 === 1) ? 'ligneimpaire' : 'lignepaire';

        $valeurClePrimaire = $nomClePrimaire ? ($row[$nomClePrimaire] ?? null) : null;
        $dataAttributes = '';
        if ($valeurClePrimaire !== null) {
            $dataAttributes = ' data-pk-value="' . htmlspecialchars($valeurClePrimaire) . '"';
            $dataAttributes .= ' data-table="' . htmlspecialchars($nomTableDemande) . '"';
        }

        $html .= '<tr class="' . $classeLigne . '" id="row_' . htmlspecialchars($nomTableDemande) . '_' . htmlspecialchars($compteur) . '"' . $dataAttributes . '>';
        foreach ($row as $value) {
            $html .= "<td class=\"case\">" . htmlspecialchars($value ?? '') . "</td>";
        }
        $html .= "</tr>"; // Fermer la ligne de données
        $compteur++;
    }

    //En cas d'erreur
    if ($compteur === 1) {
         $html .= '<tr><td colspan="' . count($colonnes) . '">Aucune donnée dans cette table.</td></tr>';
    }


    $html .= '</table>'; 
    echo $html;

} catch (PDOException $e) {
    //En cas d'erreur
    echo '<p style="color:red;">Erreur lors de la génération de la table "' . htmlspecialchars($nomTableDemande) . '": ' . htmlspecialchars($e->getMessage()) . '</p>';
}
?>