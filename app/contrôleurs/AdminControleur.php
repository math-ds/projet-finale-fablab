<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../modèles/AdminModele.php';

class AdminControleur {

    private $modele;

    public function __construct() {
        $this->modele = new AdminModele();
    }

    public function index() {
    session_start();

    if (!isset($_SESSION['utilisateur_role']) || strtolower($_SESSION['utilisateur_role']) !== 'admin') {
        header('Location: ?page=login');
        exit;
    }

    // --- Connexion à la BDD et récupération des projets
    $projects = $this->modele->getAllProjects();
    $total_projects = count($projects);

    // --- On envoie les variables à la vue
    require __DIR__ . '/../vues/admin/projets-admin.php';

}

}
