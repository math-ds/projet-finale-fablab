<?php
function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function author($c) {
  if (!empty($c['user_nom'])) return $c['user_nom'];
  if (!empty($c['auteur_nom'])) return $c['auteur_nom'];
  return 'Visiteur';
}
function badgeClass($s) {
  return $s==='approved'?'success':($s==='pending'?'warning':($s==='spam'?'danger':'secondary'));
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des Commentaires - Admin FABLAB</title>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../public/css/admin.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>

<body>
<div class="admin-container">
  <aside class="sidebar">
    <div>
      <div class="sidebar-logo">
        <a href="?page=admin">
          <img src="../public/images/ajc_logo_blanc.png" alt="AJC Logo">
        </a>
      </div>
      <?php include __DIR__ . '/../parties/sidebar.php'; ?>
    </div>
    <div class="sidebar-footer">
      <a href="?page=logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
    </div>
  </aside>

  <div class="main-content">
    <header class="admin-header">
      <form class="w-100 d-flex gap-2" method="get">
        <input type="hidden" name="page" value="admin-comments">
        <input class="form-control" name="q" placeholder="Rechercher (commentaire, vidéo, auteur)..." value="<?= e($_GET['q'] ?? '') ?>">
        <select class="form-select" name="statut" style="max-width:220px;">
          <?php $s = $_GET['statut'] ?? ''; ?>
          <option value="" <?= $s===''?'selected':'' ?>>Tous</option>
          <option value="pending" <?= $s==='pending'?'selected':'' ?>>pending</option>
          <option value="approved" <?= $s==='approved'?'selected':'' ?>>approved</option>
          <option value="spam" <?= $s==='spam'?'selected':'' ?>>spam</option>
        </select>
        <button class="btn btn-outline-primary"><i class="fas fa-filter"></i></button>
      </form>
    </header>

    <?php if (!empty($_SESSION['message'])): ?>
      <div class="alert alert-<?= e($_SESSION['message_type'] ?? 'info') ?>">
        <?= e($_SESSION['message']) ?>
      </div>
      <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>

    <h1>Commentaires WebTV</h1>

    <div class="table-responsive">
      <table class="table table-bordered align-middle" id="commentsTable">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Vidéo</th>
            <th>Auteur</th>
            <th>Statut</th>
            <th>Message</th>
            <th>Date</th>
            <th style="width:160px;">Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($commentaires as $c): ?>
          <tr>
            <td><?= (int)$c['id'] ?></td>
            <td>
              <div class="fw-semibold"><?= e($c['video_titre'] ?? 'Vidéo supprimée') ?></div>
              <small class="text-muted">video_id: <?= (int)$c['video_id'] ?></small>
            </td>
            <td><?= e(author($c)) ?></td>
            <td><span class="badge text-bg-<?= badgeClass($c['statut']) ?>"><?= e($c['statut']) ?></span></td>
            <td style="max-width:420px;">
              <div style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                <?= e($c['contenu']) ?>
              </div>
            </td>
            <td><?= e(date('d/m/Y H:i', strtotime($c['created_at'] ?? 'now'))) ?></td>
            <td>
              <div class="d-flex gap-1">
                <button class="btn btn-warning btn-sm" onclick='openModal(<?= json_encode($c, JSON_UNESCAPED_UNICODE) ?>)'>
                  <i class="fas fa-edit"></i>
                </button>

                <form method="post" action="?page=admin-comments" style="display:inline;">
                  <input type="hidden" name="action" value="approve">
                  <input type="hidden" name="comments_id" value="<?= (int)$c['id'] ?>">
                  <button class="btn btn-success btn-sm" title="Approuver"><i class="fas fa-check"></i></button>
                </form>

                <form method="post" action="?page=admin-comments" style="display:inline;">
                  <input type="hidden" name="action" value="spam">
                  <input type="hidden" name="comments_id" value="<?= (int)$c['id'] ?>">
                  <button class="btn btn-danger btn-sm" title="Spam"><i class="fas fa-ban"></i></button>
                </form>

                <form method="post" action="?page=admin-comments" style="display:inline;" onsubmit="return confirm('Supprimer ce commentaire ?');">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="comments_id" value="<?= (int)$c['id'] ?>">
                  <button class="btn btn-dark btn-sm" title="Supprimer"><i class="fas fa-trash"></i></button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>

  </div>
</div>

<!-- Modal edit -->
<div class="modal" id="commentsModal" style="display:none;">
  <div class="modal-content" style="max-width:720px;">
    <div class="modal-header">
      <h2 id="modalTitle">Modifier le commentaire</h2>
      <button class="close-modal" onclick="closeModal()">&times;</button>
    </div>

    <form method="post" action="?page=admin-comments">
      <input type="hidden" name="action" value="update">
      <input type="hidden" name="comments_id" id="comments_id">

      <div class="form-group">
        <label>Vidéo</label>
        <input class="form-control" id="video_titre" readonly>
      </div>

      <div class="form-group mt-2">
        <label>Auteur</label>
        <input class="form-control" id="auteur" readonly>
      </div>

      <div class="form-group mt-2">
        <label>Contenu</label>
        <textarea class="form-control" name="contenu" id="contenu" rows="5" required></textarea>
      </div>

      <div class="form-actions mt-3 d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-outline-secondary" onclick="closeModal()">Annuler</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Enregistrer</button>
      </div>
    </form>
  </div>
</div>

<script>
function openModal(c) {
  document.getElementById('comments_id').value = c.id;
  document.getElementById('video_titre').value = (c.video_titre || 'Vidéo supprimée') + ' (video_id=' + c.video_id + ')';
  document.getElementById('auteur').value = (c.user_nom || c.auteur_nom || 'Visiteur');
  document.getElementById('contenu').value = (c.contenu || '');
  document.getElementById('commentsModal').style.display = 'block';
}
function closeModal() {
  document.getElementById('commentsModal').style.display = 'none';
}
</script>

</body>
</html>
