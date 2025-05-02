<?php
// Connexion à la base de données
$host = "localhost";
$user = "root";
$password = "";
$dbname = "activititrade";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Récupère un utilisateur
$sql = "SELECT * FROM utilisateur LIMIT 1";
$result = $conn->query($sql);

// Si aucun utilisateur n'existe, on en insère un par défaut
if ($result->num_rows === 0) {
    $insert = "INSERT INTO utilisateur 
    (nom, prenom, email, dateNaissance, numeroRue, nomRue, codePostal, ville, pays, indicatif, telephone, role, password, photoprofil, isbanned)
    VALUES 
    ('Dupont', 'Jean', 'jean.dupont@example.com', '1990-01-01', '12', 'Rue des Lilas', '75000', 'Paris', 'France', '+33', '0612345678', 'utilisateur', 'pass123', '', 0)";
    
    $conn->query($insert);
    $result = $conn->query($sql); // Refaire la requête après insertion
}

$user = $result->fetch_assoc();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="../Style/Profil.css.css">
    <link rel="stylesheet" href="../Style/Navbar.css">

    
    </head>

    <header id="navbar" class="navbar">
    </header>
	
<body>
   
   <h1> Profil</h1>
	
<div class="conteneur-boites">
    <!-- Conteneur à gauche pour les informations personnelles et l'activité réservée -->
    <div class="gauche">
        <!-- Boîte des informations personnelles -->
        <div class="boite-info">
        <h4>Informations personnelles</h4>
        <p><strong>Nom :</strong> <?= htmlspecialchars($user['nom']) ?></p>
        <p><strong>Prénom :</strong> <?= htmlspecialchars($user['prenom']) ?></p>
        <p><strong>Numéro :</strong> <?= htmlspecialchars($user['telephone']) ?></p>
        <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Date de naissance :</strong> <?= htmlspecialchars($user['dateNaissance']) ?></p>
    </div>

       <!-- Activité réservé -->
<div class="boite-info">
    <h4>Activité réservé</h4>
    <div class="carousel">
        <div class="carousel-inner">
            <div class="slide">
                <img src="https://www.jeduka.com/storage/school_image/2/isep.jpg" alt="Logo ISEP">
				<p>Description de l'activité précédente 3</p>
            </div>
            <div class="slide">
                <img src="https://www.jeduka.com/storage/school_image/2/isep.jpg" alt="Logo ISEP">
				<p>Description de l'activité précédente 3</p>
            </div>
            <div class="slide">
                <img src="https://www.jeduka.com/storage/school_image/2/isep.jpg" alt="Logo ISEP">
				<p>Description de l'activité précédente 3</p>
            </div>
            <div class="slide">
                <img src="https://www.jeduka.com/storage/school_image/2/isep.jpg" alt="Logo ISEP">
				<p>Description de l'activité précédente 3</p>
            </div>
            <div class="slide">
                <img src="https://www.jeduka.com/storage/school_image/2/isep.jpg" alt="Logo ISEP">
				<p>Description de l'activité précédente 3</p>
            </div>
        </div>
        <div class="carousel-indicators">
            <span class="indicator active" onclick="moveSlide(0)"></span>
            <span class="indicator" onclick="moveSlide(1)"></span>
            <span class="indicator" onclick="moveSlide(2)"></span>
            <span class="indicator" onclick="moveSlide(3)"></span>
            <span class="indicator" onclick="moveSlide(4)"></span>
        </div>
    </div>
</div>

    </div>

    <!-- Conteneur à droite pour l'activité dans la liste d'attente -->
    <div class="droite">
        <div class="boite-info">
            <h4>Activité dans la liste d'attente</h4>
            <div class="liste-activites">
                <div class="activite">
                    <img src="https://www.jeduka.com/storage/school_image/2/isep.jpg" alt="Image ISEP">
                    <p>Activité 1 - Description ici</p>
                </div>
                <div class="activite">
                    <img src="https://www.jeduka.com/storage/school_image/2/isep.jpg" alt="Image ISEP">
                    <p>Activité 2 - Description ici</p>
                </div>
                <div class="activite">
                    <img src="https://www.jeduka.com/storage/school_image/2/isep.jpg" alt="Image ISEP">
                    <p>Activité 3 - Description ici</p>
                </div>
                <div class="activite">
                    <img src="https://www.jeduka.com/storage/school_image/2/isep.jpg" alt="Image ISEP">
                    <p>Activité 4 - Description ici</p>
                </div>
                <div class="activite">
                    <img src="https://www.jeduka.com/storage/school_image/2/isep.jpg" alt="Image ISEP">
                    <p>Activité 5 - Description ici</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Conteneur des carrousels -->
