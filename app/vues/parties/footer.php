<footer class="site-footer">
  <link rel="stylesheet" href="<?= $GLOBALS['baseUrl'] ?>css/footer.css">

  <div class="site-footer-container">
    <!-- Réseaux sociaux EN HAUT -->
    <div class="footer-social-icons">
      <a href="https://www.ajc-formation.fr" target="_blank" rel="noopener">
        <img src="<?= $GLOBALS['baseUrl'] ?>images/assets/AJC.png" alt="AJC" class="footer-social-icon" />
      </a>
      <a href="https://www.facebook.com/AjcFormation/?locale=fr_FR" target="_blank" rel="noopener">
        <img src="<?= $GLOBALS['baseUrl'] ?>images/assets/fablab_facebook.png" alt="Facebook" class="footer-social-icon" />
      </a>
      <a href="https://fr.linkedin.com/company/ajc-formation" target="_blank" rel="noopener">
        <img src="<?= $GLOBALS['baseUrl'] ?>images/assets/LinkedinAJC.png" alt="LinkedIn" class="footer-social-icon" />
      </a>
    </div>

    <!-- Liens de navigation EN BAS - une seule ligne -->
    <div class="footer-nav-links">
      <ul>
        <li><a href="?page=accueil">Accueil</a></li>
        <li><a href="?page=forum">Forum</a></li>
        <li><a href="?page=projets">Projets</a></li>
        <li><a href="?page=webtv">WebTV</a></li>
        <li><a href="?page=contact">Contact</a></li>
      </ul>
    </div>
  </div>

  <!-- Copyright tout en bas -->
  <div class="site-footer-bottom">
    <p>&copy; <?= date("Y") ?> AJC Formation — Tous droits réservés.</p>
  </div>
</footer>