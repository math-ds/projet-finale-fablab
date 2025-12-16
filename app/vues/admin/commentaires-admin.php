<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Vérification admin
if (empty($_SESSION['utilisateur_role']) || strtolower($_SESSION['utilisateur_role']) !== 'admin') {
    header('Location: ?page=connexion');
    exit;
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
            <a href="?page=logout" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Déconnexion
            </a>
        </div>
    </aside>

    <div class="main-content">
        <header class="admin-header">
            <div class="d-flex justify-content-between align-items-center w-100">
                <h1>Gestion des commentaires WebTV</h1>
                <div class="search-bar">
                    <input type="text" id="searchInput" placeholder="Rechercher..." onkeyup="searchCommentaires()">
                </div>
            </div>
        </header>

        <?php if (!empty($_SESSION['message'])): ?>
        <div class="alert alert-<?= htmlspecialchars($_SESSION['message_type'] ?? 'info') ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php 
            unset($_SESSION['message'], $_SESSION['message_type']);
        endif; 
        ?>

        <!-- Statistiques -->
        <?php if (!empty($stats)): ?>
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total</h5>
                        <p class="h2"><?= $stats['total'] ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-warning">En attente</h5>
                        <p class="h2"><?= $stats['pending'] ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-success">Approuvés</h5>
                        <p class="h2"><?= $stats['approved'] ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-danger">Spam</h5>
                        <p class="h2"><?= $stats['spam'] ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Filtres -->
        <div class="mb-3">
            <div class="btn-group" role="group">
                <a href="?page=admin-comments" class="btn btn-outline-primary <?= empty($_GET['statut']) ? 'active' : '' ?>">
                    Tous
                </a>
                <a href="?page=admin-comments&statut=pending" class="btn btn-outline-warning <?= ($_GET['statut'] ?? '') === 'pending' ? 'active' : '' ?>">
                    En attente
                </a>
                <a href="?page=admin-comments&statut=approved" class="btn btn-outline-success <?= ($_GET['statut'] ?? '') === 'approved' ? 'active' : '' ?>">
                    Approuvés
                </a>
                <a href="?page=admin-comments&statut=spam" class="btn btn-outline-danger <?= ($_GET['statut'] ?? '') === 'spam' ? 'active' : '' ?>">
                    Spam
                </a>
            </div>
        </div>

        <!-- Table des commentaires -->
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="commentsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Vidéo</th>
                        <th>Auteur</th>
                        <th>Commentaire</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($commentaires)): ?>
                        <?php foreach ($commentaires as $c): ?>
                            <tr>
                                <td><?= htmlspecialchars($c['id']) ?></td>
                                <td>
                                    <?php if (!empty($c['video_titre'])): ?>
                                        <a href="?page=webtv&video=<?= $c['video_db_id'] ?>" target="_blank">
                                            <?= htmlspecialchars(substr($c['video_titre'], 0, 30)) ?>
                                            <?= strlen($c['video_titre']) > 30 ? '...' : '' ?>
                                        </a>
                                    <?php else: ?>
                                        <em>Vidéo supprimée</em>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($c['auteur']) ?>
                                    <?php if (!empty($c['user_nom'])): ?>
                                        <br><small class="text-muted"><?= htmlspecialchars($c['user_nom']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars(substr($c['texte'], 0, 50)) ?>
                                    <?= strlen($c['texte']) > 50 ? '...' : '' ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= 
                                        $c['statut'] === 'approved' ? 'success' : 
                                        ($c['statut'] === 'pending' ? 'warning' : 'danger') 
                                    ?>">
                                        <?= htmlspecialchars($c['statut']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?= date('d/m/Y H:i', strtotime($c['created_at'])) ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <?php if ($c['statut'] !== 'approved'): ?>
                                            <form method="post" class="d-inline">
                                                <input type="hidden" name="action" value="approve">
                                                <input type="hidden" name="comments_id" value="<?= $c['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-success" title="Approuver">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <button class="btn btn-sm btn-warning" onclick='openModal(<?= json_encode($c, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)' title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        
                                        <button class="btn btn-sm btn-danger" onclick="deleteComment(<?= $c['id'] ?>, '<?= addslashes(substr($c['texte'], 0, 30)) ?>')" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">Aucun commentaire trouvé.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal d'édition -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier le commentaire</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="comments_id" id="editId">
                    
                    <div class="mb-3">
                        <label for="editAuteur" class="form-label">Auteur</label>
                        <input type="text" class="form-control" id="editAuteur" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editContenu" class="form-label">Commentaire</label>
                        <textarea class="form-control" id="editContenu" name="contenu" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
let editModalInstance;

document.addEventListener('DOMContentLoaded', function() {
    editModalInstance = new bootstrap.Modal(document.getElementById('editModal'));
});

function openModal(comment) {
    document.getElementById('editId').value = comment.id;
    document.getElementById('editAuteur').value = comment.auteur;
    document.getElementById('editContenu').value = comment.texte;
    editModalInstance.show();
}

function deleteComment(id, titre) {
    if (confirm(`Supprimer le commentaire "${titre}..." ?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '?page=admin-comments';
        form.innerHTML = `
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="comments_id" value="${id}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

function searchCommentaires() {
    const val = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#commentsTable tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(val) ? '' : 'none';
    });
}
</script>
</body>
</html>