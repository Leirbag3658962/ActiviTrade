<?php
$host = 'localhost';
$port = '3306'; 
$dbname = 'activitrade';
$user = 'root';
$password = ''; 

session_start();
require_once(__DIR__ . '../../../Modele/Database.php');
require_once(__DIR__ . '../../Components/Navbar2.php');
require_once(__DIR__ . '../../Components/Footer2.php');

$pdo = getPDO();

// Récupérer tous les thèmes
$sqlThemes = "SELECT * FROM theme ORDER BY theme ASC";
$stmtThemes = $pdo->query($sqlThemes);
$themes = $stmtThemes->fetchAll(PDO::FETCH_ASSOC);

// Récupérer toutes les villes uniques des activités
$sqlVilles = "SELECT DISTINCT ville FROM activite ORDER BY ville ASC";
$stmtVilles = $pdo->query($sqlVilles);
$villes = $stmtVilles->fetchAll(PDO::FETCH_ASSOC);

// Récupérer ville par défaut (ville de l'utilisateur connecté)
$villeDefaut = '';
if (isset($_SESSION['idUtilisateur'])) {
    $sqlUserVille = "SELECT ville FROM adresse WHERE idUtilisateur = :idUser LIMIT 1";
    $stmtUserVille = $pdo->prepare($sqlUserVille);
    $stmtUserVille->execute(['idUser' => $_SESSION['idUtilisateur']]);
    $userVille = $stmtUserVille->fetch(PDO::FETCH_ASSOC);
    if ($userVille) {
        $villeDefaut = $userVille['ville'];
    }
}

// Initialiser les filtres (tous facultatifs)
$filtreTheme = isset($_GET['theme']) ? $_GET['theme'] : '';
$filtreVille = isset($_GET['ville']) ? $_GET['ville'] : '';  // Ville vide par défaut pour afficher toutes les activités
$filtrePrixMin = isset($_GET['prixMin']) && $_GET['prixMin'] !== '' ? intval($_GET['prixMin']) : 0;
$filtrePrixMax = isset($_GET['prixMax']) && $_GET['prixMax'] !== '' ? intval($_GET['prixMax']) : 1000;
$filtreDureeMin = isset($_GET['dureeMin']) && $_GET['dureeMin'] !== '' ? intval($_GET['dureeMin']) : 0;
$filtreDureeMax = isset($_GET['dureeMax']) && $_GET['dureeMax'] !== '' ? intval($_GET['dureeMax']) : 24;
$filtreDate = isset($_GET['date']) ? $_GET['date'] : '';

// Vérifier si des filtres sont appliqués
$filtresAppliques = !empty($filtreTheme) || !empty($filtreVille) || 
                    $filtrePrixMin > 0 || $filtrePrixMax < 1000 || 
                    $filtreDureeMin > 0 || $filtreDureeMax < 24 || 
                    !empty($filtreDate);

