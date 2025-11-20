<?php
// Vue : app/vues/admin/webtv-admin.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion WebTV - Admin FABLAB</title>

  <!-- Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

  <!-- Styles -->
  <link rel="stylesheet" href="../public/css/admin.css">
</head>

<body>
<div class="admin-container">
  <!-- Sidebar -->
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

  <!-- Main -->
  <div class="main-content">
    <header class="admin-header">
      <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Rechercher une vidéo..." onkeyup="searchVideos()">
      </div>
    </header>

    <section class="dashboard">
      <h1><i class="fas fa-video"></i> Gestion des vidéos WebTV</h1>

      <?php if (!empty($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message_type'] ?>">
          <i class="fas fa-<?= $_SESSION['message_type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
          <?= htmlspecialchars($_SESSION['message']) ?>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
      <?php endif; ?>

      <div class="action-buttons">
        <button class="btn btn-primary" onclick="openModal('create')">
          <i class="fas fa-plus"></i> Nouvelle Vidéo
        </button>
        <span class="stats-badge"><i class="fas fa-video"></i> <?= $total_videos ?? 0 ?> vidéo(s)</span>
      </div>

      <?php if (empty($videos)): ?>
        <div style="padding: 40px; text-align: center; color: var(--text-muted);">
          <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.5;"></i>
          <p>Aucune vidéo enregistrée pour le moment.</p>
        </div>
      <?php else: ?>
        <table id="videosTable">
          <thead>
            <tr>
              <th>Miniature</th>
              <th>Titre</th>
              <th>Catégorie</th>
              <th>Type</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($videos as $video): ?>
              <tr>
                <td>
                  <?php if (!empty($video['vignette'])): ?>
                    <img src="<?= htmlspecialchars($video['vignette']) ?>" alt="Miniature" class="article-image-thumb">
                  <?php else: ?>
                    <div class="no-image"><i class="fas fa-video"></i></div>
                  <?php endif; ?>
                </td>
                <td class="video-title"><?= htmlspecialchars($video['titre']) ?></td>
                <td><?= htmlspecialchars($video['categorie']) ?></td>
                <td>
                  <?= $video['type'] === 'youtube'
                    ? '<i class="fab fa-youtube" style="color:#ff4d4d"></i> YouTube'
                    : '<i class="fas fa-folder" style="color:#00afa7"></i> Locale' ?>
                </td>
                <td><?= date('d/m/Y', strtotime($video['created_at'])) ?></td>
                <td>
                  <div class="table-actions">
                    <button class="btn btn-warning btn-small" onclick='openModal("edit", <?= json_encode($video) ?>)'>
                      <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-danger btn-small" onclick="deleteVideo(<?= $video['id'] ?>, '<?= addslashes($video['titre']) ?>')">
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </section>
  </div>
</div>

<!-- Modal création / modification -->
<div id="videoModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2 id="modalTitle">Nouvelle Vidéo</h2>
      <button class="close-modal" onclick="closeModal()">&times;</button>
    </div>

    <form id="videoForm" method="POST" action="?page=admin-webtv">
      <input type="hidden" name="action" id="formAction" value="create">
      <input type="hidden" name="video_id" id="videoId">

      <div class="form-group">
        <label for="titre">Titre *</label>
        <input type="text" id="titre" name="titre" required>
      </div>

      <div class="form-group">
        <label for="description">Description *</label>
        <textarea id="description" name="description" required></textarea>
      </div>

      <div class="form-group">
        <label for="categorie">Catégorie *</label>
        <input type="text" id="categorie" name="categorie" required>
      </div>

      <div class="form-group">
        <label for="type">Type de vidéo *</label>
        <select id="type" name="type" onchange="toggleVideoInputs()" required>
          <option value="local">Locale (fichier)</option>
          <option value="youtube">YouTube</option>
        </select>
      </div>

      <div class="form-group" id="fichierGroup">
        <label for="fichier">Nom du fichier local</label>
        <input type="text" id="fichier" name="fichier" placeholder="ex : fablab_demo.mp4">
      </div>

      <div class="form-group" id="youtubeGroup" style="display:none;">
        <label for="youtube_id">ID YouTube</label>
        <input type="text" id="youtube_id" name="youtube_id" placeholder="ex : dQw4w9WgXcQ">
      </div>

      <div class="form-group">
        <label for="vignette">URL de la vignette</label>
        <input type="url" id="vignette" name="vignette" placeholder="https://...">
      </div>

      <div class="form-actions">
        <button type="button" class="btn btn-danger" onclick="closeModal()">
          <i class="fas fa-times"></i> Annuler
        </button>
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save"></i> Enregistrer
        </button>
      </div>
    </form>
  </div>
</div>

<script>
function toggleVideoInputs() {
  const type = document.getElementById('type').value;
  document.getElementById('fichierGroup').style.display = type === 'local' ? 'block' : 'none';
  document.getElementById('youtubeGroup').style.display = type === 'youtube' ? 'block' : 'none';
}

function openModal(action, video = null) {
  const modal = document.getElementById('videoModal');
  const title = document.getElementById('modalTitle');
  const form = document.getElementById('videoForm');

  if (action === 'create') {
    title.textContent = "Nouvelle Vidéo";
    document.getElementById('formAction').value = 'create';
    form.reset();
    toggleVideoInputs();
  } else {
    title.textContent = "Modifier la Vidéo";
    document.getElementById('formAction').value = 'update';
    document.getElementById('videoId').value = video.id;
    document.getElementById('titre').value = video.titre;
    document.getElementById('description').value = video.description;
    document.getElementById('categorie').value = video.categorie;
    document.getElementById('type').value = video.type;
    document.getElementById('fichier').value = video.fichier || '';
    document.getElementById('youtube_id').value = video.youtube_id || '';
    document.getElementById('vignette').value = video.vignette || '';
    toggleVideoInputs();
  }

  modal.classList.add('active');
}

function closeModal() {
  document.getElementById('videoModal').classList.remove('active');
}

function deleteVideo(id, titre) {
  if (confirm(`Supprimer la vidéo "${titre}" ?`)) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '?page=admin-webtv';
    form.innerHTML = `<input type="hidden" name="action" value="delete">
                      <input type="hidden" name="video_id" value="${id}">`;
    document.body.appendChild(form);
    form.submit();
  }
}

function searchVideos() {
  const input = document.getElementById('searchInput').value.toLowerCase();
  const rows = document.querySelectorAll('#videosTable tbody tr');
  rows.forEach(row => {
    const titre = row.querySelector('.video-title').textContent.toLowerCase();
    row.style.display = titre.includes(input) ? '' : 'none';
  });
}
</script>
</body>
</html>
