<?php require __DIR__ . '/../parties/header.php'; ?>
<?php


// Connexion à la base de données
$host = 'localhost';
$dbname = 'fablab';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupérer l'ID de la vidéo actuelle (par défaut la première)
$current_video_id = isset($_GET['video_id']) ? $_GET['video_id'] : 'x8Pc9hqTEO8';

// Si un nouveau commentaire est envoyé
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['commentaire']) && isset($_POST['video_id'])) {
    $texte = trim($_POST['commentaire']);
    $video_id = $_POST['video_id'];
    $auteur=$_SESSION['utilisateur_nom'];
    
    if ($texte !== '') {
        $stmt = $pdo->prepare("INSERT INTO commentaires_videos (video_id, auteur, texte) VALUES (?, ?, ?)");
        $stmt->execute([$video_id, htmlspecialchars($auteur), htmlspecialchars($texte)]);
        
        // Retourner les données du commentaire en JSON
      }
}


//supression du commentaire
if(isset($_SESSION['utilisateur_role']) && $_SESSION['utilisateur_role']=='Admin'){

    // Si un nouveau commentaire est envoyé
    if (isset($_GET['del']) && $_GET['del']>0) {
   
        $stmt = $pdo->prepare('DELETE FROM commentaires_videos  WHERE ID=?');
        $stmt->execute([$_GET['del']]);
        
        // Retourner les données du commentaire en JSON

          }
        
}




// Récupérer les commentaires pour la vidéo actuelle
$stmt = $pdo->prepare("SELECT * FROM commentaires_videos WHERE video_id = ? ORDER BY created_at DESC");
$stmt->execute([$current_video_id]);
$commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>WebTV - Lecture Vidéo</title>
  <link rel="stylesheet" href="../public/css/webtv.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  
</head>
<body>

<div class="youtube-container">
  <div class="youtube-layout">
    
    <!-- Primary Column: Video + Comments -->
    <div class="primary-column">
      
      <!-- Video Player -->
<div class="video-player-container">
  <div class="video-player-wrapper">
    <iframe 
      id="mainVideoPlayer"
      src="https://www.youtube.com/embed/<?= htmlspecialchars($current_video_id) ?>?start=2&autoplay=1&mute=1"
      title="Lecteur vidéo principal" 
      allowfullscreen
      allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
    </iframe>
  </div>
</div>

      <!-- Video Info -->
      <h1 class="video-title" id="currentVideoTitle">Introduction au Fablab</h1>
      
      <div class="video-description">
        <p id="currentVideoDescription">
          Présentation générale du FabLab et de ses projets innovants en robotique.
        </p>
      </div>

      <!-- Comments Section -->
      <div class="comment-section">
        <h3><span class="comment-count"><?= count($commentaires) + 1 ?></span> Commentaires</h3>

            <?php if(!empty($_SESSION['utilisateur_nom'])): ?>
  <!-- Utilisateur connecté peut commenter -->
  <div class="comment-input-wrapper">
    <img src="https://cdn-icons-png.flaticon.com/512/159/159833.png" class="user-avatar" alt="User" />
    <div class="flex-grow-1">
      <form method="post">
        <input type="hidden" name="video_id" value="<?php echo $current_video_id; ?>">
        <textarea name="commentaire" class="form-control" placeholder="Ajouter un commentaire..." rows="2"></textarea>
        <button type="submit" class="btn btn-primary mt-2">Commenter</button>
      </form>
    </div>
  </div>
<?php else: ?>
  <!-- Utilisateur non connecté - zone désactivée -->
  <div class="comment-input-wrapper">
    <img src="https://cdn-icons-png.flaticon.com/512/159/159833.png" class="user-avatar" alt="User" />
    <div class="flex-grow-1 position-relative">
      <textarea class="form-control" rows="2" disabled style="background-color: #f8f9fa; cursor: not-allowed;"></textarea>
      <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; width: 100%; pointer-events: none;">
        <p style="margin: 0; color: #495057; font-weight: 600;">
          Pour commenter, 
          <a href="?page=connexion" style="color: #0d6efd; text-decoration: underline; pointer-events: all;">connectez-vous</a> ou 
          <a href="?page=inscription" style="color: #0d6efd; text-decoration: underline; pointer-events: all;">inscrivez-vous</a>
        </p>
      </div>
    </div>
  </div>
