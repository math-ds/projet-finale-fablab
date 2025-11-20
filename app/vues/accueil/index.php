<?php
// Définir la base URL de ton projet
$GLOBALS['baseUrl'] = '/Fablabrobot/public/';

// Inclut le header commun
include(__DIR__ . '/../parties/header.php');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Accueil - AJC FABLAB</title>

  <!-- Feuilles de style -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
  />
  <link rel="stylesheet" href="<?= $GLOBALS['baseUrl'] ?>css/index.css" />
</head>

<body>
<main>

  <!-- 🏠 SECTION HERO -->
  <section class="hero-section">
    <div class="container">
      <div class="hero-content text-center">
        <h1 class="hero-title">Bienvenue chez AJC FABLAB</h1>
        <p class="hero-subtitle">
          Innovation technologique, développement web et solutions robotiques au service de vos projets.
        </p>
      </div>
    </div>
  </section>

  <!-- 🎠 SECTION CARROUSEL AMÉLIORÉ -->
  <section class="carousel-section">
    <div class="container">
      <div id="projectCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#projectCarousel" data-bs-slide-to="0" class="active"></button>
          <button type="button" data-bs-target="#projectCarousel" data-bs-slide-to="1"></button>
          <button type="button" data-bs-target="#projectCarousel" data-bs-slide-to="2"></button>
          <button type="button" data-bs-target="#projectCarousel" data-bs-slide-to="3"></button>
        </div>

        <div class="carousel-inner">
          <!-- Slide 1 - Image d'accueil sans bouton -->
          <div class="carousel-item active">
            <div class="carousel-image-wrapper">
              <img src="../public/images/accueil/carousel_index1.jpg" class="d-block w-100 carousel-img" alt="Innovation technologique" />
              <div class="carousel-overlay"></div>
            </div>
            <div class="carousel-caption-custom">
              <div class="caption-content">
                <h2 class="caption-title">Innovation Technologique</h2>
                <p class="caption-text">Des solutions modernes pour l'avenir</p>
              </div>
            </div>
          </div>

          <!-- Slide 2 - Projets -->
          <div class="carousel-item">
            <div class="carousel-image-wrapper">
              <img src="../public/images/accueil/carousel_index4.jpg" class="d-block w-100 carousel-img" alt="Nos projets" />
              <div class="carousel-overlay"></div>
            </div>
            <div class="carousel-caption-custom">
              <div class="caption-content">
                <h2 class="caption-title">Nos Projets</h2>
                <p class="caption-text">Découvrez nos réalisations techniques et innovantes</p>
                <a href="?page=projets" class="btn-carousel">
                  <i class="fas fa-rocket me-2"></i> Découvrir nos projets
                </a>
              </div>
            </div>
          </div>

          <!-- Slide 3 - Articles -->
          <div class="carousel-item">
            <div class="carousel-image-wrapper">
              <img src="../public/images/accueil/carousel_index3.png" class="d-block w-100 carousel-img" alt="Nos articles" />
              <div class="carousel-overlay"></div>
            </div>
            <div class="carousel-caption-custom">
              <div class="caption-content">
                <h2 class="caption-title">Nos Articles</h2>
                <p class="caption-text">Actualités, tutoriels et veille technologique</p>
                <a href="?page=articles" class="btn-carousel">
                  <i class="fas fa-newspaper me-2"></i> Lire nos articles
                </a>
              </div>
            </div>
          </div>

          <!-- Slide 4 - Vidéos -->
          <div class="carousel-item">
            <div class="carousel-image-wrapper">
              <img src="../public/images/accueil/carousel_index2.png" class="d-block w-100 carousel-img" alt="Nos vidéos" />
              <div class="carousel-overlay"></div>
            </div>
            <div class="carousel-caption-custom">
              <div class="caption-content">
                <h2 class="caption-title">Nos Vidéos</h2>
                <p class="caption-text">Démonstrations et présentations de nos projets</p>
                <a href="?page=contact" class="btn-carousel">
                  <i class="fas fa-video me-2"></i> Voir nos vidéos
                </a>
              </div>
            </div>
          </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#projectCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon"></span>
          <span class="visually-hidden">Précédent</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#projectCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon"></span>
          <span class="visually-hidden">Suivant</span>
        </button>
      </div>
    </div>
  </section>

  <!-- 💡 SECTION DOMAINES D'EXPERTISE -->
  <section class="featured-projects">
    <div class="container">
      <div class="section-header text-center">
        <h2>Nos Domaines d'Expertise</h2>
        <p class="section-subtitle">Découvrez nos principales activités et réalisations techniques</p>
      </div>

      <div class="row g-4">
        <div class="col-lg-4 col-md-6">
          <div class="project-card">
            <div class="card-image">
              <img src="../public/images/accueil/index_card1.png" alt="Plateforme WebTV" class="card-img" />
              <div class="card-overlay">
                <i class="fas fa-tv"></i>
              </div>
            </div>
            <div class="card-content">
              <h4 class="card-title">Plateforme WebTV</h4>
              <p class="card-description">Streaming et diffusion de contenu multimédia en temps réel</p>
              <a href="?page=webtv" class="btn-card">
                En savoir plus <i class="fas fa-arrow-right ms-2"></i>
              </a>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6">
          <div class="project-card">
            <div class="card-image">
              <img src="../public/images/accueil/index_card2.png" alt="Système Robotique" class="card-img" />
              <div class="card-overlay">
                <i class="fas fa-robot"></i>
              </div>
            </div>
            <div class="card-content">
              <h4 class="card-title">Nos Projets</h4>
              <p class="card-description">Développement de solutions robotiques intelligentes et autonomes</p>
              <a href="?page=projets" class="btn-card">
                En savoir plus <i class="fas fa-arrow-right ms-2"></i>
              </a>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6">
          <div class="project-card">
            <div class="card-image">
              <img src="../public/images/accueil/index_card3.png" alt="Analytics Fablab" class="card-img" />
              <div class="card-overlay">
                <i class="fas fa-chart-line"></i>
              </div>
            </div>
            <div class="card-content">
              <h4 class="card-title">Articles Fablab</h4>
              <p class="card-description">Ensemble des Articles de Fablab robotique & Web</p>
              <a href="?page=articles" class="btn-card">
                En savoir plus <i class="fas fa-arrow-right ms-2"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

</main>



<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const carousel = new bootstrap.Carousel(document.getElementById('projectCarousel'), {
    interval: 5000,
    wrap: true,
    touch: true,
    pause: 'hover'
  });
});
</script>
</body>
<?php include(__DIR__ . '/../parties/footer.php'); ?>
</html>