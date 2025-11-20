<?php
// Vue : app/vues/projets/projet_creation.php
if (session_status() === PHP_SESSION_NONE) session_start();
$role = $_SESSION['utilisateur_role'] ?? '';

if (!in_array($role, ['Admin', 'Éditeur', 'Editeur'])) {
    header('Location: ?page=projets');
    exit;
}

// baseUrl vient du routeur public/index.php
$base = $GLOBALS['baseUrl'] ?? '/';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Créer un projet - Fablab</title>

  <!-- CSS local (NOUVEAU) -->
  <link rel="stylesheet" href="<?= htmlspecialchars($base) ?>css/ajouter.css">

  <!-- Icônes -->
  <link rel="preconnect" href="https://cdnjs.cloudflare.com">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>

  <div class="creation-container">
    <h1><i class="fas fa-diagram-project"></i> Créer un nouveau projet</h1>

    <form action="?page=projet_enregistrer" method="POST" enctype="multipart/form-data" class="creation-form">

      <div class="form-group">
        <label for="titre">Titre du projet <span class="req">*</span></label>
        <input type="text" id="titre" name="titre" required>
      </div>

      <div class="form-group">
        <label for="description">Description <span class="req">*</span></label>
        <textarea id="description" name="description" rows="8" required></textarea>
      </div>

      <div class="form-group">
        <label for="image">Image (optionnel)</label>
        <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp">
        <p class="hint"><i class="fa-regular fa-image"></i> JPG/PNG/WebP – 2 Mo max</p>
      </div>

      <div class="form-actions">
        <a href="?page=projets" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Annuler</a>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Publier</button>
      </div>
    </form>
  </div>

</body>
</html>