<?php endif; ?>

        <div id="commentsContainer">
          <?php if (!empty($commentaires)): ?>
            <?php foreach ($commentaires as $c): ?>
              <div class="comment-item">
                <div class="d-flex gap-3">
                  <img src="https://cdn-icons-png.flaticon.com/512/159/159833.png" class="user-avatar" alt="User" />
                    <?php
                     if(isset($_SESSION['utilisateur_role']) && $_SESSION['utilisateur_role']=='Admin')
                         echo '<a href="?page=webtv&video_id='.$c['video_id'].'&del='.$c['id'].'" class="btn btn-primary">Supprimer</a>';
                     ?>

                  <div class="flex-grow-1">
                    <div class="comment-header">
                      <span class="comment-author"><?= htmlspecialchars($c['auteur']) ?></span>
                      <span class="comment-time"><?= date('d/m/Y H:i', strtotime($c['created_at'])) ?></span>
                    </div>
                    <p class="comment-text"><?= ($c['texte']) ?></p>

                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
          
          <!-- Commentaire de base de Natha D. -->
          <div class="comment-item">
            <div class="d-flex gap-3">
              <img src="https://cdn-icons-png.flaticon.com/512/159/159833.png" class="user-avatar" alt="User" />
              <div class="flex-grow-1">
                <div class="comment-header">
                  <span class="comment-author">Natha D.</span>
                  <span class="comment-time">il y a 2h</span>
                </div>
                <p class="comment-text">Super vidéo ! Les explications sont très claires et le projet est vraiment intéressant. J'aimerais bien voir plus de contenus comme celui-ci.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      
</div> <!-- Fin de primary-column -->

    <!-- Secondary Column: Sidebar -->
    <div class="secondary-column">
      <div class="sidebar-section">
        <h3>Vidéos suggérées</h3>

        <!-- Video 1 -->
        <div class="video-card" onclick="changeVideo('x8Pc9hqTEO8', 2, 'Introduction au Fablab', 'Présentation générale du FabLab et de ses projets innovants en robotique.')">
          <div class="video-card-thumb">
            <img src="https://img.youtube.com/vi/x8Pc9hqTEO8/hqdefault.jpg" alt="Introduction au Fablab">
            <button class="btn-play"></button>
          </div>
          <div class="video-card-content">
            <h5 class="video-card-title">Introduction au Fablab</h5>
          </div>
        </div>

        <!-- Video 2 -->
        <div class="video-card" onclick="changeVideo('mSyo25hKnfo', 35, 'Robotique & Impression 3D', 'Projet étudiant de robotique utilisant des pièces imprimées en 3D.')">
          <div class="video-card-thumb">
            <img src="https://img.youtube.com/vi/mSyo25hKnfo/hqdefault.jpg" alt="Robotique & Impression 3D">
            <button class="btn-play"></button>
          </div>
          <div class="video-card-content">
            <h5 class="video-card-title">Robotique & Impression 3D</h5>
          </div>
        </div>

        <!-- Video 3 -->
        <div class="video-card" onclick="changeVideo('Ikownb7GSjE', 58, 'Impression 3D avancée', 'Techniques d\'impression 3D complexes utilisées dans le FabLab.')">
          <div class="video-card-thumb">
            <img src="https://img.youtube.com/vi/Ikownb7GSjE/hqdefault.jpg" alt="Impression 3D avancée">
            <button class="btn-play"></button>
          </div>
          <div class="video-card-content">
            <h5 class="video-card-title">Impression 3D avancée</h5>
          </div>
        </div>

        <!-- Video 4 -->
        <div class="video-card" onclick="changeVideo('msY6LTbBc2s', 0, 'Atelier Robotique', 'Démonstration robotique et présentation des projets étudiants associés.')">
          <div class="video-card-thumb">
            <img src="https://img.youtube.com/vi/msY6LTbBc2s/hqdefault.jpg" alt="Atelier Robotique">
            <button class="btn-play"></button>
          </div>
          <div class="video-card-content">
            <h5 class="video-card-title">Atelier Robotique</h5>
          </div>
        </div>

        <!-- Video 5 -->
        <div class="video-card" onclick="changeVideo('N8R8eWBI8Qk', 0, 'Découpe laser', 'Formation à l\'utilisation de la découpe laser pour créer des prototypes.')">
          <div class="video-card-thumb">
            <img src="https://img.youtube.com/vi/N8R8eWBI8Qk/hqdefault.jpg" alt="Découpe laser">
            <button class="btn-play"></button>
          </div>
          <div class="video-card-content">
            <h5 class="video-card-title">Découpe laser</h5>
          </div>
        </div>

        <!-- Video 6 -->
        <div class="video-card" onclick="changeVideo('qJ6B_0Xv06E', 0, 'Projets collaboratifs', 'Présentation de projets collaboratifs menés par la communauté du FabLab.')">
          <div class="video-card-thumb">
            <img src="https://img.youtube.com/vi/qJ6B_0Xv06E/hqdefault.jpg" alt="Projets collaboratifs">
            <button class="btn-play"></button>
          </div>
          <div class="video-card-content">
            <h5 class="video-card-title">Projets collaboratifs</h5>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Variable globale pour l'ID de la vidéo actuelle
let currentVideoId = '<?= $current_video_id ?>';

function changeVideo(videoId, startTime, title, description) {
  window.location="?page=webtv&video_id="+videoId;

 }




</script>
<?php
require __DIR__ . '/../parties/footer.php';
?>

</body>
</html>