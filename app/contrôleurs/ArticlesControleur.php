<?php
// ======================================================
// Contrôleur : ArticlesControleur
// Gère l'affichage des articles côté public
// ======================================================

require_once __DIR__ . '/../modèles/ArticleModele.php';

class ArticlesControleur {

    private $modele;

    public function __construct() {
        $this->modele = new ArticleModele();
    }

    /**
     * Affiche la liste de tous les articles
     */
    public function index() {
        $articles = $this->modele->getAllArticles();
        require __DIR__ . '/../vues/articles/articles.php';
    }

    /**
     * Affiche le détail d’un article spécifique
     */
    public function detail($id) {
        $article = $this->modele->getArticleById($id);

        if ($article) {
            require __DIR__ . '/../vues/articles/article_detail.php';
        } else {
            echo "<h2>Article introuvable.</h2>";
        }
    }
    public function enregistrer(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ?page=articles');
        exit;
    }

    require_once __DIR__ . '/../modèles/AdminArticlesModele.php';
    $modele = new AdminArticlesModele();

    $titre   = trim($_POST['titre'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');
    $auteur  = $_SESSION['utilisateur_nom'] ?? 'Inconnu';
    $image_url = null;

    // ✅ Gestion du fichier image (optionnel)
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = __DIR__ . '/../../public/images/articles/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $filename = time() . '_' . basename($_FILES['image']['name']);
        $target = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image_url = 'images/articles/' . $filename;
        }
    }

    // ✅ Vérifie que les champs obligatoires sont remplis
    if ($titre && $contenu) {
        $modele->create([
            'titre'     => $titre,
            'contenu'   => $contenu,
            'auteur'    => $auteur,
            'image_url' => $image_url
        ]);

        $_SESSION['message'] = "✅ Article publié avec succès !";
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = "❌ Merci de remplir tous les champs obligatoires.";
        $_SESSION['message_type'] = 'danger';
    }

    header('Location: ?page=articles');
    exit;
}
    public function creation(): void
    {
        // Vérifie le rôle de l'utilisateur
        $role = $_SESSION['utilisateur_role'] ?? 'Visiteur';
        if (!in_array($role, ['Éditeur', 'Editeur', 'Admin'])) {
            header('Location: ?page=articles');
            exit;
        }

        require __DIR__ . '/../vues/articles/article_creation.php';
    }
}