// Si aucun filtre n'est appliqué, récupérer toutes les activités
if (!$filtresAppliques) {
    $sql = "SELECT * FROM activite ORDER BY date ASC, nomActivite ASC";
    $stmt = $pdo->query($sql);
    $activites = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Construire la requête SQL avec les filtres
    $sql = "SELECT DISTINCT a.* FROM activite a";
    $params = [];

    // Ajouter la jointure pour le filtre de thème si nécessaire
    if (!empty($filtreTheme)) {
        $sql .= " INNER JOIN activite_theme at ON a.idActivite = at.idActivite
                INNER JOIN theme t ON at.idTheme = t.idTheme AND t.theme = :theme";
        $params['theme'] = $filtreTheme;
    }

    // Ajouter la clause WHERE
    $sql .= " WHERE 1=1";

    // Filtrer par ville
    if (!empty($filtreVille)) {
        $sql .= " AND a.ville = :ville";
        $params['ville'] = $filtreVille;
    }

    // Filtrer par prix
    $sql .= " AND a.prix BETWEEN :prixMin AND :prixMax";
    $params['prixMin'] = $filtrePrixMin;
    $params['prixMax'] = $filtrePrixMax;

    // Filtrer par durée
    $sql .= " AND a.duree BETWEEN :dureeMin AND :dureeMax";
    $params['dureeMin'] = $filtreDureeMin;
    $params['dureeMax'] = $filtreDureeMax;

    // Filtrer par date
    if (!empty($filtreDate)) {
        $sql .= " AND DATE(a.date) = :date";
        $params['date'] = $filtreDate;
    }

    // Ordonner les résultats
    $sql .= " ORDER BY a.date ASC, a.nomActivite ASC";

    // Exécuter la requête
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $activites = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Afficher des informations de débogage si demandé
if (isset($_GET['debug'])) {
    echo "<pre>";
    print_r($activites);
    echo "</pre>";
    echo "Nombre d'activités trouvées : " . count($activites);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/Vue/Style/Home.css">
    <link rel="stylesheet" href="/Vue/Style/Navbar2.css">
    <link rel="stylesheet" href="/Vue/Style/Footer2.css">
    <link rel="stylesheet" href="/Vue/Style/ToutesActivites.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toutes les activités - ActiviTrade</title>
   
</head>
<body>
    <header id="navbar" class="navbar">
        <?php echo Navbar2(); ?>
    </header>

    <div class="content">
        <h1>Toutes les activités</h1>
        
        <!-- Section de filtrage avec toggle -->
        <div class="filter-container">
            <div class="filter-header" id="filter-toggle">
                <h2>Filtrer les activités</h2>
                <span class="filter-toggle-icon">▼</span>
            </div>
            
            <form class="filter-form" action="" method="GET">
                <div class="filter-group">
                    <label for="theme">Thème</label>
                    <select name="theme" id="theme">
                        <option value="">Tous les thèmes</option>
                        <?php foreach ($themes as $theme): ?>
                            <option value="<?= htmlspecialchars($theme['theme']) ?>" <?= $filtreTheme === $theme['theme'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($theme['theme']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="ville">Ville</label>
                    <select name="ville" id="ville">
                        <option value="">Toutes les villes</option>
                        <?php foreach ($villes as $ville): ?>
                            <option value="<?= htmlspecialchars($ville['ville']) ?>" <?= $filtreVille === $ville['ville'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($ville['ville']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="prix">Prix (€)</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="number" name="prixMin" id="prixMin" min="0" max="1000" value="<?= $filtrePrixMin ?>" placeholder="Min">
                        <input type="number" name="prixMax" id="prixMax" min="0" max="1000" value="<?= $filtrePrixMax ?>" placeholder="Max">
                    </div>
                </div>
                
                <div class="filter-group">
                    <label for="duree">Durée (heures)</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="number" name="dureeMin" id="dureeMin" min="0" max="24" value="<?= $filtreDureeMin ?>" placeholder="Min">
                        <input type="number" name="dureeMax" id="dureeMax" min="0" max="24" value="<?= $filtreDureeMax ?>" placeholder="Max">
                    </div>
                </div>
                
                <div class="filter-group">
                    <label for="date">Date</label>
                    <input type="date" name="date" id="date" value="<?= $filtreDate ?>">
                </div>
                
                <div class="filter-buttons">
                    <button type="submit" class="apply-button">Appliquer les filtres</button>
                    <button type="button" class="reset-button" onclick="window.location.href='ToutesActivites.php'">Réinitialiser</button>
                </div>
            </form>
        </div>
        
        <!-- Section des activités -->
        <div class="activities-container">
            <?php if (empty($activites)): ?>
                <div class="no-activities">
                    <h3>Aucune activité ne correspond à vos critères de recherche</h3>
                    <p>Essayez de modifier vos filtres pour trouver plus d'activités.</p>
                </div>
            <?php else: ?>
                <?php foreach ($activites as $activite): ?>
                    <div class="activity-card">
                        <img src="../img/banner1.jpg" alt="<?= htmlspecialchars($activite['nomActivite']) ?>">
                        <div class="activity-info">
                            <h3><?= htmlspecialchars($activite['nomActivite']) ?></h3>
                            
                            <div class="activity-meta">
                                <span>
                                    <i class="fa fa-map-marker"></i> 
                                    <?= htmlspecialchars($activite['ville']) ?>
                                </span>
                                <span>
                                    <i class="fa fa-calendar"></i>
                                    <?= (isset($activite['date'])) ? date('d/m/Y', strtotime($activite['date'])) : 'Date non définie' ?>
                                </span>
                            </div>
                            
                            <div class="activity-meta">
                                <span>
                                    <i class="fa fa-clock-o"></i>
                                    Durée: <?= isset($activite['duree']) ? $activite['duree'] . 'h' : 'Non précisée' ?>
                                </span>
                                <span>
                                    <i class="fa fa-euro"></i>
                                    Prix: <?= $activite['prix'] == 0 ? 'Gratuit' : $activite['prix'] . '€' ?>
                                </span>
                            </div>
                            
                            <p><?= (strlen($activite['description']) > 100) ? htmlspecialchars(substr($activite['description'], 0, 100)) . '...' : htmlspecialchars($activite['description']) ?></p>
                            
                            <a href="Activite.php?id=<?= $activite['idActivite'] ?>">
                                <button class="view-button">Voir</button>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <footer id="footer" class="footer">
        <?php echo Footer2(); ?>
    </footer>

    <script>
        // Script pour améliorer l'expérience utilisateur des filtres
        document.addEventListener('DOMContentLoaded', function() {
            // Synchroniser les champs min/max
            const prixMin = document.getElementById('prixMin');
            const prixMax = document.getElementById('prixMax');
            const dureeMin = document.getElementById('dureeMin');
            const dureeMax = document.getElementById('dureeMax');
            
            prixMin.addEventListener('change', function() {
                if (parseInt(prixMin.value) > parseInt(prixMax.value)) {
                    prixMax.value = prixMin.value;
                }
            });
            
            prixMax.addEventListener('change', function() {
                if (parseInt(prixMax.value) < parseInt(prixMin.value)) {
                    prixMin.value = prixMax.value;
                }
            });
            
            dureeMin.addEventListener('change', function() {
                if (parseInt(dureeMin.value) > parseInt(dureeMax.value)) {
                    dureeMax.value = dureeMin.value;
                }
            });
            
            dureeMax.addEventListener('change', function() {
                if (parseInt(dureeMax.value) < parseInt(dureeMin.value)) {
                    dureeMin.value = dureeMax.value;
                }
            });
            
            // Gestion du toggle de la section de filtres
            const filterToggle = document.getElementById('filter-toggle');
            const filterForm = document.querySelector('.filter-form');
            const toggleIcon = document.querySelector('.filter-toggle-icon');
            
            // Si des filtres sont appliqués, ouvrir automatiquement la section
            <?php if ($filtresAppliques): ?>
                filterForm.classList.add('active');
                toggleIcon.classList.add('rotate-icon');
            <?php endif; ?>
            
            filterToggle.addEventListener('click', function() {
                filterForm.classList.toggle('active');
                toggleIcon.classList.toggle('rotate-icon');
            });
        });

        // Empêcher l'envoi des champs vides ou par défaut
document.querySelector('.filter-form').addEventListener('submit', function(event) {
    // Empêcher l'envoi standard du formulaire
    event.preventDefault();
    
    // Collecter uniquement les champs avec des valeurs non-défaut
    const formData = new FormData();
    
    // Vérifier le thème
    if (document.getElementById('theme').value !== '') {
        formData.append('theme', document.getElementById('theme').value);
    }
    
    // Vérifier la ville
    if (document.getElementById('ville').value !== '') {
        formData.append('ville', document.getElementById('ville').value);
    }
    
    // Vérifier le prix min (seulement s'il n'est pas 0)
    if (document.getElementById('prixMin').value !== '' && parseInt(document.getElementById('prixMin').value) > 0) {
        formData.append('prixMin', document.getElementById('prixMin').value);
    }
    
    // Vérifier le prix max (seulement s'il n'est pas 1000)
    if (document.getElementById('prixMax').value !== '' && parseInt(document.getElementById('prixMax').value) < 1000) {
        formData.append('prixMax', document.getElementById('prixMax').value);
    }
    
    // Vérifier la durée min (seulement si elle n'est pas 0)
    if (document.getElementById('dureeMin').value !== '' && parseInt(document.getElementById('dureeMin').value) > 0) {
        formData.append('dureeMin', document.getElementById('dureeMin').value);
    }
    
    // Vérifier la durée max (seulement si elle n'est pas 24)
    if (document.getElementById('dureeMax').value !== '' && parseInt(document.getElementById('dureeMax').value) < 24) {
        formData.append('dureeMax', document.getElementById('dureeMax').value);
    }
    
    // Vérifier la date
    if (document.getElementById('date').value !== '') {
        formData.append('date', document.getElementById('date').value);
    }
    
    // Construire l'URL avec uniquement les paramètres non vides
    let url = 'ToutesActivites.php';
    const params = new URLSearchParams(formData);
    if (params.toString()) {
        url += '?' + params.toString();
    }
    
    // Rediriger vers la nouvelle URL
    window.location.href = url;
});
    </script>
</body>
</html>