<?php include __DIR__ . '/../parties/header.php'; ?>
<link rel="stylesheet" href="<?= $GLOBALS['baseUrl'] ?>css/profil.css">

<div class="container">
    <a href="?page=accueil" class="back-btn">
        <i class="fas fa-arrow-left"></i>
        <span>Retour à l'accueil</span>
    </a>

    <!-- Zone des messages -->
    <?php if (!empty($_SESSION['message'])): ?>
        <div class="alert <?= strpos($_SESSION['message'], 'succès') !== false || strpos($_SESSION['message'], 'mis') !== false ? 'alert-success' : 'alert-error' ?>">
            <i class="fas <?= strpos($_SESSION['message'], 'succès') !== false || strpos($_SESSION['message'], 'mis') !== false ? 'fa-check-circle' : 'fa-exclamation-triangle' ?>"></i>
            <span><?= htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?></span>
            <button class="close-alert" onclick="this.parentElement.style.display='none'">&times;</button>
        </div>
    <?php endif; ?>

    <div class="profile-wrapper">
        <!-- En-tête -->
        <div class="profile-header">
            <div class="header-background"></div>
            <div class="header-content">
                <div class="profile-avatar-large">
                    <?php
                    $path = __DIR__ . '/../../../public/uploads/profils/' . $user['photo'];
                    if (!empty($user['photo']) && file_exists($path)): ?>
                        <img id="photoPreviewHeader" src="<?= $GLOBALS['baseUrl'] ?>uploads/profils/<?= htmlspecialchars($user['photo']); ?>" alt="Photo de profil">
                    <?php else:
                        $initiales = strtoupper(substr($user['nom'], 0, 2));
                    ?>
                        <div class="avatar-placeholder" id="photoPreviewHeader"><?= htmlspecialchars($initiales) ?></div>
                    <?php endif; ?>

                    <!-- 👑 Badge admin -->
                    <?php if (isset($user['role']) && strtolower($user['role']) === 'admin'): ?>
                        <div class="avatar-badge" title="Administrateur">
                            <i class="fas fa-crown"></i>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="header-info">
                    <h1 class="profile-name"><?= htmlspecialchars($user['nom']); ?></h1>
                    <p class="profile-email">
                        <i class="fas fa-envelope"></i> <?= htmlspecialchars($user['email']); ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="profile-content">
            <div class="content-grid">

                <!-- Colonne principale -->
                <div class="main-column">
                    <!-- Informations personnelles -->
                    <div class="card card-form">
                        <div class="card-header">
                            <div class="card-icon"><i class="fas fa-user-edit"></i></div>
                            <h2>Informations personnelles</h2>
                        </div>
                        <form method="POST" action="?page=profil" class="form-ajax">
                            <input type="hidden" name="action" value="update-info">
                            <div class="form-group">
                                <label for="nom"><i class="fas fa-user"></i> Nom complet</label>
                                <input type="text" name="nom" id="nom" class="form-control" value="<?= htmlspecialchars($user['nom']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email"><i class="fas fa-envelope"></i> Adresse email</label>
                                <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($user['email']); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                        </form>
                    </div>

                    <!-- Mot de passe -->
                    <div class="card card-password">
                        <div class="card-header">
                            <div class="card-icon"><i class="fas fa-lock"></i></div>
                            <h2>Changer le mot de passe</h2>
                        </div>
                        <form method="POST" action="?page=profil" class="form-ajax">
                            <input type="hidden" name="action" value="update-password">
                            <div class="form-group">
                                <label for="old_password"><i class="fas fa-key"></i> Ancien mot de passe</label>
                                <input type="password" name="old_password" id="old_password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="new_password"><i class="fas fa-lock"></i> Nouveau mot de passe</label>
                                <input type="password" name="new_password" id="new_password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password"><i class="fas fa-check"></i> Confirmer</label>
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-secondary">
                                <i class="fas fa-sync-alt"></i> Mettre à jour
                            </button>
                        </form>
                    </div>

                    <!-- Photo de profil -->
                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon"><i class="fas fa-camera"></i></div>
                            <h2>Photo de profil</h2>
                        </div>
                        <div class="card-body">
                            <div class="photo-preview">
                                <?php if (!empty($user['photo']) && file_exists($path)): ?>
                                    <img id="photoPreview" src="<?= $GLOBALS['baseUrl'] ?>uploads/profils/<?= htmlspecialchars($user['photo']); ?>" alt="Photo de profil">
                                <?php else: ?>
                                    <div class="avatar-placeholder" id="photoPreview"><?= htmlspecialchars($initiales) ?></div>
                                <?php endif; ?>
                            </div>

                            <form method="POST" enctype="multipart/form-data" action="?page=profil" class="form-ajax">
                                <input type="file" name="photo" id="photo" accept="image/*" required>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload"></i> Mettre à jour
                                </button>
                            </form>

                            <?php if (!empty($user['photo'])): ?>
                                <form method="POST" action="?page=profil" onsubmit="return confirmerSuppression()" class="form-ajax">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash-alt"></i> Supprimer
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="sidebar-column">
                    <div class="card card-stats">
                        <div class="card-header">
                            <div class="card-icon"><i class="fas fa-chart-line"></i></div>
                            <h3>Statistiques</h3>
                        </div>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                                <div class="stat-info">
                                    <span class="stat-label">Membre depuis</span>
                                    <span class="stat-value"><?= !empty($user['date_creation']) ? date('d/m/Y', strtotime($user['date_creation'])) : 'Non renseignée'; ?></span>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                                <div class="stat-info">
                                    <span class="stat-label">Dernière connexion</span>
                                    <span class="stat-value"><?= date('d/m/Y'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-actions">
                        <div class="card-header">
                            <div class="card-icon"><i class="fas fa-bolt"></i></div>
                            <h3>Actions rapides</h3>
                        </div>
                        <div class="quick-actions">
                            <a href="?page=accueil" class="action-link">
                                <i class="fas fa-home"></i> Accueil
                            </a>
                            <a href="?page=logout" class="action-link action-logout">
                                <i class="fas fa-sign-out-alt"></i> Se déconnecter
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<script>
function confirmerSuppression() {
    return confirm("🗑️ Voulez-vous vraiment supprimer votre photo de profil ?");
}

// Disparition automatique des alertes
setTimeout(() => {
    const alert = document.querySelector('.alert');
    if (alert) {
        alert.style.opacity = '0';
        setTimeout(() => alert?.remove(), 600);
    }
}, 3000);

// ==========================
// LOADER PROPRE ET SÉCURISÉ
// ==========================
document.querySelectorAll('.form-ajax').forEach(form => {
    form.addEventListener('submit', (e) => {
        // Si une confirmation est refusée, on annule l'overlay
        const hasConfirm = form.getAttribute('onsubmit');
        if (hasConfirm && !confirm("🗑️ Voulez-vous vraiment effectuer cette action ?")) {
            e.preventDefault();
            return false;
        }

        // Création de l’overlay
        const overlay = document.createElement('div');
        overlay.className = 'loading-overlay';
        overlay.innerHTML = `
            <div class="spinner">
                <div class="spin-circle"></div>
                <p>Enregistrement...</p>
            </div>`;
        document.body.appendChild(overlay);

        // Sécurité : enlève le loader au bout de 10 secondes max
        setTimeout(() => {
            if (document.body.contains(overlay)) overlay.remove();
        }, 10000);

        // Supprime le loader dès que la page est rechargée
        window.addEventListener('beforeunload', () => {
            overlay.remove();
        });
    });
});


// Prévisualisation photo
const photoInput = document.getElementById('photo');
photoInput?.addEventListener('change', e => {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(ev) {
        const preview = document.getElementById('photoPreview');
        const header = document.getElementById('photoPreviewHeader');

        if (preview.tagName === 'IMG') preview.src = ev.target.result;
        else {
            const img = document.createElement('img');
            img.src = ev.target.result;
            preview.replaceWith(img);
            img.id = 'photoPreview';
        }

        if (header.tagName === 'IMG') header.src = ev.target.result;
        else {
            const img2 = document.createElement('img');
            img2.src = ev.target.result;
            header.replaceWith(img2);
            img2.id = 'photoPreviewHeader';
        }
    };
    reader.readAsDataURL(file);
});
</script>

<!-- Styles badge admin + loader -->
<style>
.avatar-badge {
    position: absolute;
    bottom: -5px;
    right: -5px;
    background: #FFD700;
    color: #111;
    border-radius: 50%;
    width: 26px;
    height: 26px;
    display: flex;
    justify-content: center;
    align-items: center;
    border: 2px solid white;
    font-size: 0.9rem;
}
.loading-overlay {
    position: fixed; inset: 0;
    background: rgba(0,0,0,0.4);
    display: flex; justify-content: center; align-items: center;
    z-index: 9999; backdrop-filter: blur(2px);
}
.spinner { background: white; padding: 25px 40px; border-radius: 10px; text-align: center; }
.spin-circle {
    width: 50px; height: 50px;
    border: 5px solid #ccc; border-top-color: #00afa7;
    border-radius: 50%; animation: spin 1s linear infinite;
    margin: 0 auto 10px;
}
.spinner p { margin: 0; color: #00afa7; font-weight: 600; }
@keyframes spin { from { transform: rotate(0); } to { transform: rotate(360deg); } }
</style>

<?php include __DIR__ . '/../parties/footer.php'; ?>
