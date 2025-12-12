<?php
// app/contrôleurs/AdminArticlesControleur.php
require_once __DIR__ . '/../modèles/AdminArticlesModele.php';

class AdminArticlesControleur
{
    private AdminArticlesModele $modele;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->modele = new AdminArticlesModele();
    }

    public function handleRequest(?string $action = null): void
    {
        $action = $action ?? ($_GET['action'] ?? null);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formAction = $_POST['action'] ?? null;

            try {
                if ($formAction === 'create') {
                    $this->modele->create([
                        'titre'     => trim($_POST['titre'] ?? ''),
                        'contenu'   => trim($_POST['contenu'] ?? ''),
                        'auteur'    => trim($_POST['auteur'] ?? ''),
                        'image_url' => trim($_POST['image_url'] ?? '')
                    ]);
                    $_SESSION['message'] = "Article créé avec succès.";
                    $_SESSION['message_type'] = 'success';
                } elseif ($formAction === 'update') {
                    $id = (int)($_POST['article_id'] ?? 0);
                    $this->modele->update($id, [
                        'titre'     => trim($_POST['titre'] ?? ''),
                        'contenu'   => trim($_POST['contenu'] ?? ''),
                        'auteur'    => trim($_POST['auteur'] ?? ''),
                        'image_url' => trim($_POST['image_url'] ?? '')
                    ]);
                    $_SESSION['message'] = "Article mis à jour.";
                    $_SESSION['message_type'] = 'success';
                } elseif ($formAction === 'delete') {
                    $id = (int)($_POST['article_id'] ?? 0);
                    $this->modele->delete($id);
                    $_SESSION['message'] = "Article supprimé.";
                    $_SESSION['message_type'] = 'success';
                }
            } catch (Throwable $e) {
                $_SESSION['message'] = "Erreur: " . $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }

            header('Location: ?page=admin-articles');
            exit;
        }

        $this->index();
    }

    public function index(): void
    {
        $articles = $this->modele->all();
        $total_articles = count($articles);
        include __DIR__ . '/../vues/admin/articles-admin.php';
    }
}
