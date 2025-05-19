<?php
function Footer2() {
    function getThemes() {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=activitrade', 'username', 'password');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $stmt = $pdo->query("SELECT id, nom FROM themes ORDER BY nom ASC LIMIT 3");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [
                ['id' => 1, 'nom' => 'Sport'],
                ['id' => 2, 'nom' => 'Loisirs'],
                ['id' => 3, 'nom' => 'Culture']
            ];
        }
    }
    
    // Charger les thèmes
    $themes = getThemes();
    ?>
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-column brand-column">
                <a href="../Pages/Home.php" class="footer-logo">
                    <img src='../img/logo_blanc.png' alt='ActiviTrade Logo' />
                </a>
                <div class="follow-section">
                    <div class="follow-text">Suivez-nous sur nos réseaux !</div>
                    <div class="social-icons">
                        <a href="#" class="social-icon" aria-label="Facebook">
                            <img src="../img/facebook.svg" alt="Facebook" />
                        </a>
                        <a href="#" class="social-icon" aria-label="Instagram">
                            <img src="../img/instagram.svg" alt="Instagram" />
                        </a>
                        <a href="#" class="social-icon" aria-label="TikTok">
                            <img src="../img/tiktok.svg" alt="TikTok" />
                        </a>
                        <a href="#" class="social-icon" aria-label="WhatsApp">
                            <img src="../img/whatsapp.svg" alt="WhatsApp" />
                        </a>
                    </div>
                    
                </div>
                <div class="contact-us">
                        <p>Vous avez des questions ? <a href="../pages/contact.php"> Contactez-nous</a></p>
                    </div>
            </div>
            
            <div class="footer-column">
                <h3>Activités</h3>
                <div class="footer-links">
                    <a href="../Pages/ToutesActivites.php">Decouvrir les activites</a>
                    <?php foreach ($themes as $theme): ?>
                    <a href="../pages/home.php?filter=<?php echo htmlspecialchars($theme['nom']); ?>">
                        <?php echo htmlspecialchars($theme['nom']); ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="footer-column">
                <h3>Pages</h3>
                <div class="footer-links">
                    <a href="../pages/creationactivite.php">Créer une activité</a>
                    <a href="../pages/faq.php">FAQ</a>
                    <a href="../pages/moncompte.php">Mon compte</a>
                    <a href="../pages/forum.php">Forum</a>
                </div>
            </div>
        </div>
        <div class="copyright">
            <div class="legal-links">
                <a href="../pages/MentionLegale.php">Mentions Légales</a>
                <a href="../pages/Cgu.php">Conditions générales d'utilisation</a>
            </div>
            <div class="copyright-text">
                <p>Copyright © <?php echo date('Y'); ?> ActiviTrade - Tous droits réservés</p>
            </div>
        </div>
    </footer>
<?php
}
?>