<?php
// app/contrôleurs/AdminWebtvControleur.php
require_once __DIR__ . '/../modèles/AdminWebtvModele.php';

class AdminWebtvControleur
{
    private AdminWebtvModele $modele;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->modele = new AdminWebtvModele();
    }

    public function handleRequest(?string $action = null): void
    {
        $action = $action ?? ($_GET['action'] ?? null);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formAction = $_POST['action'] ?? null;

            try {
                if ($formAction === 'create') { 
                    $this->modele->create([
                        'titre'       => trim($_POST['titre'] ?? ''),
                        'description' => trim($_POST['description'] ?? ''),
                        'categorie'   => trim($_POST['categorie'] ?? ''),
                        'type'        => trim($_POST['type'] ?? 'local'),
                        'fichier'     => trim($_POST['fichier'] ?? ''),
                        'youtube_id'  => trim($_POST['youtube_id'] ?? ''),
                        'vignette'    => trim($_POST['vignette'] ?? '')
                    ]);
                    $_SESSION['message'] = "Vidéo ajoutée.";
                    $_SESSION['message_type'] = 'success';
                } elseif ($formAction === 'update') {
                    $id = (int)($_POST['video_id'] ?? 0);
                    $this->modele->update($id, [
                        'titre'       => trim($_POST['titre'] ?? ''),
                        'description' => trim($_POST['description'] ?? ''),
                        'categorie'   => trim($_POST['categorie'] ?? ''),
                        'type'        => trim($_POST['type'] ?? 'local'),
                        'fichier'     => trim($_POST['fichier'] ?? ''),
                        'youtube_id'  => trim($_POST['youtube_id'] ?? ''),
                        'vignette'    => trim($_POST['vignette'] ?? '')
                    ]);
                    $_SESSION['message'] = "Vidéo mise à jour.";
                    $_SESSION['message_type'] = 'success';
                } elseif ($formAction === 'delete') {
                    $id = (int)($_POST['video_id'] ?? 0);
                    $this->modele->delete($id);
                    $_SESSION['message'] = "Vidéo supprimée.";
                    $_SESSION['message_type'] = 'success';
                }
            } catch (Throwable $e) {
                $_SESSION['message'] = "Erreur: " . $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }

            header('Location: ?page=admin-webtv');
            exit;
        }

        $this->index();
    }

    public function index(): void
    {
        $videos = $this->modele->all();
        $total_videos = count($videos);
        include __DIR__ . '/../vues/admin/webtv-admin.php';
    }
}
