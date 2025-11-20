<?php
// app/contrôleurs/AdminProjetsControleur.php
require_once __DIR__ . '/../modèles/AdminProjetsModele.php';

class AdminProjetsControleur
{
    private AdminProjetsModele $modele;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->modele = new AdminProjetsModele();
    }

    public function handleRequest(?string $action = null): void
    {
        $action = $action ?? ($_GET['action'] ?? null);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formAction = $_POST['action'] ?? null;

            try {
                // ---- CREATE ----
                if ($formAction === 'create') {
                    $this->modele->create([
                        'title'                => trim($_POST['title'] ?? ''), 
                        'description'          => trim($_POST['description'] ?? ''),
                        'description_detailed' => trim($_POST['description_detailed'] ?? ''),
                        'technologies'         => trim($_POST['technologies'] ?? ''),
                        'image_url'            => trim($_POST['image_url'] ?? ''),
                        'features'             => trim($_POST['features'] ?? ''),
                        'challenges'           => trim($_POST['challenges'] ?? '')
                    ]);

                    $_SESSION['message'] = "Projet créé.";
                    $_SESSION['message_type'] = 'success';
                }

                // ---- UPDATE ----
                elseif ($formAction === 'update') {
                    $id = (int)($_POST['project_id'] ?? 0);

                    $this->modele->update($id, [
                        'title'                => trim($_POST['title'] ?? ''), 
                        'description'          => trim($_POST['description'] ?? ''),
                        'description_detailed' => trim($_POST['description_detailed'] ?? ''),
                        'technologies'         => trim($_POST['technologies'] ?? ''),
                        'image_url'            => trim($_POST['image_url'] ?? ''),
                        'features'             => trim($_POST['features'] ?? ''),
                        'challenges'           => trim($_POST['challenges'] ?? '')
                    ]);

                    $_SESSION['message'] = "Projet mis à jour.";
                    $_SESSION['message_type'] = 'success';
                }

                // ---- DELETE ----
                elseif ($formAction === 'delete') {
                    $id = (int)($_POST['project_id'] ?? 0);
                    $this->modele->delete($id);

                    $_SESSION['message'] = "Projet supprimé.";
                    $_SESSION['message_type'] = 'success';
                }

            } catch (Throwable $e) {
                $_SESSION['message'] = "Erreur: " . $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }

            header('Location: ?page=admin-projets');
            exit;
        }

        $this->index();
    }

    public function index(): void
    {
        $projects = $this->modele->all();
        $total_projects = count($projects);
        include __DIR__ . '/../vues/admin/projets-admin.php';
    }
}
