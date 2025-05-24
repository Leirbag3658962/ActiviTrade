<?php
// Connexion à la base de données
$host = "localhost";
$user = "root";
$password = "";
$dbname = "activitrade"; // vérifie bien le nom ici aussi

session_start(); // Très important pour accéder à $_SESSION


require_once(__DIR__ . '../../Components/Navbar2.php');

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Vérifie que l'utilisateur est connecté
if (isset($_SESSION['user']['id'])) {
    $userId = intval($_SESSION['user']['id']); // Sécurisation de l'ID

    // Récupère l'utilisateur connecté
    $sql = "SELECT * FROM utilisateur WHERE idUtilisateur = $userId";
    $result = $conn->query($sql);

    // Vérifie si la requête a réussi
    if (!$result) {
        die("Erreur SQL : " . $conn->error);
    }

    // Si aucun utilisateur trouvé, on en insère un (optionnel)
    if ($result->num_rows === 0) {
        $insert = "INSERT INTO utilisateur 
        (nom, prenom, email, dateNaissance, numeroRue, nomRue, codePostal, ville, pays, indicatif, telephone, role, password, photoprofil, isbanned)
        VALUES 
        ('Dupont', 'Jean', 'jean.dupont@example.com', '1990-01-01', '12', 'Rue des Lilas', '75000', 'Paris', 'France', '+33', '0612345678', 'utilisateur', 'pass123', '', 0)";
        
        $conn->query($insert);

        // Récupère le nouvel utilisateur inséré
        $result = $conn->query("SELECT * FROM utilisateur WHERE idUtilisateur = " . $conn->insert_id);
    }

    $user = $result->fetch_assoc();
} else {
    die("Utilisateur non connecté.");
}
?>





<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="../Style/Moncompte.css">
    <link rel="stylesheet" href="../Style/navbar2.css">
    <link rel="stylesheet" href="../Style/footer2.css">

    
    </head>

    <header id="navbar" class="navbar">
	<?php echo Navbar2(); ?>
</header>
	
<body>
   
   <h1> Profil</h1>
	
<div class="conteneur-boites">
    <!-- Conteneur à gauche pour les informations personnelles et l'activité réservée -->
    <div class="gauche">
        <!-- Boîte des informations personnelles -->
        <div class="boite-info">
       

        <div class="bloc-titre-modifier">
            <h4>Informations personnelles</h4>
                <a href="ModificationInfoPersonnelle.php" title="Modifier mon profil" aria-label="Modifier mon profil">
                    <img src="../img/CrayonIcone.png" alt="Modifier mon profil" class="icone-modifier">
                </a>
</div>


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




<iframe id="messagerieFrame" src="messagerie.php" style="
  position: fixed;
  bottom: 20px;
  right: 20px;
  width: 800px;
  height: 600px;
  border: none;
  border-radius: 10px;
  z-index: 1000;
  display: none;
"></iframe>



<button onclick="ouvrirMessagerie()" style="
  position: fixed;
  bottom: 30px;
  right: 20px;
  background-color: #355e3b;
  color: white;
  border: none;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  font-size: 24px;
  cursor: pointer;
  z-index: 999;
">💬</button>

<footer id="footer" class="footer"></footer>
</body>
<script>
  function ouvrirMessagerie() {
    document.getElementById("messagerieFrame").style.display = "block";
    const iframe = document.getElementById("messagerieFrame");
    iframe.contentWindow.postMessage("ouvrir-messagerie", "*");
    
  }
</script>

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
window.addEventListener("message", function(event) {
  if (event.data === "fermer-messagerie") {
    const frame = document.getElementById("messagerieFrame");
    if (frame) {
      frame.style.display = "none";
    }
  }
});

</script>
<script src="../Components/NavbarAnim.js"></script>
<script src="../Components/DragAndDrop.js"></script>
<!-- <script src="../Components/BackgroundImageChanges.js"></script> -->
<script src="../Components/Footer2.js"></script>
<script>
	document.getElementById("footer").innerHTML = Footer2();
</script>

</body>
</html>
