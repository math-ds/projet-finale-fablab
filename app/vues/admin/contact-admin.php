<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion des Messages de Contact - Admin FABLAB</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<div class="admin-container">
  <!-- ========== SIDEBAR ========== -->
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

  <!-- ========== MAIN CONTENT ========== -->
  <div class="main-content">
    <header class="admin-header">
      <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Rechercher un message..." onkeyup="searchMessages()">
      </div>
    </header>

    <section class="dashboard">
      <h1><i class="fas fa-envelope"></i> Gestion des Messages de Contact</h1>

      <!-- Alert -->
      <?php if (!empty($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message_type'] ?>">
          <i class="fas fa-<?= $_SESSION['message_type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
          <?= htmlspecialchars($_SESSION['message']) ?>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
      <?php endif; ?>

      <!-- Statistiques -->
      <div class="contact-stats-cards">
        <div class="contact-stat-card">
          <div class="contact-stat-card-header">
            <i class="fas fa-inbox contact-stat-card-icon"></i>
          </div>
          <div class="contact-stat-card-value"><?= $stats['total'] ?></div>
          <div class="contact-stat-card-label">Total Messages</div>
        </div>
        <div class="contact-stat-card">
          <div class="contact-stat-card-header">
            <i class="fas fa-envelope contact-stat-card-icon"></i>
          </div>
          <div class="contact-stat-card-value" style="color:#ff6b6b;"><?= $stats['non_lus'] ?></div>
          <div class="contact-stat-card-label">Non Lus</div>
        </div>
        <div class="contact-stat-card">
          <div class="contact-stat-card-header">
            <i class="fas fa-envelope-open contact-stat-card-icon"></i>
          </div>
          <div class="contact-stat-card-value" style="color:#ffa500;"><?= $stats['lus'] ?></div>
          <div class="contact-stat-card-label">Lus</div>
        </div>
        <div class="contact-stat-card">
          <div class="contact-stat-card-header">
            <i class="fas fa-check-circle contact-stat-card-icon"></i>
          </div>
          <div class="contact-stat-card-value" style="color:#4ade80;"><?= $stats['traites'] ?></div>
          <div class="contact-stat-card-label">Traités</div>
        </div>
      </div>

      <!-- Filtres -->
      <div class="contact-filter-buttons">
        <button class="btn btn-primary" onclick="filterMessages('all')">Tous</button>
        <button class="btn btn-secondary" onclick="filterMessages('non_lu')">Non lus</button>
        <button class="btn btn-secondary" onclick="filterMessages('lu')">Lus</button>
        <button class="btn btn-secondary" onclick="filterMessages('traite')">Traités</button>
      </div>

      <!-- Liste -->
      <div class="contact-messages-table">
        <?php if (empty($contacts)): ?>
          <div class="no-messages">
            <i class="fas fa-inbox"></i>
            <p>Aucun message trouvé.</p>
          </div>
        <?php else: ?>
          <table id="contactsTable" class="contact-table">
            <thead>
              <tr>
                <th>Contact</th>
                <th>Sujet</th>
                <th>Message</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($contacts as $msg): ?>
                <tr data-statut="<?= htmlspecialchars($msg['statut']) ?>" class="<?= $msg['statut'] === 'non_lu' ? 'unread' : '' ?>">
                  <td>
                    <div class="contact-message-nom"><?= htmlspecialchars($msg['nom']) ?></div>
                    <div class="contact-message-email"><?= htmlspecialchars($msg['email']) ?></div>
                  </td>
                  <td class="contact-message-sujet"><?= htmlspecialchars($msg['sujet']) ?></td>
                  <td class="contact-message-excerpt"><?= htmlspecialchars(substr($msg['message'], 0, 50)) ?>...</td>
                  <td><?= date('d/m/Y H:i', strtotime($msg['date_envoi'])) ?></td>
                  <td>
                    <span class="role-badge <?= match($msg['statut']) {
                      'lu' => 'role-editeur',
                      'traite' => 'role-admin',
                      default => 'role-utilisateur'
                    } ?>">
                      <?= ucfirst($msg['statut']) ?>
                    </span>
                  </td>
                  <td>
                    <div class="table-actions">
                      <button class="btn btn-primary btn-small" onclick='viewMessage(<?= json_encode($msg, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                        <i class="fas fa-eye"></i>
                      </button>
                      <form method="POST" action="?page=admin-contact" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="contact_id" value="<?= $msg['id'] ?>">
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

<!-- ========== MODAL ========== -->
<div id="messageModal" class="contact-modal">
  <div class="contact-modal-content">
    <div class="contact-modal-header">
      <h2><i class="fas fa-envelope-open"></i> Détails du Message</h2>
      <button class="contact-close-modal" onclick="closeModal()">&times;</button>
    </div>
    <div id="messageDetails"></div>
  </div>
</div>

<script>
function viewMessage(msg) {
  const modal = document.getElementById('messageModal');
  const content = document.getElementById('messageDetails');

  content.innerHTML = `
    <div class="form-group"><label><i class="fas fa-user"></i> Nom</label><input type="text" readonly value="${msg.nom}"></div>
    <div class="form-group"><label><i class="fas fa-envelope"></i> Email</label><input type="text" readonly value="${msg.email}"></div>
    <div class="form-group"><label><i class="fas fa-tag"></i> Sujet</label><input type="text" readonly value="${msg.sujet}"></div>
    <div class="form-group"><label><i class="fas fa-calendar"></i> Date d'envoi</label><input type="text" readonly value="${msg.date_envoi}"></div>
    <div class="form-group"><label><i class="fas fa-info-circle"></i> Statut</label><input type="text" readonly value="${msg.statut}"></div>
    <div class="form-group"><label><i class="fas fa-network-wired"></i> Adresse IP</label><input type="text" readonly value="${msg.ip_address ?? 'N/A'}"></div>
    <div class="form-group"><label><i class="fas fa-comment-dots"></i> Message</label><textarea readonly>${msg.message}</textarea></div>

    <div class="form-actions">
      <form method="POST" action="?page=admin-contact">
        <input type="hidden" name="contact_id" value="${msg.id}">
        <input type="hidden" name="nom" value="${msg.nom}">
        <button type="submit" name="action" value="lu" class="btn btn-primary"><i class="fas fa-envelope-open"></i> Marquer comme lu</button>
        <button type="submit" name="action" value="traite" class="btn btn-warning"><i class="fas fa-check"></i> Marquer comme traité</button>
        <button type="submit" name="action" value="delete" class="btn btn-danger"><i class="fas fa-trash"></i> Supprimer</button>
      </form>
    </div>
  `;
  modal.classList.add('active');
}

function closeModal() {
  document.getElementById('messageModal').classList.remove('active');
}

function searchMessages() {
  const val = document.getElementById('searchInput').value.toLowerCase();
  document.querySelectorAll('#contactsTable tbody tr').forEach(row => {
    row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
  });
}

function filterMessages(status) {
  const rows = document.querySelectorAll('#contactsTable tbody tr');
  rows.forEach(row => {
    const stat = row.dataset.statut;
    row.style.display = (status === 'all' || stat === status) ? '' : 'none';
  });
}
</script>

</body>
</html>
