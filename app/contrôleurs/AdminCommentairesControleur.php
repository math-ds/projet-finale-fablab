<?php
require_once __DIR__ . '/../modèles/AdminCommentairesModele.php';

class AdminCommentairesControleur
{
    private AdminCommentairesModele $modele;

    public function __construct()
    {
        $this->modele = new AdminCommentairesModele();
    }

    /**
     * Gère les requêtes du contrôleur
     */
    public function handleRequest(?string $action = null): void
    {
        // Gestion des actions POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePostActions();
            return;
        }

        // Gestion des actions GET
        $action = $action ?? ($_GET['action'] ?? null);
        
        switch ($action) {
            case 'approve':
                $this->approuver();
                break;
            case 'reject':
                $this->rejeter();
                break;
            default:
                $this->index();
                break;
        }
    }

    /**
     * Affiche la liste des commentaires
     */
    public function index(): void
    {
        $statut = $_GET['statut'] ?? null;
        $q = $_GET['q'] ?? null;
        
        $commentaires = $this->modele->all($statut, $q);
        $stats = $this->modele->getStats();
        
        require __DIR__ . '/../vues/admin/commentaires-admin.php';
    }

    /**
     * Gère les actions POST
     */
    private function handlePostActions(): void
    {
        $action = $_POST['action'] ?? '';
        
        switch ($action) {
            case 'update':
                $this->modifier($_POST);
                break;
            case 'delete':
                $this->supprimer((int)($_POST['comments_id'] ?? 0));
                break;
            case 'approve':
                $this->changerStatut((int)($_POST['comments_id'] ?? 0), 'approved');
                break;
            case 'reject':
                $this->changerStatut((int)($_POST['comments_id'] ?? 0), 'spam');
                break;
            default:
                $this->redirect();
        }
    }

    /**
     * Supprime un commentaire
     */
    public function supprimer(int $id): void
    {
        if ($id > 0) {
            $this->modele->delete($id);
            $_SESSION['message'] = "Commentaire supprimé avec succès.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "ID de commentaire invalide.";
            $_SESSION['message_type'] = "danger";
        }
        
        $this->redirect();
    }

    /**
     * Modifie un commentaire
     */
    public function modifier(array $data): void
    {
        $id = (int)($data['comments_id'] ?? 0);
        $texte = trim($data['contenu'] ?? '');
        
        if ($id <= 0 || $texte === '') {
            $_SESSION['message'] = "Données invalides.";
            $_SESSION['message_type'] = "danger";
            $this->redirect();
            return;
        }
        
        if ($this->modele->update($id, $texte)) {
            $_SESSION['message'] = "Commentaire modifié avec succès.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Erreur lors de la modification.";
            $_SESSION['message_type'] = "danger";
        }
        
        $this->redirect();
    }

    /**
     * Change le statut d'un commentaire
     */
    private function changerStatut(int $id, string $statut): void
    {
        if ($id <= 0) {
            $_SESSION['message'] = "ID de commentaire invalide.";
            $_SESSION['message_type'] = "danger";
            $this->redirect();
            return;
        }
        
        try {
            if ($this->modele->updateStatut($id, $statut)) {
                $_SESSION['message'] = "Statut mis à jour avec succès.";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Erreur lors de la mise à jour.";
                $_SESSION['message_type'] = "danger";
            }
        } catch (InvalidArgumentException $e) {
            $_SESSION['message'] = $e->getMessage();
            $_SESSION['message_type'] = "danger";
        }
        
        $this->redirect();
    }

    /**
     * Approuve un commentaire (GET)
     */
    private function approuver(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $this->changerStatut($id, 'approved');
    }

    /**
     * Rejette un commentaire (GET)
     */
    private function rejeter(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $this->changerStatut($id, 'spam');
    }

    /**
     * Redirection vers la page de gestion
     */
    private function redirect(): void
    {
        header('Location: ?page=admin-comments');
        exit;
    }
}