<div class="conteneur-activites-bas">
    <!-- Activité créée -->
    <div class="boite-info">
        <h4>Activité créée</h4>
        <div class="carousel" id="carousel-creee">
            <div class="carousel-inner">
                <div class="slide"><img src="https://www.jeduka.com/storage/school_image/2/isep.jpg" alt="Logo ISEP"><p>Description de l'activité précédente 3</p></div>
                <div class="slide"><img src="https://www.jeduka.com/storage/school_image/2/isep.jpg" alt="Logo ISEP"><p>Description de l'activité précédente 3</p></div>
                <div class="slide"><img src="https://www.jeduka.com/storage/school_image/2/isep.jpg" alt="Logo ISEP"><p>Description de l'activité précédente 3</p></div>
                <div class="slide"><img src="https://www.jeduka.com/storage/school_image/2/isep.jpg" alt="Logo ISEP"><p>Description de l'activité précédente 3</p></div>
                <div class="slide"><img src="https://www.jeduka.com/storage/school_image/2/isep.jpg" alt="Logo ISEP"><p>Description de l'activité précédente 3</p></div>
            </div>
            <div class="carousel-indicators">
                <span class="indicator active" data-index="0"></span>
                <span class="indicator" data-index="1"></span>
                <span class="indicator" data-index="2"></span>
                <span class="indicator" data-index="3"></span>
                <span class="indicator" data-index="4"></span>
            </div>
        </div>
    </div>

    <!-- Activité précédente -->
    <div class="boite-info">
        <h4>Activité précédente</h4>
        <div class="carousel" id="carousel-precedente">
            <div class="carousel-inner">
                <div class="slide"><img src="https://www.jeduka.com/storage/school_image/2/isep.jpg" alt="Logo ISEP"><p>Description de l'activité précédente 3</p></div>
                <div class="slide"><img src="https://www.jeduka.com/storage/school_image/2/isep.jpg" alt="Logo ISEP"><p>Description de l'activité précédente 3</p></div>
                <div class="slide"><img src="https://www.jeduka.com/storage/school_image/2/isep.jpg" alt="Logo ISEP"><p>Description de l'activité précédente 3</p></div>
                <div class="slide"><img src="https://www.jeduka.com/storage/school_image/2/isep.jpg" alt="Logo ISEP"><p>Description de l'activité précédente 3</p></div>
                <div class="slide"><img src="https://www.jeduka.com/storage/school_image/2/isep.jpg" alt="Logo ISEP"><p>Description de l'activité précédente 3</p></div>
            </div>
            <div class="carousel-indicators">
                <span class="indicator active" data-index="0"></span>
                <span class="indicator" data-index="1"></span>
                <span class="indicator" data-index="2"></span>
                <span class="indicator" data-index="3"></span>
                <span class="indicator" data-index="4"></span>
            </div>
        </div>
    </div>
</div>
</body>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // Gestion des carrousels dynamiques
    document.querySelectorAll('.carousel').forEach((carousel) => {
        const carouselInner = carousel.querySelector('.carousel-inner');
        const indicators = carousel.querySelectorAll('.indicator');
        const slides = carousel.querySelectorAll('.slide');
        const slideWidth = slides[0]?.clientWidth || 0;

        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                carouselInner.style.transform = `translateX(${-index * slideWidth}px)`;

                indicators.forEach(ind => ind.classList.remove('active'));
                indicator.classList.add('active');
            });
        });
    });

    // Gestion du slider automatique s'il existe
    const images = document.querySelectorAll('.image-slider img');
    if (images.length > 0) {
        let currentIndex = 0;
        images[currentIndex].classList.add('active');

        setInterval(() => {
            images[currentIndex].classList.remove('active');
            currentIndex = (currentIndex + 1) % images.length;
            images[currentIndex].classList.add('active');
        }, 2000);
    }
});
</script>
<script src="../Components/Navbar.js"></script>
<script>
    document.getElementById("navbar").innerHTML = Navbar();
</script>
<script src="../Components/NavbarAnim.js"></script>
<script src="../Components/Footer.js"></script>


</body>
</html>
