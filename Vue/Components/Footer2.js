function Footer2(){
    return `
        <footer class="footer">
            <div class="footer-content">
                <div class="contact-section">
                    <h2>Contactez-nous</h2>
                    <p>Si vous avez des suggestions de modifications à apporter à notre site Web, veuillez nous contacter.</p>
                    <div class="social-icons">
                        <a href="#" class="social-icon">X</a>
                        <a href="#" class="social-icon">Facebook</a>
                        <a href="#" class="social-icon">Instagram</a>
                        <a href="#" class="social-icon">LinkedIn</a>
                    </div>
                </div>
                <div class="contact-form">
                    <form id="contactForm" method="post" action="../Components/processContact.php">
                        <input type="text" name="name" placeholder="Nom" required>
                        <input type="email" name="email" placeholder="Adresse e-mail" required>
                        <textarea name="message" placeholder="Description" required></textarea>
                        <button type="submit">Envoyer</button>
                    </form>
                    <div id="messageStatus" style="display:none; color: white; margin-top: 10px;"></div>
                </div>
            </div>
            <div class="copyright">
                <a href="MentionLegale.php">Mentions Légales</a>
                <a href="Cgu.php">Conditions générales d'utilisation</a>

                <p>Copyright ©2025 Tous droits réservés</p>
            </div>
        </footer>
    `;
}
