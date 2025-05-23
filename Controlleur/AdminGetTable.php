<?php
session_start();
require_once(__DIR__ . '/../Modele/LienPDO.php');
require_once(__DIR__ . '/../Modele/AdminModele.php');
$pdo = lienPDO();

//En cas d'erreur
if (!isset($_POST['table']) || empty($_POST['table'])) {
    echo '<p style="color:red;">Erreur : Nom de table non fourni.</p>';
    exit;
}

$nomTableDemande = $_POST['table'];

$messErreur = '';
$messErreur = validationTable($nomTableDemande);
if($messErreur != ''){
    echo "".$messErreur."";
    exit;
}



try {
    $nomClePrimaire = nomClePrimaire($nomTableDemande);
    if (!$nomClePrimaire) {
        echo '<p style="color:orange;">Attention : Aucune clé primaire trouvée pour la table ' . htmlspecialchars($nomTableDemande) . '. La suppression ne fonctionnera pas.</p>';
    }

    //Colonnes
    $colonnes = showColonnes($nomTableDemande);

    //Données
    $stmtData = data($nomTableDemande);

    $html = '<button id="BoutonAjouter" type="button" data-table="'.htmlspecialchars($nomTableDemande).'">Ajouter '.htmlspecialchars($nomTableDemande).'</button>';
    $html .= '<br>';

    //Tableeau
    $html .= '<table class="tabledata">'; 
    $html .= '<tr class="titretableau">';
    foreach ($colonnes as $col) {
        if($col != "password"){
            $html .= "<th>" . htmlspecialchars($col) . "</th>";
        }
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
            $dataAttributes .= ' data-pk-name="' . htmlspecialchars($nomClePrimaire) . '"';
        }

        $html .= '<tr class="' . $classeLigne . '" id="row_' . htmlspecialchars($nomTableDemande) . '_' . htmlspecialchars($compteur) . '"' . $dataAttributes . '>';
        foreach ($row as $columnName => $value) {
            $isPkColumn = ($columnName === $nomClePrimaire);
            if($columnName != "password"){
                $tdClass = 'case' . ($isPkColumn ? ' pk-cell' : ' editable-cell');
                $html .= "<td class=\"" . $tdClass . "\" data-column-name=\"" . htmlspecialchars($columnName) . "\">" 
                . htmlspecialchars($value ?? '') . "</td>";
            }
        }
        $html .= "</tr>"; 
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
    echo '<p style="color:red;">Erreur lors de la génération de la table "' . htmlspecialchars($nomTableDemande) . '": ' 
    . htmlspecialchars($e->getMessage()) . '</p>';
}
?>