<?php
// Vue : app/vues/admin/comments-admin.php
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
                <input type="text" id="searchInput" placeholder="Rechercher un commentaire..." onkeyup="searchCommentaires()">
            </div>
        </header>

<h1>Gestion des commentaires WebTV</h1>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Auteur</th>
        <th>Message</th>
        <th>Date</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($commentaires as $c): ?>
        <tr>
            <td><?= htmlspecialchars($c['id']) ?></td>
            <td><?= htmlspecialchars($c['auteur']) ?></td>
            <td><?= htmlspecialchars($c['texte']) ?></td>
            <td><?= $c['created_at'] ?></td>

            <td>
                <div class="table-actions">
                                        <button class="btn btn-warning btn-small" onclick='openModal("edit", <?= json_encode($c) ?>)'>
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-small" onclick="deleteComments(<?= $c['id'] ?>, '<?= addslashes($c['texte']) ?>')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
            </td>
        </tr>
    <?php endforeach; ?>

</table>




<!-- MODAL -->
<div id="commentsModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
        <h2 id="modalTitle">Nouveau Commentaire</h2>
        <button class="close-modal" onclick="closeModal()">&times;</button>
    </div>

    <form id="commentsForm" method="POST" action="?page=admin-comments">
        <input type="hidden" name="action" id="formAction" value="create">
        <input type="hidden" name="comments_id" id="commentsId">

        <div class="form-group">
            <label for="contenu">Contenu *</label>
            <textarea id="contenu" name="contenu" required></textarea>
        </div>

        <div class="form-group">
            <label for="auteur">Auteur *</label>
            <input type="text" id="auteur" name="auteur" readonly>
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
function openModal(action, comments = null) {
    const modal = document.getElementById('commentsModal');
    const title = document.getElementById('modalTitle');
    const form = document.getElementById('Form');

    if (action === 'create') {
        title.textContent = "Nouveau Commentaire";
        document.getElementById('formAction').value = 'create';
        form.reset();
    } else {
        title.textContent = "Modifier le commentaire";
        document.getElementById('formAction').value = 'update';
        document.getElementById('commentsId').value = comments.id;
        document.getElementById('contenu').value = comments.texte;
        document.getElementById('auteur').value = comments.auteur;
    }
    modal.classList.add('active');
}

function closeModal() { document.getElementById('commentsModal').classList.remove('active'); }

function deleteComments(id, titre) {
    if (confirm(`Supprimer le commentaire "${titre}" ?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '?page=admin-comments';
        form.innerHTML = `<input type="hidden" name="action" value="delete">
                          <input type="hidden" name="comments_id" value="${id}">`;
        document.body.appendChild(form);
        form.submit();
    }
}

function searchComments() {
    const val = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#commentsTable tbody tr');
    rows.forEach(r => {
        const titre = r.children[1].textContent.toLowerCase();
        const auteur = r.children[3].textContent.toLowerCase();
        r.style.display = (titre.includes(val) || auteur.includes(val)) ? '' : 'none';
    });
}
</script>
</body>
</html>
