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

// Déterminer le filtre actif
$activeFilter = 'Nouveau';
if (isset($_GET['filter'])) {
    $activeFilter = $_GET['filter'];
}

// Récupérer les activités en fonction du filtre
if ($activeFilter == 'Nouveau') {
    // Pour les nouvelles activités, on récupère toutes les activités triées par ID décroissant (les plus récentes)
    $sql = "SELECT * FROM activite ORDER BY idActivite DESC";
    $stmt = $pdo->query($sql);
    $filteredActivites = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Pour un thème spécifique, on utilise une jointure avec activite_theme
    $sql = "SELECT a.* FROM activite a
            INNER JOIN activite_theme at ON a.idActivite = at.idActivite
            INNER JOIN theme t ON at.idTheme = t.idTheme
            WHERE t.theme = :theme
            ORDER BY a.idActivite DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['theme' => $activeFilter]);
    $filteredActivites = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/Vue/Style/Home.css">
    <link rel="stylesheet" href="/Vue/Style/Navbar2.css">
    <link rel="stylesheet" href="/Vue/Style/Footer2.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ActiviTrade</title>
</head>
<body>
    <header id="navbar" class="navbar">
        <?php echo Navbar2(); ?>
    </header>

    <div class="banner">
        <div class="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="banner-content">
                        <h1>ActiviTrade</h1>
                        <p>Proposez des activités, et participez à celles qui vous sont proposées.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="banner-content">
                        <h1>Découvrez</h1>
                        <p>Explorez une multitude d'activités passionnantes.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="banner-content">
                        <h1>Partagez</h1>
                        <p>Créez et partagez vos propres événements.</p>
                    </div>
                </div>
            </div>
            <button class="carousel-control prev">❮</button>
            <button class="carousel-control next">❯</button>
            <div class="carousel-indicators">
                <span class="indicator active"></span>
                <span class="indicator"></span>
                <span class="indicator"></span>
            </div>
        </div>
    </div>

    <div class="content">
        <section class="activities">
            <h2>Activités</h2>
            <p>Découvrez tous les événements ici, ou sélectionnez l'événement qui vous intéresse par type et consultez les informations détaillées.</p>
            
            <div class="tags">
                <a href="?filter=Nouveau" class="tag <?= $activeFilter == 'Nouveau' ? 'active' : '' ?>">Nouveau</a>
                
                <!-- Afficher seulement les 3 premiers thèmes -->
                <?php 
                $displayedThemes = 0;
                foreach ($themes as $theme): 
                    if ($displayedThemes < 3): // Limite à 3 thèmes
                        $displayedThemes++;
                ?>
                    <a href="?filter=<?= urlencode($theme['theme']) ?>" class="tag <?= $activeFilter == $theme['theme'] ? 'active' : '' ?>">
                        <?= htmlspecialchars($theme['theme']) ?>
                    </a>
                <?php 
                    endif;
                endforeach; 
                ?>
                <a href="ToutesActivites.php" class="tag">Plus</a>
            </div>
            
            <div class="activities-grid">
                <?php if (empty($filteredActivites)): ?>
                    <p>Aucune activité ne correspond à ce filtre.</p>
                <?php else: ?>
                    <?php foreach ($filteredActivites as $activite): ?>
                        <div class="activity">
                            <img src="../img/banner1.jpg" alt="<?= htmlspecialchars($activite['nomActivite']) ?>">
                            <h3><?= htmlspecialchars($activite['nomActivite']) ?></h3>
                            <p>Coût d'inscription: <?= $activite['prix'] == 0 ? 'gratuit' : $activite['prix'] . '€' ?></p>
                            <p>Description: <?= htmlspecialchars($activite['description']) ?></p>
                            <a href="Activite.php?id=<?= $activite['idActivite'] ?>">
                                <button class="view-button">Voir plus</button>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="slider-controls">
                <button class="slider-arrow prev-arrow">&lt; Précédent</button>
                <button class="slider-arrow next-arrow">Suivant &gt;</button>
            </div>

            <div class="slider-dots">
                <!-- Les points seront générés dynamiquement par JavaScript -->
            </div>
        </section>

        <section class="about">
            <h2>À propos</h2>
            <div class="about-grid">
                <div class="about-item">
                    <h3>Autogestion</h3>
                    <p>Suivez et participez aux activités qui vous intéressent, choisissez celles que vous voulez joindre.</p>
                </div>
                <div class="about-item">
                    <h3>Flexibilité</h3>
                    <p>Créez votre propre événement et invitez votre famille et vos amis à se joindre à vous.</p>
                </div>
                <div class="about-item">
                    <h3>Sécurité</h3>
                    <p>Protégez vos informations en suivant nos normes européennes de cybersécurité.</p>
                </div>
                <div class="about-item">
                    <h3>Intéressant</h3>
                    <p>Trouvez de nombreux amis partageant les mêmes idées.</p>
                </div>
            </div>
        </section>

        <div class="cta-container">
            <a href="CreationActivite.php">
                <button class="cta-button">Créer votre événement</button>
            </a>
        </div>
    </div>
    <footer id="footer" class="footer">
        <?php echo Footer2(); ?>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Carousel functionality for banner
        const carousel = document.querySelector('.carousel');
        const items = carousel.querySelectorAll('.carousel-item');
        const indicators = carousel.querySelectorAll('.indicator');
        const prevBtn = carousel.querySelector('.prev');
        const nextBtn = carousel.querySelector('.next');
        let currentIndex = 0;
        let interval;

        function showSlide(index) {
            // Remove active class from all items and indicators
            items.forEach(item => item.classList.remove('active'));
            indicators.forEach(indicator => indicator.classList.remove('active'));
            
            // Add active class to current item and indicator
            items[index].classList.add('active');
            indicators[index].classList.add('active');
            currentIndex = index;
        }

        function nextSlide() {
            let nextIndex = currentIndex + 1;
            if (nextIndex >= items.length) {
                nextIndex = 0;
            }
            showSlide(nextIndex);
        }

        function prevSlide() {
            let prevIndex = currentIndex - 1;
            if (prevIndex < 0) {
                prevIndex = items.length - 1;
            }
            showSlide(prevIndex);
        }

        // Event listeners for controls
        prevBtn.addEventListener('click', () => {
            clearInterval(interval);
            prevSlide();
            startAutoSlide();
        });

        nextBtn.addEventListener('click', () => {
            clearInterval(interval);
            nextSlide();
            startAutoSlide();
        });

        // Event listeners for indicators
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                clearInterval(interval);
                showSlide(index);
                startAutoSlide();
            });
        });

        function startAutoSlide() {
            interval = setInterval(nextSlide, 5000); // Change slide every 5 seconds
        }

        // Initialize carousel
        showSlide(0);
        startAutoSlide();

        // Pause auto-slide when hovering over carousel
        carousel.addEventListener('mouseenter', () => {
            clearInterval(interval);
        });

        carousel.addEventListener('mouseleave', () => {
            startAutoSlide();
        });
    });
    </script>

    <!-- Script pour le carousel d'activités -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestionnaire pour le carousel d'activités
        const activitiesGrid = document.querySelector('.activities-grid');
        const activities = document.querySelectorAll('.activity');
        const prevArrow = document.querySelector('.prev-arrow');
        const nextArrow = document.querySelector('.next-arrow');
        const sliderDots = document.querySelector('.slider-dots');
        
        // Nombre d'activités à afficher à la fois (4 sur desktop, moins sur mobile)
        const getItemsPerPage = () => {
            if (window.innerWidth < 768) return 1;
            if (window.innerWidth < 1024) return 2;
            return 4;
        };
        
        let itemsPerPage = getItemsPerPage();
        let currentPage = 0;
        let totalPages = Math.ceil(activities.length / itemsPerPage);
        
        // Fonction pour initialiser les dots de navigation
        function initDots() {
            sliderDots.innerHTML = '';
            for (let i = 0; i < totalPages; i++) {
                const dot = document.createElement('span');
                dot.classList.add('dot');
                if (i === 0) dot.classList.add('active');
                dot.addEventListener('click', () => {
                    goToPage(i);
                });
                sliderDots.appendChild(dot);
            }
        }
        
        // Fonction pour afficher la page courante
        function showPage(page) {
            // On s'assure que la page est dans les limites
            if (page < 0) page = 0;
            if (page >= totalPages) page = totalPages - 1;
            
            currentPage = page;
            
            // Cacher toutes les activités
            activities.forEach(activity => {
                activity.style.display = 'none';
            });
            
            // Afficher les activités de la page courante
            const startIdx = currentPage * itemsPerPage;
            const endIdx = Math.min(startIdx + itemsPerPage, activities.length);
            
            for (let i = startIdx; i < endIdx; i++) {
                activities[i].style.display = 'block';
            }
            
            // Update active dot
            const dots = document.querySelectorAll('.dot');
            dots.forEach((dot, idx) => {
                dot.classList.toggle('active', idx === currentPage);
            });
            
            // Update arrow visibility
            prevArrow.style.opacity = currentPage === 0 ? '0.5' : '1';
            nextArrow.style.opacity = currentPage === totalPages - 1 ? '0.5' : '1';
        }
        
        // Navigation entre les pages
        function goToPage(page) {
            showPage(page);
        }
        
        // Event listeners pour les flèches
        prevArrow.addEventListener('click', () => {
            if (currentPage > 0) {
                goToPage(currentPage - 1);
            }
        });
        
        nextArrow.addEventListener('click', () => {
            if (currentPage < totalPages - 1) {
                goToPage(currentPage + 1);
            }
        });
        
        // Recalculer le carousel lors du redimensionnement de la fenêtre
        window.addEventListener('resize', () => {
            const newItemsPerPage = getItemsPerPage();
            if (newItemsPerPage !== itemsPerPage) {
                itemsPerPage = newItemsPerPage;
                totalPages = Math.ceil(activities.length / itemsPerPage);
                initDots();
                showPage(0); // Revenir à la première page après redimensionnement
            }
        });
        
        // Initialisation
        initDots();
        showPage(0);
    });
    </script>
</body>
</html>