<?php
// session_start();
require_once(__DIR__ . '/../Modele/ActiviteModele.php');
require_once(__DIR__ . '/../Modele/ImageModele.php');
require_once(__DIR__ . '/../Modele/Database.php');

function listeActivites($activitiesId) {
    $html = '<div class="activities-grid">';

    if (empty($activitiesId)) {
        $activitiesId = Activite::getAllId();
        echo'<a class="rien">Aucune activité trouvée avec votre recherche!</a>';
    } 
    foreach ($activitiesId as $id) {
        $activite = Activite::get($id);
        $imagePaths = Image::get($id); 

        $nom = testValidationForm($activite['nomActivite'] ?? 'N/A');
        $prix = testValidationForm($activite['prix'] ?? '0');
        $prixDisplay = ($prix == 0 || strtolower((string)$prix) === 'gratuit') ? 'Gratuit' : htmlspecialchars($prix) . '€';
        $description = testValidationForm($activite['description'] ?? 'Pas de description disponible.');

        $defaultImagePath = "../img/banner1.jpg"; 

        $html .= '
            <div class="activity">
                <div class="activity-carousel" id="carousel-activity-' . htmlspecialchars($id) . '">
                    <div class="activity-carousel-inner">';

        if (!empty($imagePaths)) {
            foreach ($imagePaths as $index => $image) {
                $imgPath = htmlspecialchars($image['image'] ?? $defaultImagePath);
                $activeClass = ($index === 0) ? 'active' : '';
                $html .= '<div class="activity-carousel-item ' . $activeClass . '">
                            <img src="' . $imgPath . '" alt="' . $nom . ' - Image ' . ($index + 1) . '">
                          </div>';
            }
        } else {
            $html .= '<div class="activity-carousel-item active">
                        <img src="' . htmlspecialchars($defaultImagePath) . '" alt="' . $nom . '">
                      </div>';
        }

        $html .= '
                    </div>'; 

        // Ajouter flèches
        if (is_array($imagePaths) && count($imagePaths) > 1) {
            $html .= '
                        <button class="activity-carousel-control activity-prev" data-carousel-id="carousel-activity-' . htmlspecialchars($id) . '">❮</button>
                        <button class="activity-carousel-control activity-next" data-carousel-id="carousel-activity-' . htmlspecialchars($id) . '">❯</button>';
        }
        $html .= '
                </div>'; 

        $html .= '
                <h3><a class="nomActivite" href="../../Vue/Pages/activite.php?id='.$id.'">' . $nom . '</a></h3>
                <p>Coût d\'inscription: ' . $prixDisplay . '</p>
                <p>Description: ' . $description . '</p>
                <a href="ReservationActivite.php?id=' . htmlspecialchars($id) . '">
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