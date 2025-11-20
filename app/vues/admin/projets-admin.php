<?php
// Vue : app/vues/admin/projets-admin.php

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des Projets - Admin FABLAB</title>

  <!-- Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

  <!-- Styles -->
  <link rel="stylesheet" href="css/admin.css">
</head>

<body>
<div class="admin-container">
  <!-- Sidebar -->
  <aside class="sidebar">
    <div>
      <div class="sidebar-logo">
        <a href="?page=admin">
          <img src="images/ajc_logo_blanc.png" alt="AJC Logo">
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
        <input type="text" id="searchInput" placeholder="Rechercher un projet..." onkeyup="searchProjects()">
      </div>
    </header>

    <section class="dashboard">
      <h1><i class="fas fa-project-diagram"></i> Gestion des Projets</h1>

      <!-- Message de succès / erreur -->
      <?php if (!empty($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message_type'] ?>">
          <i class="fas fa-<?= $_SESSION['message_type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
          <?= htmlspecialchars($_SESSION['message']) ?>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
      <?php endif; ?>

      <div class="action-buttons">
        <button class="btn btn-primary" onclick="openModal('create')">
          <i class="fas fa-plus"></i> Nouveau Projet
        </button>
        <span class="stats-badge"><i class="fas fa-folder"></i> <?= $total_projects ?? 0 ?> projet(s)</span>
      </div>

      <!-- Liste des projets -->
      <div class="projects-table">
        <?php if (empty($projects)): ?>
          <div style="padding: 40px; text-align: center; color: var(--text-muted);">
            <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.5;"></i>
            <p>Aucun projet pour le moment. Créez-en un pour commencer !</p>
          </div>
        <?php else: ?>
          <table id="projectsTable">
            <thead>
              <tr>
                <th>Image</th>
                <th>Titre</th>
                <th>Description</th>
                <th>Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($projects as $project): ?>
                <tr>
                  <td>
  <?php
    $imageName = trim($project['image_url']); // ex: image_projet2.png

    // Si l'image est une URL absolue (hébergée en ligne)
    if (preg_match('#^https?://#', $imageName)) {
        $src = $imageName;
    } else {
        // Force le chemin complet HTTP (pas relatif)
        $src = "http://localhost/Fablabrobot_MVC/Fablabrobot/public/images/projets/" . $imageName;
    }

    // Vérifie que le fichier existe sur le serveur (pour éviter les erreurs 404)
    $path = __DIR__ . "/../../../public/images/projets/" . $imageName;
    if (!empty($imageName) && file_exists($path)) {
        echo "<img src='" . htmlspecialchars($src, ENT_QUOTES) . "' alt='Image projet' class='project-image-thumb'>";
    } else {
        echo "<div class='no-image'><i class='fas fa-image'></i></div>";
    }
  ?>
</td>

                  <td class="project-title"><?= htmlspecialchars($project['title']); ?></td>
                  <td class="project-excerpt"><?= htmlspecialchars(substr($project['description'], 0, 80)); ?>...</td>
                  <td><?= date('d/m/Y', strtotime($project['created_at'])); ?></td>
                  <td>
                    <div class="table-actions">
                      <button class="btn btn-warning btn-small edit-btn"
                        data-id="<?= $project['id'] ?>"
                        data-title="<?= htmlspecialchars($project['title']) ?>"
                        data-description="<?= htmlspecialchars($project['description']) ?>"
                        data-image="<?= htmlspecialchars($project['image_url']) ?>">
                        <i class="fas fa-edit"></i>
                      </button>

                      <button class="btn btn-danger btn-small"
                        onclick="deleteProject(<?= $project['id'] ?>, '<?= addslashes($project['title']) ?>')">
                        <i class="fas fa-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </section>
  </div>
</div>

<!-- Modal -->
<div id="projectModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2 id="modalTitle">Nouveau Projet</h2>
      <button class="close-modal" onclick="closeModal()">&times;</button>
    </div>

    <form id="projectForm" method="POST" action="?page=admin-projets">
      <input type="hidden" name="action" id="formAction" value="create">
      <input type="hidden" name="project_id" id="projectId">

      <div class="form-group">
        <label for="title">Titre du projet *</label>
        <input type="text" id="title" name="title" required>
      </div>

      <div class="form-group">
        <label for="description">Description *</label>
        <textarea id="description" name="description" required></textarea>
      </div>

      <div class="form-group">
        <label for="image_url">Nom du fichier image (ex : projet1.jpg)</label>
        <input type="text" id="image_url" name="image_url">
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
function openModal(action) {
  const modal = document.getElementById('projectModal');
  const title = document.getElementById('modalTitle');
  const form = document.getElementById('projectForm');

  if (action === 'create') {
    title.textContent = 'Nouveau Projet';
    form.reset();
    document.getElementById('formAction').value = 'create';
  }
  modal.classList.add('active');
}

document.querySelectorAll('.edit-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const modal = document.getElementById('projectModal');
    const title = document.getElementById('modalTitle');
    const form = document.getElementById('projectForm');

    title.textContent = 'Modifier le Projet';
    document.getElementById('formAction').value = 'update';
    document.getElementById('projectId').value = btn.dataset.id;
    document.getElementById('title').value = btn.dataset.title;
    document.getElementById('description').value = btn.dataset.description;
    document.getElementById('image_url').value = btn.dataset.image;
    modal.classList.add('active');
  });
});

function closeModal() {
  document.getElementById('projectModal').classList.remove('active');
}

function deleteProject(id, title) {
  if (confirm(`Supprimer le projet "${title}" ?`)) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '?page=admin-projets';
    form.innerHTML = `
      <input type="hidden" name="action" value="delete">
      <input type="hidden" name="project_id" value="${id}">
    `;
    document.body.appendChild(form);
    form.submit();
  }
}

function searchProjects() {
  const val = document.getElementById('searchInput').value.toLowerCase();
  const rows = document.querySelectorAll('#projectsTable tbody tr');
  rows.forEach(row => {
    const title = row.querySelector('.project-title').textContent.toLowerCase();
    row.style.display = title.includes(val) ? '' : 'none';
  });
}
</script>
</body>
</html>
