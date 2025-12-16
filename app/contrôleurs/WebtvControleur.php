<?php
require_once __DIR__ . '/../modèles/WebtvModele.php';
require_once __DIR__ . '/../modèles/CommentairesVideoModele.php';

class WebtvControleur
{
    private WebtvModele $videoModele;
    private CommentairesVideoModele $commentaireModele;

    public function __construct()
    {
        $this->videoModele = new WebtvModele();
        $this->commentaireModele = new CommentairesVideoModele();
    }

    public function index(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Récupération des filtres
        $q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
        $cat = isset($_GET['categorie']) ? trim((string)$_GET['categorie']) : '';

        // Récupération des vidéos et catégories
        $videos = $this->videoModele->all($q ?: null, $cat ?: null);
        $categories = $this->videoModele->categories();

        // Sélection de la vidéo courante
        $current = $this->selectCurrentVideo($videos);

        // Gestion du POST de commentaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add_comment') {
            $this->handleCommentSubmission($current);
            return;
        }

        // Suppression de commentaire (admin uniquement)
        if (isset($_GET['del']) && !empty($_SESSION['utilisateur_role']) && 
            strtolower($_SESSION['utilisateur_role']) === 'admin') {
            $this->handleCommentDeletion($_GET['del'], $current);
            return;
        }

        // Incrément des vues
        if ($current && !empty($current['id'])) {
            $this->videoModele->incrementViews((int)$current['id']);
        }

        // Récupération des commentaires
        $isAdmin = !empty($_SESSION['utilisateur_role']) && 
                   strtolower((string)$_SESSION['utilisateur_role']) === 'admin';
        
        $videoIdentifier = $current['youtube_id'] ?? $current['id'] ?? '';
        $commentaires = $videoIdentifier 
            ? $this->commentaireModele->listForVideo((string)$videoIdentifier, $isAdmin)
            : [];

        // Chargement de la vue
        require __DIR__ . '/../vues/webtv/webtv.php';
    }

    /**
     * Sélectionne la vidéo à afficher
     */
    private function selectCurrentVideo(array $videos): ?array
    {
        // Tentative par ID numérique
        if (isset($_GET['video']) && ctype_digit((string)$_GET['video'])) {
            $video = $this->videoModele->findById((int)$_GET['video']);
            if ($video) return $video;
        }

        // Tentative par youtube_id
        if (isset($_GET['video_id']) && trim((string)$_GET['video_id']) !== '') {
            $video = $this->videoModele->findByYoutubeId(trim((string)$_GET['video_id']));
            if ($video) return $video;
        }

        // Vidéo par défaut (première de la liste)
        return !empty($videos) ? $videos[0] : null;
    }

    /**
     * Gère la soumission d'un commentaire
     */
    private function handleCommentSubmission(?array $current): void
    {
        if (empty($_SESSION['utilisateur_id']) || !$current) {
            $_SESSION['message'] = "Vous devez être connecté pour commenter.";
            $_SESSION['message_type'] = "danger";
            $this->redirect($current);
            return;
        }

        $texte = trim((string)($_POST['commentaire'] ?? ''));
        
        if ($texte === '') {
            $_SESSION['message'] = "Le commentaire ne peut pas être vide.";
            $_SESSION['message_type'] = "warning";
            $this->redirect($current);
            return;
        }

        $videoIdentifier = $current['youtube_id'] ?? (string)$current['id'];
        $success = $this->commentaireModele->create(
            $videoIdentifier,
            (int)($current['id'] ?? 0),
            (int)$_SESSION['utilisateur_id'],
            (string)($_SESSION['utilisateur_nom'] ?? 'Utilisateur'),
            $texte
        );

        if ($success) {
            $_SESSION['message'] = "Commentaire envoyé (en attente de validation).";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Erreur lors de l'envoi du commentaire.";
            $_SESSION['message_type'] = "danger";
        }

        $this->redirect($current);
    }

    /**
     * Gère la suppression d'un commentaire
     */
    private function handleCommentDeletion($commentId, ?array $current): void
    {
        if (!ctype_digit((string)$commentId)) {
            $this->redirect($current);
            return;
        }

        $this->commentaireModele->delete((int)$commentId);
        $_SESSION['message'] = "Commentaire supprimé.";
        $_SESSION['message_type'] = "success";
        $this->redirect($current);
    }

    /**
     * Redirection après action
     */
    private function redirect(?array $current): void
    {
        $videoParam = $current 
            ? (isset($current['youtube_id']) ? 'video_id=' . urlencode($current['youtube_id']) : 'video=' . (int)$current['id'])
            : '';
        
        header("Location: ?page=webtv" . ($videoParam ? "&$videoParam" : ""));
        exit;
    }
}