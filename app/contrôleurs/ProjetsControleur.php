<?php
require_once __DIR__ . '/../modèles/Projet.php';

class ProjetsControleur {

    public function index() {
        $modele = new Projet();
        $projects = $modele->getTousLesProjets(); // Récupère tous les projets

        // On inclut la vue correspondante
        require __DIR__ . '/../vues/projets/index.php';
    }
    public function creation(): void
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    $role = $_SESSION['utilisateur_role'] ?? '';

    if (!in_array($role, ['Admin', 'Éditeur', 'Editeur'])) {
        header('Location: ?page=projets');
        exit;
    }

    include __DIR__ . '/../vues/projets/projet_creation.php';
}


public function enregistrer(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ?page=projets');
        exit;
    }

    require_once __DIR__ . '/../modèles/AdminProjetsModele.php';
    $modele = new AdminProjetsModele();

    $title = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $auteur = $_SESSION['utilisateur_nom'] ?? 'Inconnu';
    $image_url = null;

    if (!empty($_FILES['image']['name'])) {
        $uploadDir = __DIR__ . '/../../public/images/projets/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $filename = time() . '_' . basename($_FILES['image']['name']);
        $target = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image_url = 'images/projets/' . $filename;
        }
    }

    if ($title && $description) {
        $modele->create([
            'titre'       => $title,
            'description' => $description,
            'auteur'      => $auteur,
            'image_url'   => $image_url
        ]);
        $_SESSION['message'] = "✅ Projet ajouté avec succès !";
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = "❌ Champs obligatoires manquants.";
        $_SESSION['message_type'] = 'danger';
    }

    header('Location: ?page=projets');
    exit;
}

}

