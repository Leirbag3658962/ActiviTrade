<?php
// session_start();
require_once(__DIR__ . '/../Modele/ActiviteModele.php');
require_once(__DIR__ . '/../Modele/Database.php');

function listeActivites($activitiesId) {
    $html = '<div class="activities-grid">';

    if (empty($activitiesId)) {
        $activitiesId = rechercheVide();
    } 
    foreach ($activitiesId as $id) {
        $activite = Activite::get($id);

        $nom = $activite['nomActivite'];
        $prix = testValidationForm($activite['prix']);
        $description = $activite['description'] ?? 'Pas de description disponible.';
        $imagePath = "../img/banner1.jpg"; // Image 

        $prixDisplay = ($prix == 0) ? 'gratuit' : htmlspecialchars($prix) . '€';

        $html .= '
            <div class="activity">
                <img src="' . htmlspecialchars($imagePath) . '" alt="' . $nom . '">
                <h3>' . $nom . '</h3>
                <p>Coût d\'inscription: ' . $prixDisplay . '</p>
                <p>Description: ' . $description . '</p>
                <a href="ReservationActivite.php?id=' . $id . '">
                    <button class="reserve-button">Réserver</button>
                </a>
            </div>';
        
    }

    $html .= '</div>'; 
    return $html;
}

function verifRechercheSoumise($recherche){
    $pdo = getPDO();
    
    if (isset($recherche) && !empty(testValidationForm($recherche))) {
        $searchTerm = testValidationForm($recherche);
        if (function_exists('recherche')) {
            return recherche($searchTerm); 
        } else {
            return [];
        }
    }
}
?>