function Navbar2(){
    return `
        <nav>
            <a href="../Pages/home.php">
                <div href="../Pages/FAQ.php" class="logo">
                    <img src="../img/logo_blanc.png" alt="ActiviTrade Logo" />
                </div>
            </a>
            <ul class="nav-links">
                <li><a href="../Pages/activite.php" class="active">Activités</a></li>
                <li><a href="../Pages/CreationActivite.php">Création</a></li>
                <li><a href="../Pages/profils.php">Mon Compte</a></li>
                <li><a href="../Pages/FAQ.php">FAQ</a></li>
                <li><a href="../Pages/Forum.php">Forum</a></li>
            </ul>
            <div class="search-container">
                <form class="search-box" id="searchForm">
                    <input type="text" 
                           name="q" 
                           id="searchInput" 
                           placeholder="Rechercher une activité..." 
                           required 
                           autocomplete="off" />
                    <button type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>
                </form>
                <div id="searchResults" class="search-results"></div>
            </div>
        </nav>
    `
}
