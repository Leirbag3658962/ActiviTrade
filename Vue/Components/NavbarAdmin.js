function NavbarAdmin() {
    return `
    <nav class="navbar">
        <a href="#" class="nav-icon" aria-label="adminpage" aria-current="page">
            <img src="../img/logo.png" alt="Activitrade logo" />
        </a>

        <div class="main-navlinks">
            <button type="button" class="hamburger"  aria-label="Toggle Navigation" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <div class="navlinks-container">
            <a href="#" aria-current="page">Accueil</a>
            <a href="#">Utilisateurs</a>
            <a href="#">Activit&eacute;s</a>
            <a href="#">R&eacute;servations</a>
            <a href="#">Discussion</a>
            <a href="#">Faq</a>
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
