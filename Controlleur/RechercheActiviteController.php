<?php
// session_start();
require_once(__DIR__ . '/../Modele/ActiviteModele.php');
require_once(__DIR__ . '/../Modele/Database.php');

function listeActivites($activitiesId) {
    $html = '<div class="activities-grid">';

    if (empty($activitiesId)) {
        $html .= '<p class="messErreur">Aucune activité ne s\'appelle comme cela pour le moment...</p>';
    } else {
        foreach ($activitiesId as $id) {
            $activite = Activite::get($id);

            $nom = testValidationForm($activite['nomActivite']);
            $prix = testValidationForm($activite['prix']);
            $description = testValidationForm($activite['description'] ?? 'Pas de description disponible.');
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