<?php
require_once __DIR__ . '/../modèles/AdminContactModele.php';

class AdminContactControleur
{
    private AdminContactModele $modele;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->modele = new AdminContactModele();
    }

    public function handleRequest(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $id = (int)($_POST['contact_id'] ?? 0);

            try {
                if (in_array($action, ['lu', 'traite'])) {
                    $this->modele->updateStatut($id, $action);
                    $_SESSION['message'] = "Le message de \"{$_POST['nom']}\" a été marqué comme $action.";
                    $_SESSION['message_type'] = 'success';
                } elseif ($action === 'delete') {
                    $this->modele->delete($id);
                    $_SESSION['message'] = "Message supprimé avec succès.";
                    $_SESSION['message_type'] = 'success';
                }
            } catch (Throwable $e) {
                $_SESSION['message'] = "Erreur : " . $e->getMessage();
                $_SESSION['message_type'] = "danger";
            }

            header('Location: ?page=admin-contact');
            exit;
        }

        $this->index();
    }

    public function index(): void
    {
        $contacts = $this->modele->all();

        $total = count($contacts);
        $non_lus = count(array_filter($contacts, fn($c) => $c['statut'] === 'non_lu'));
        $lus = count(array_filter($contacts, fn($c) => $c['statut'] === 'lu'));
        $traites = count(array_filter($contacts, fn($c) => $c['statut'] === 'traite'));

        $stats = compact('total', 'non_lus', 'lus', 'traites');
        include __DIR__ . '/../vues/admin/contact-admin.php';
    }
}
