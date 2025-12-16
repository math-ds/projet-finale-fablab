<?php require __DIR__ . '/../parties/header.php'; ?>

<link rel="stylesheet" href="../public/css/projets.css">

<body class="project-page">
<div class="particles" id="particles"></div>

<section class="hero-section"> 
    <h1 class="hero-title">Les Projets</h1>
    <p class="hero-subtitle">
        Découvrez les projets réalisés au sein du Fablab d’AJC Formation.
Cette section met en avant des réalisations développées autour de la robotique, de l’électronique et des technologies numériques.
    </p>
</section>
<section class="section-recherche">
  <div class="search-filter">
  <input type="text" id="searchInput" placeholder=" Rechercher un projet...">
  <select id="categoryFilter">
    <option value="all">Toutes les catégories</option>
    <option value="robotique">Robotique</option>
    <option value="drone">Drone</option>
    <option value="autres">Autres</option>
  </select>
</div>
</section>

<!-- === BOUTON CRÉER PROJET (ÉDITEUR) === -->
<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$role = $_SESSION['utilisateur_role'] ?? '';
?>

<?php if (in_array($role, ['Admin', 'Éditeur', 'Editeur'])): ?>
   <?php if (isset($_SESSION['utilisateur_role']) && in_array($_SESSION['utilisateur_role'], ['Admin', 'Éditeur', 'Editeur'])): ?>
  <div class="projet-action">
    <a href="?page=projet_creation" class="btn-create">
      <i class="fas fa-plus-circle"></i> Créer un projet
    </a>
  </div>
<?php endif; ?>
<?php endif; ?>


<!-- === SECTION PROJETS RÉCENTS === -->
<section class="featured-section">
    <h2 class="section-title">Projets récents</h2>

   <div class="projects-grid">
<?php foreach ($projects as $project): ?>

    <?php
    // 🧠 Catégorisation automatique intelligente
    $txt = strtolower($project['title'] . ' ' . $project['description'] . ' ' . ($project['technologies'] ?? ''));

    if (str_contains($txt, "drone") || str_contains($txt, "fpv") || str_contains($txt, "quad")) {
        $categorie = "drone";
    } elseif (str_contains($txt, "robot") || str_contains($txt, "moteur") || str_contains($txt, "arduino") || str_contains($txt, "servo")) {
        $categorie = "robotique";
    } else {
        $categorie = "autres";
    }
    ?>

    <div class="project-card"
         data-categorie="<?= $categorie ?>"
         onclick="openModal(<?= $project['id']; ?>)">

        <div class="project-image">
            <?php if (!empty($project['image_url'])): ?>
                <img src="../public/images/projets/<?= htmlspecialchars($project['image_url']) ?>"
                     alt="<?= htmlspecialchars($project['title']) ?>"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <i class="fas fa-code" style="display:none;"></i>
            <?php else: ?>
                <i class="fas fa-code"></i>
            <?php endif; ?>
        </div>

        <div class="project-content">
            <h3 class="project-title"><?= htmlspecialchars($project['title']) ?></h3>
            <p class="project-description"><?= htmlspecialchars($project['description']) ?></p>

            <?php if (!empty($project['technologies'])): ?>
            <div class="project-tags">
                <?php foreach (explode(',', $project['technologies']) as $tech): ?>
                    <span class="tag"><?= htmlspecialchars(trim($tech)) ?></span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

    </div>

<?php endforeach; ?>
</div>


</section>

<!-- === MODALES === -->
<?php foreach ($projects as $project): ?>
<div class="modal" id="modal-<?= $project['id']; ?>">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title"><?= htmlspecialchars($project['title']); ?></h2>
            <button class="close-btn" onclick="closeModal(<?= $project['id']; ?>)">&times;</button>
        </div>

        <div class="modal-body">
            <div class="modal-layout">
                <div class="modal-image-section">
                    <div class="modal-image">
                        <?php if (isset($project['image_url']) && trim($project['image_url']) !== ''): ?>
                            <img src="../public/images/projets/<?= htmlspecialchars($project['image_url']); ?>" 
                                 alt="<?= htmlspecialchars($project['title']); ?>"
                                 onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <i class="fas fa-code" style="display:none;"></i>
                        <?php else: ?>
                            <i class="fas fa-code"></i>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="modal-content-section">
                    <div class="modal-description">
                        <?= nl2br(htmlspecialchars($project['description'])); ?>
                    </div>

                    <?php if (isset($project['technologies']) && trim($project['technologies']) !== ''): ?>
                        <div class="modal-section-title">
                            <i class="fas fa-microchip"></i> Technologies utilisées
                        </div>
                        <div class="modal-tags">
                            <?php 
                            $techs = explode(',', $project['technologies']);
                            foreach ($techs as $tech): ?>
                                <span class="tag"><?= htmlspecialchars(trim($tech)); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="action-buttons">
                        <a href="?page=projet&id=<?= $project['id']; ?>" class="btn btn-primary">
                            <i class="fas fa-eye"></i> Voir plus de détails
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<script>
function createParticles() {
    const particles = document.getElementById('particles');
    const particleCount = 45;
    for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.top = Math.random() * 100 + '%';
        particle.style.animationDelay = Math.random() * 6 + 's';
        particle.style.animationDuration = (Math.random() * 3 + 3) + 's';
        particles.appendChild(particle);
    }
}

function openModal(id) {
    document.getElementById('modal-' + id).style.display = 'block';
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    document.getElementById('modal-' + id).style.display = 'none';
    document.body.style.overflow = 'auto';
}
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}
document.addEventListener('DOMContentLoaded', createParticles);
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        document.querySelectorAll('.modal').forEach(m => m.style.display = 'none');
        document.body.style.overflow = 'auto';
    }
});
document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.getElementById("searchInput");
  const categoryFilter = document.getElementById("categoryFilter");
  const cards = document.querySelectorAll(".project-card"); // adapte selon ta classe

  function filtrer() {
    const searchText = searchInput.value.toLowerCase();
    const selectedCategory = categoryFilter.value.toLowerCase();

    cards.forEach(card => {
      const title = card.querySelector("h3, .project-titre").textContent.toLowerCase();
      const category = card.dataset.categorie?.toLowerCase() || "autre";

      const matchTexte = title.includes(searchText);
      const matchCategorie = selectedCategory === "all" || category === selectedCategory;

      card.style.display = (matchTexte && matchCategorie) ? "block" : "none";
    });
  }

  searchInput.addEventListener("input", filtrer);
  categoryFilter.addEventListener("change", filtrer);
});
</script>

<?php require __DIR__ . '/../parties/footer.php'; ?>
