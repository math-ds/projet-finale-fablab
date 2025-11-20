<?php
// ===============================
// VUE : app/vues/admin/dashboard-admin.php
// ===============================
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - AJC Admin</title>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="../public/css/admin.css">
</head>
<body>

<div class="admin-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div>
            <div class="sidebar-logo">
                <a href="?page=accueil" class="sidebar-logo">
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

    <!-- Contenu principal -->
    <div class="main-content">
        <section class="dashboard">
            <h1>Bienvenue sur le tableau de bord</h1>
            <div class="cards">
                <div class="card">
                    <h3>Articles</h3>
                    <p>Gérez vos articles et actualités.</p>
                </div>
                <div class="card">
                    <h3>Forum</h3>
                    <p>Modérez les discussions.</p>
                </div>
                <div class="card">
                    <h3>Projets</h3>
                    <p>Suivez l’avancement des projets.</p>
                </div>
                <div class="card">
                    <h3>WebTV</h3>
                    <p>Ajoutez ou gérez vos vidéos.</p>
                </div>
            </div>
        </section>
    </div>
</div>

</body>
</html>
