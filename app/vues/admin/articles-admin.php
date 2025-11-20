<?php
// Vue : app/vues/admin/articles-admin.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Articles - Admin FABLAB</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/admin.css">
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
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Rechercher un article..." onkeyup="searchArticles()">
            </div>
        </header>

        <section class="dashboard">
            <h1><i class="fas fa-newspaper"></i> Gestion des Articles</h1>

            <?php if (!empty($_SESSION['message'])): ?>
                <div class="alert alert-<?= $_SESSION['message_type'] ?>">
                    <i class="fas fa-<?= $_SESSION['message_type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                    <?= htmlspecialchars($_SESSION['message']) ?>
                </div>
                <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
            <?php endif; ?>

            <div class="action-buttons">
                <button class="btn btn-primary" onclick="openModal('create')">
                    <i class="fas fa-plus"></i> Nouvel Article
                </button>
                <span class="stats-badge">
                    <i class="fas fa-file-alt"></i> <?= $total_articles ?> article(s)
                </span>
            </div>

            <?php if (empty($articles)): ?>
                <div style="padding: 40px; text-align: center; color: var(--text-muted);">
                    <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.5;"></i>
                    <p>Aucun article pour le moment. Créez-en un pour commencer !</p>
                </div>
            <?php else: ?>
                <table id="articlesTable">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Titre</th>
                            <th>Extrait</th>
                            <th>Auteur</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($articles as $article): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($article['image_url'])): ?>
                                        <img src="<?= htmlspecialchars($article['image_url']) ?>" alt="Image" class="article-image-thumb">
                                    <?php else: ?>
                                        <div class="no-image"><i class="fas fa-image"></i></div>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($article['titre']) ?></td>
                                <td><?= htmlspecialchars(substr($article['contenu'], 0, 80)) ?>...</td>
                                <td><?= htmlspecialchars($article['auteur']) ?></td>
                                <td><?= date('d/m/Y', strtotime($article['created_at'])) ?></td>
                                <td>
                                    <div class="table-actions">
                                        <button class="btn btn-warning btn-small" onclick='openModal("edit", <?= json_encode($article) ?>)'>
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-small" onclick="deleteArticle(<?= $article['id'] ?>, '<?= addslashes($article['titre']) ?>')">
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

<!-- MODAL -->
<div id="articleModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
        <h2 id="modalTitle">Nouvel Article</h2>
        <button class="close-modal" onclick="closeModal()">&times;</button>
    </div>

    <form id="articleForm" method="POST" action="?page=admin-articles">
        <input type="hidden" name="action" id="formAction" value="create">
        <input type="hidden" name="article_id" id="articleId">

        <div class="form-group">
            <label for="titre">Titre *</label>
            <input type="text" id="titre" name="titre" required>
        </div>

        <div class="form-group">
            <label for="contenu">Contenu *</label>
            <textarea id="contenu" name="contenu" required></textarea>
        </div>

        <div class="form-group">
            <label for="auteur">Auteur *</label>
            <input type="text" id="auteur" name="auteur" required>
        </div>

        <div class="form-group">
            <label for="image_url">URL de l'image</label>
            <input type="url" id="image_url" name="image_url" placeholder="https://...">
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
function openModal(action, article = null) {
    const modal = document.getElementById('articleModal');
    const title = document.getElementById('modalTitle');
    const form = document.getElementById('articleForm');

    if (action === 'create') {
        title.textContent = "Nouvel Article";
        document.getElementById('formAction').value = 'create';
        form.reset();
    } else {
        title.textContent = "Modifier l'article";
        document.getElementById('formAction').value = 'update';
        document.getElementById('articleId').value = article.id;
        document.getElementById('titre').value = article.titre;
        document.getElementById('contenu').value = article.contenu;
        document.getElementById('auteur').value = article.auteur;
        document.getElementById('image_url').value = article.image_url || '';
    }
    modal.classList.add('active');
}

function closeModal() { document.getElementById('articleModal').classList.remove('active'); }

function deleteArticle(id, titre) {
    if (confirm(`Supprimer l'article "${titre}" ?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '?page=admin-articles';
        form.innerHTML = `<input type="hidden" name="action" value="delete">
                          <input type="hidden" name="article_id" value="${id}">`;
        document.body.appendChild(form);
        form.submit();
    }
}

function searchArticles() {
    const val = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#articlesTable tbody tr');
    rows.forEach(r => {
        const titre = r.children[1].textContent.toLowerCase();
        const auteur = r.children[3].textContent.toLowerCase();
        r.style.display = (titre.includes(val) || auteur.includes(val)) ? '' : 'none';
    });
}
</script>
</body>
</html>
