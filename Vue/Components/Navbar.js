function Navbar() {
    return `
    <nav class="navbar">
        <a href="#" class="nav-icon" aria-label="homepage" aria-current="page">
            <img src="../img/logo_blanc.png" alt="Activitrade logo" />
        </a>

        <div class="main-navlinks">
            <button type="button" class="hamburger"  aria-label="Toggle Navigation" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <div class="navlinks-container">
            <a href="#" aria-current="page">Accueil</a>
            <a href="#">Activités</a>
            <a href="#">Création</a>
            <a href="#">Mon compte</a>
            <a href="#">Contact</a>
            </div>
        </div>

        <div class="nav-authentication">
            <a href="#" class="user-toggler" aria-label="Sign in page">
            <img src="../img/user.svg" alt="user icon" />
            </a>
            <div class="sign-btns">
            <button type="button">Se connecter</button>
            <button type="button">S'inscrire</button>
            </div>
        </div>
    </nav>
    `;
};
