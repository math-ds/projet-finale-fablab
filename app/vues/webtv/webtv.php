<?php require __DIR__ . '/../parties/header.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>WebTV - FabLab</title>
  <link rel="stylesheet" href="../public/css/webtv.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php if (!empty($_SESSION['message'])): ?>
<div class="alert alert-<?= htmlspecialchars($_SESSION['message_type'] ?? 'info') ?> alert-dismissible fade show m-3" role="alert">
    <?= htmlspecialchars($_SESSION['message']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php 
    unset($_SESSION['message'], $_SESSION['message_type']);
endif; 
?>

<div class="youtube-container">
  <div class="youtube-layout">
    
    <!-- Colonne principale: Vidéo + Commentaires -->
    <div class="primary-column">
      
      <!-- Lecteur vidéo -->
      <div class="video-player-container">
        <div class="video-player-wrapper">
          <?php if ($current && $current['type'] === 'youtube' && !empty($current['youtube_id'])): ?>
            <iframe 
              id="mainVideoPlayer"
              src="https://www.youtube.com/embed/<?= htmlspecialchars($current['youtube_id']) ?>?autoplay=1"
              title="<?= htmlspecialchars($current['titre'] ?? 'Vidéo') ?>" 
              allowfullscreen
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
            </iframe>
          <?php elseif ($current && $current['type'] === 'local' && !empty($current['fichier'])): ?>
            <video controls style="width: 100%; height: 100%;">
              <source src="../uploads/videos/<?= htmlspecialchars($current['fichier']) ?>" type="video/mp4">
              Votre navigateur ne supporte pas la lecture vidéo.
            </video>
          <?php else: ?>
            <div class="alert alert-warning">Aucune vidéo disponible.</div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Informations vidéo -->
      <h1 class="video-title"><?= htmlspecialchars($current['titre'] ?? 'Titre indisponible') ?></h1>
      
      <div class="video-meta">
        <span><?= number_format($current['vues'] ?? 0) ?> vues</span>
        <?php if (!empty($current['auteur'])): ?>
          <span> • <?= htmlspecialchars($current['auteur']) ?></span>
        <?php endif; ?>
        <?php if (!empty($current['created_at'])): ?>
          <span> • <?= date('d/m/Y', strtotime($current['created_at'])) ?></span>
        <?php endif; ?>
      </div>

      <div class="video-description">
        <p><?= nl2br(htmlspecialchars($current['description'] ?? 'Aucune description disponible.')) ?></p>
      </div>

      <!-- Section commentaires -->
      <div class="comment-section">
        <h3><span class="comment-count"><?= count($commentaires) ?></span> Commentaires</h3>

        <?php if (!empty($_SESSION['utilisateur_nom'])): ?>
          <!-- Formulaire de commentaire pour utilisateurs connectés -->
          <div class="comment-input-wrapper">
            <img src="<?= !empty($_SESSION['utilisateur_photo']) 
                ? '../uploads/users/' . htmlspecialchars($_SESSION['utilisateur_photo']) 
                : 'https://cdn-icons-png.flaticon.com/512/159/159833.png' ?>" 
                class="user-avatar" alt="User" />
            <div class="flex-grow-1">
              <form method="post">
                <input type="hidden" name="action" value="add_comment">
                <textarea name="commentaire" class="form-control" placeholder="Ajouter un commentaire..." rows="2" required></textarea>
                <button type="submit" class="btn btn-primary mt-2">Commenter</button>
              </form>
            </div>
          </div>
        <?php else: ?>
          <!-- Zone désactivée pour utilisateurs non connectés -->
          <div class="comment-input-wrapper">
            <img src="https://cdn-icons-png.flaticon.com/512/159/159833.png" class="user-avatar" alt="User" />
            <div class="flex-grow-1 position-relative">
              <textarea class="form-control" rows="2" disabled style="background-color: #f8f9fa; cursor: not-allowed;"></textarea>
              <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; width: 100%; pointer-events: none;">
                <p style="margin: 0; color: #495057; font-weight: 600;">
                  Pour commenter, 
                  <a href="?page=connexion" style="color: #0d6efd; text-decoration: underline; pointer-events: all;">connectez-vous</a> ou 
                  <a href="?page=inscription" style="color: #0d6efd; text-decoration: underline; pointer-events: all;">inscrivez-vous</a>
                </p>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <!-- Liste des commentaires -->
        <div id="commentsContainer">
          <?php if (!empty($commentaires)): ?>
            <?php foreach ($commentaires as $c): ?>
              <div class="comment-item">
                <div class="d-flex gap-3">
                  <img src="<?= !empty($c['user_photo']) 
                      ? '../uploads/users/' . htmlspecialchars($c['user_photo']) 
                      : 'https://cdn-icons-png.flaticon.com/512/159/159833.png' ?>" 
                      class="user-avatar" alt="User" />
                  
                  <div class="flex-grow-1">
                    <div class="comment-header">
                      <span class="comment-author"><?= htmlspecialchars($c['auteur']) ?></span>
                      <span class="comment-time"><?= date('d/m/Y H:i', strtotime($c['created_at'])) ?></span>
                      
                      <?php if (!empty($_SESSION['utilisateur_role']) && 
                                strtolower($_SESSION['utilisateur_role']) === 'admin'): ?>
                        <span class="badge bg-<?= $c['statut'] === 'approved' ? 'success' : 'warning' ?> ms-2">
                          <?= htmlspecialchars($c['statut']) ?>
                        </span>
                      <?php endif; ?>
                    </div>
                    <p class="comment-text"><?= nl2br(htmlspecialchars($c['texte'])) ?></p>

                    <?php if (!empty($_SESSION['utilisateur_role']) && 
                              strtolower($_SESSION['utilisateur_role']) === 'admin'): ?>
                      <div class="mt-2">
                        <a href="?page=webtv&video=<?= $current['id'] ?>&del=<?= $c['id'] ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Supprimer ce commentaire ?')">
                          Supprimer
                        </a>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="text-muted">Aucun commentaire pour le moment. Soyez le premier à commenter !</p>
          <?php endif; ?>
        </div>
      </div>
      
    </div> <!-- Fin primary-column -->

    <!-- Colonne secondaire: Vidéos suggérées -->
    <div class="secondary-column">
      <div class="sidebar-section">
        <h3>Vidéos suggérées</h3>

        <?php if (!empty($videos)): ?>
          <?php foreach ($videos as $video): ?>
            <a href="?page=webtv&video=<?= $video['id'] ?>" class="video-card text-decoration-none">
              <div class="video-card-thumb">
                <?php if ($video['type'] === 'youtube' && !empty($video['youtube_id'])): ?>
                  <img src="https://img.youtube.com/vi/<?= htmlspecialchars($video['youtube_id']) ?>/hqdefault.jpg" 
                       alt="<?= htmlspecialchars($video['titre']) ?>">
                <?php elseif (!empty($video['vignette'])): ?>
                  <img src="../uploads/vignettes/<?= htmlspecialchars($video['vignette']) ?>" 
                       alt="<?= htmlspecialchars($video['titre']) ?>">
                <?php else: ?>
                  <img src="https://via.placeholder.com/320x180?text=Vidéo" 
                       alt="<?= htmlspecialchars($video['titre']) ?>">
                <?php endif; ?>
                <button class="btn-play"></button>
              </div>
              <div class="video-card-content">
                <h5 class="video-card-title"><?= htmlspecialchars($video['titre']) ?></h5>
                <p class="video-card-meta">
                  <?= number_format($video['vues']) ?> vues
                  <?php if (!empty($video['auteur'])): ?>
                    • <?= htmlspecialchars($video['auteur']) ?>
                  <?php endif; ?>
                </p>
              </div>
            </a>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="text-muted">Aucune vidéo disponible.</p>
        <?php endif; ?>

      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php require __DIR__ . '/../parties/footer.php'; ?>

</body>
</html>