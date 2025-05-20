<?php
require_once(__DIR__ . '../../../Modele/Database.php');

function Navbar2() {
    ?>
    <nav>
        <a href='../Pages/home.php'>
            <div href='../Pages/FAQ.php' class='logo'>
                <img src='../img/logo_blanc.png' alt='ActiviTrade Logo' />
            </div>
        </a>
        <ul class='nav-links'>
            <li><a href='../Pages/RechercheActivite.php'>Activités</a></li>
            <li><a href='../Pages/CreationActivite.php'>Création</a></li>
            <li><a href='../Pages/FAQ.php'>FAQ</a></li>
            <li><a href='../Pages/Forum.php'>Forum</a></li>
            <?php if (!isset($_SESSION['user'])): ?>
                <li><a href='../Pages/SignIn.php'>S'inscrire</a></li>
                <li><a href='../Pages/LogIn.php'>Se connecter</a></li>
            <?php else: ?>
                <li><a href='../Pages/profils.php'>Mon Compte</a></li>
                <li><a href='../../Controlleur/Deconnexion.php'>Se deconnecter</a></li>
            <?php endif; ?>
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin'): ?>
                <li><a href='../Pages/Admin.php'>Admin</a></li>
            <?php endif; ?>
        </ul>
        <form method="GET" action="../Pages/RechercheActivite.php" class="rechercheForm" value="<?php echo testValidationForm($_GET['q'] ?? ''); ?>">
            <div class='search-box'>
                <input type='text' name="q" placeholder='Rechercher' />
                <button type='submit'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'>
                        <circle cx='11' cy='11' r='8'></circle>
                        <line x1='21' y1='21' x2='16.65' y2='16.65'></line>
                    </svg>
                </button>
            </div>
        </form>
    </nav>
    <?php
}
?>