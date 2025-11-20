<?php

$current_page = $_GET['page'] ?? 'admin';


function isActive($page, $current_page) {
    return $page === $current_page ? 'active' : '';
}
?>

<aside class="sidebar">
  <div>
    <!-- Logo -->
    <div class="sidebar-logo">
      <a href="/fablabrobot/public/?page=accueil" class="sidebar-logo">
        <img src="../public/images/global/ajc_logo_blanc.png" alt="AJC Logo">
      </a>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
  <a href="?page=admin" class="<?= isActive('admin', $current_page) ?>">
    <i class="fas fa-home"></i> Dashboard
  </a>
  <a href="?page=admin-articles" class="<?= isActive('admin-articles', $current_page) ?>">
    <i class="fas fa-newspaper"></i> Articles
  </a>
  <a href="?page=admin-projets" class="<?= isActive('admin-projets', $current_page) ?>">
    <i class="fas fa-project-diagram"></i> Projets
  </a>
  <a href="?page=utilisateurs-admin" class="<?= isActive('utilisateurs-admin', $current_page) ?>">
    <i class="fas fa-users"></i> Utilisateurs
  </a>
  <a href="?page=admin-contact" class="<?= isActive('admin-contact', $current_page) ?>">
    <i class="fas fa-address-book"></i> Contact
  </a>
  <a href="?page=admin-webtv" class="<?= isActive('admin-webtv', $current_page) ?>">
    <i class="fas fa-video"></i> WebTV
  </a>
</nav>

  </div>

  <!-- Bouton Déconnexion -->
  <div class="sidebar-footer">
    <a href="?page=logout" class="logout-btn">
      <i class="fas fa-sign-out-alt"></i> Déconnexion
    </a>
  </div>
</aside>
