<?php
require_once __DIR__ . '/../modèles/AdminCommentairesModele.php';

class AdminCommentairesControleur
{
    private $modele;

    public function __construct() {
        $this->modele = new AdminCommentairesModele();
    }

    public function handleRequest(?string $action = null): void
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if ($_POST['action']=='update')
            {
                $this->modifier($_POST);
            }
            if ($_POST['action']=='delete')
            {
                $this->supprimer($_POST['comments_id']);
            }

        }

        $action = $action ?? ($_GET['action'] ?? null);
        $this->index();
    }

    public function index()
    {
        $commentaires = $this->modele->all();
        require __DIR__ . '/../vues/admin/commentaires-admin.php';
    }


    public function supprimer($id)
    {
        $this->modele->delete($id);
         header('Location: ?page=admin-comments');
        exit;
    }

    public function modifier($c)
    {
            $this->modele->update($c['comments_id'], $c['contenu']);
            header('Location: ?page=admin-comments');
            exit;

        // récupérer les infos du commentaire si tu veux une page "éditer"
    }
}
