<?php
session_start();

require_once(__DIR__ . '/../Modele/LienPDO.php');
require_once(__DIR__ . '/../Modele/AdminModele.php');
$pdo = lienPDO();

// Validation POST
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

// Récupérer colonnes
try {
    $colonnesDetails = recupColonnes($nomTableDemande);

    
    $htmlForm = '<form id="add-entity-form" data-table="' . htmlspecialchars($nomTableDemande) . '">';
    $htmlForm .= '<h3>Ajouter dans la table : ' . htmlspecialchars($nomTableDemande) . '</h3>';

    foreach ($colonnesDetails as $col) {
        $colName = $col['Field'];
        $colType = strtolower($col['Type']);
        $colNull = strtoupper($col['Null']) === 'YES';
        $colKey = strtoupper($col['Key']);
        $colDefault = $col['Default'];
        $colExtra = strtolower($col['Extra']);

        
        if ($colKey === 'PRI' && strpos($colExtra, 'auto_increment') !== false) {
            continue; 
        }

        $htmlForm .= '<div>';
        $htmlForm .= '<label for="add_' . htmlspecialchars($colName) . '">' . htmlspecialchars($colName) . ':</label><br>';

        // Trouver type input
        $inputType = 'text'; 
        $attributes = '';

        if (strpos($colType, 'int') !== false || strpos($colType, 'float') !== false || strpos($colType, 'double') !== false || strpos($colType, 'decimal') !== false) {
            $inputType = 'number';
            if (strpos($colType, 'float') !== false || strpos($colType, 'double') !== false || strpos($colType, 'decimal') !== false) {
                $attributes .= ' step="any"';
            }
        } elseif (strpos($colType, 'date') !== false && strpos($colType, 'datetime') === false) {
             $inputType = 'date';
        } elseif (strpos($colType, 'datetime') !== false || strpos($colType, 'timestamp') !== false) {
             $inputType = 'datetime-local';
        } elseif (strpos($colType, 'time') !== false) {
             $inputType = 'time';
        } elseif (strpos($colType, 'text') !== false || strpos($colType, 'longtext') !== false) {
            
             $htmlForm .= '<textarea id="add_' . htmlspecialchars($colName) . '" name="' . htmlspecialchars($colName) . '"';
             if (!$colNull && $colDefault === null) { $htmlForm .= ' required'; }
             $htmlForm .= '></textarea>';
             $htmlForm .= '</div>';
             continue; 

        } elseif (strpos($colType, 'enum') !== false || strpos($colType, 'set') !== false) {

             preg_match_all("/'([^']*)'/", $colType, $matches);
             $options = $matches[1] ?? [];
             $htmlForm .= '<select id="add_' . htmlspecialchars($colName) . '" name="' . htmlspecialchars($colName) . '"';
             if (!$colNull && $colDefault === null) { $htmlForm .= ' required'; }
             $htmlForm .= '>';
             if ($colNull) { $htmlForm .= '<option value="">(Vide)</option>'; } 
             foreach ($options as $option) {
                 $htmlForm .= '<option value="' . htmlspecialchars($option) . '">' . htmlspecialchars($option) . '</option>';
             }
             $htmlForm .= '</select>';
             $htmlForm .= '</div>';
             continue; 
        }
        // Ajoutez d'autres types si nécessaire (boolean -> checkbox ?, etc.)


        $htmlForm .= '<input type="' . $inputType . '" id="add_' . htmlspecialchars($colName) . '" name="' . htmlspecialchars($colName) . '"' . $attributes;

        // Ajout 'required' si colonne ne peut pas être NULL 
        if (!$colNull && $colDefault === null && $colKey !== 'PRI') { 
            $htmlForm .= ' required';
        }
        $htmlForm .= '>';
        $htmlForm .= '</div>';
    }

   
    $htmlForm .= '<div class="form-buttons">';
    $htmlForm .= '<button type="submit">Enregistrer</button>';
    $htmlForm .= '<button type="button" id="cancel-add-button">Annuler</button>'; // Bouton pour fermer le modal
    $htmlForm .= '</div>';

    $htmlForm .= '</form>'; 

    echo $htmlForm;

} catch (PDOException $e) {
    echo '<p style="color:red;">Erreur lors de la génération du formulaire: ' . htmlspecialchars($e->getMessage()) . '</p>';
}
?>