<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!in_array($_SESSION['utilisateur_role'] ?? '', ['Éditeur', 'Editeur', 'Admin'])) {
    header('Location: ?page=articles');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Créer un Article</title>
  <link rel="stylesheet" href="css/admin.css">
</head>
<body>
  <div class="dashboard">
    <h1><i class="fas fa-pen-nib"></i> Créer un nouvel article</h1>
    <form action="?page=article_enregistrer" method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label>Titre *</label>
        <input type="text" name="titre" required>
      </div>
      <div class="form-group">
        <label>Contenu *</label>
        <textarea name="contenu" required></textarea>
      </div>
      <div class="form-group">
        <label>Image (optionnelle)</label>
        <input type="file" name="image">
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Publier</button>
        <a href="?page=articles" class="btn btn-secondary">Annuler</a>
      </div>
    </form>
  </div>
</body>
</html>
