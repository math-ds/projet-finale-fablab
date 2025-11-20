<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion des Utilisateurs - Admin FABLAB</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<div class="admin-container">
  <!-- Sidebar -->
  <aside class="sidebar">
    <div>
      <div class="sidebar-logo">
        <a href="?page=admin"><img src="images/ajc_logo_blanc.png" alt="Logo AJC"></a>
      </div>
      <?php include __DIR__ . '/../parties/sidebar.php'; ?>
    </div>
    <div class="sidebar-footer">
      <a href="?page=logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
    </div>
  </aside>

  <!-- Main content -->
  <div class="main-content">
    <header class="admin-header">
      <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Rechercher un utilisateur..." onkeyup="searchUsers()">
      </div>
    </header>

    <section class="dashboard">
      <h1><i class="fas fa-users"></i> Gestion des Utilisateurs</h1>

      <!-- Alert -->
      <?php if (!empty($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message_type'] ?>">
          <i class="fas fa-<?= $_SESSION['message_type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
          <?= htmlspecialchars($_SESSION['message']) ?>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
      <?php endif; ?>

      <!-- Statistiques -->
      <div class="stats-cards">
        <div class="stat-card">
          <div class="stat-card-header">
            <i class="fas fa-users stat-card-icon"></i>
          </div>
          <div class="stat-card-value"><?= $stats['total_users'] ?></div>
          <div class="stat-card-label">Total Utilisateurs</div>
        </div>
        <div class="stat-card">
          <div class="stat-card-header">
            <i class="fas fa-user-shield stat-card-icon"></i>
          </div>
          <div class="stat-card-value" style="color:#ff6b6b;"><?= $stats['admins'] ?></div>
          <div class="stat-card-label">Administrateurs</div>
        </div>
        <div class="stat-card">
          <div class="stat-card-header">
            <i class="fas fa-user-edit stat-card-icon"></i>
          </div>
          <div class="stat-card-value" style="color:#ffa500;"><?= $stats['editeurs'] ?></div>
          <div class="stat-card-label">Éditeurs</div>
        </div>
        <div class="stat-card">
          <div class="stat-card-header">
            <i class="fas fa-user stat-card-icon"></i>
          </div>
          <div class="stat-card-value" style="color:#4ade80;"><?= $stats['utilisateurs'] ?></div>
          <div class="stat-card-label">Utilisateurs</div>
        </div>
      </div>

      <!-- Boutons filtres -->
      <div class="action-buttons">
        <button class="btn btn-primary" onclick="openAddModal()"><i class="fas fa-user-plus"></i> Nouvel Utilisateur</button>
        <button class="btn btn-secondary" onclick="filterUsers('all')">Tous</button>
        <button class="btn btn-secondary" onclick="filterUsers('admin')">Admins</button>
        <button class="btn btn-secondary" onclick="filterUsers('editeur')">Éditeurs</button>
        <button class="btn btn-secondary" onclick="filterUsers('utilisateur')">Utilisateurs</button>
      </div>

      <!-- Liste -->
      <div class="users-table">
        <?php if (empty($users)): ?>
          <div class="no-users">
            <i class="fas fa-user-slash"></i>
            <p>Aucun utilisateur trouvé.</p>
          </div>
        <?php else: ?>
          <table id="usersTable">
            <thead>
              <tr>
                <th>Utilisateur</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Date d'inscription</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($users as $u): ?>
                <tr data-role="<?= strtolower($u['role']) ?>">
                  <td class="user-info">
                    <div class="user-avatar"><?= strtoupper(substr($u['nom'], 0, 2)) ?></div>
                    <div class="user-details">
                      <span class="user-name"><?= htmlspecialchars($u['nom']) ?></span>
                      <span class="user-email"><?= htmlspecialchars($u['email']) ?></span>
                    </div>
                  </td>
                  <td><?= htmlspecialchars($u['email']) ?></td>
                  <td>
                    <span class="role-badge <?= match(strtolower($u['role'])) {
                      'admin' => 'role-admin',
                      'editeur', 'éditeur' => 'role-editeur',
                      default => 'role-utilisateur'
                    } ?>">
                      <?= ucfirst($u['role']) ?>
                    </span>
                  </td>
                  <td><?= date('d/m/Y', strtotime($u['date_creation'])) ?></td>
                  <td>
                    <div class="table-actions">
                      <button class="btn btn-primary btn-small" onclick='viewUser(<?= json_encode($u, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'><i class="fas fa-eye"></i></button>
                      <button class="btn btn-warning btn-small" onclick='editUser(<?= json_encode($u, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'><i class="fas fa-edit"></i></button>
                      <form method="POST" action="?page=admin-utilisateurs" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                        <button type="submit" class="btn btn-danger btn-small"><i class="fas fa-trash"></i></button>
                      </form>
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

<!-- ========== MODALE UTILISATEUR ========== -->
<div id="userModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2 id="modalTitle"><i class="fas fa-user"></i> Modifier l'Utilisateur</h2>
      <button class="close-modal" onclick="closeModal()">&times;</button>
    </div>
    <form method="POST" action="?page=admin-utilisateurs">
      <input type="hidden" name="action" value="update">
      <input type="hidden" name="user_id" id="userId">

      <div class="form-group">
        <label>Nom complet *</label>
        <input type="text" name="nom" id="userName" required>
      </div>

      <div class="form-group">
        <label>Email *</label>
        <input type="email" name="email" id="userEmail" required>
      </div>

      <div class="form-group">
        <label>Rôle *</label>
        <select name="role" id="userRole" required>
          <option value="Admin">Admin</option>
          <option value="Éditeur">Éditeur</option>
          <option value="Utilisateur">Utilisateur</option>
        </select>
      </div>

      <div class="form-group">
        <label>Mot de passe *</label>
        <input type="password" name="mot_de_passe" id="userPassword">
        <div class="password-info">
          <i class="fas fa-info-circle"></i> Laissez vide pour conserver le mot de passe actuel.
        </div>
      </div>

      <div class="form-actions">
        <button type="button" class="btn btn-danger" onclick="closeModal()">✖ Annuler</button>
        <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
      </div>
    </form>
  </div>
</div>

<script>
function viewUser(u) {
  editUser(u);
  document.getElementById('modalTitle').innerHTML = "<i class='fas fa-user'></i> Détails de l'Utilisateur";
  document.getElementById('userPassword').parentElement.style.display = 'none';
}

function editUser(u) {
  const modal = document.getElementById('userModal');
  document.getElementById('userId').value = u.id;
  document.getElementById('userName').value = u.nom;
  document.getElementById('userEmail').value = u.email;
  document.getElementById('userRole').value = u.role;
  document.getElementById('userPassword').value = '';
  document.getElementById('modalTitle').innerHTML = "<i class='fas fa-user-edit'></i> Modifier l'Utilisateur";
  document.getElementById('userPassword').parentElement.style.display = 'block';
  modal.classList.add('active');
}

function openAddModal() {
  const modal = document.getElementById('userModal');
  document.getElementById('modalTitle').innerHTML = "<i class='fas fa-user-plus'></i> Nouvel Utilisateur";
  document.getElementById('userId').value = '';
  document.getElementById('userName').value = '';
  document.getElementById('userEmail').value = '';
  document.getElementById('userRole').value = 'Utilisateur';
  document.getElementById('userPassword').value = '';
  modal.classList.add('active');
}

function closeModal() {
  document.getElementById('userModal').classList.remove('active');
}

function searchUsers() {
  const val = document.getElementById('searchInput').value.toLowerCase();
  document.querySelectorAll('#usersTable tbody tr').forEach(row => {
    row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
  });
}

function filterUsers(role) {
  const rows = document.querySelectorAll('#usersTable tbody tr');
  rows.forEach(row => {
    const userRole = row.dataset.role;
    row.style.display = (role === 'all' || userRole === role) ? '' : 'none';
  });
}
</script>

</body>
</html>
