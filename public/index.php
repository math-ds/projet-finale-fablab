<?php
// ======================================================
// Fichier : public/index.php
// Routeur principal (MVC)
// ======================================================
session_start();


$GLOBALS['baseUrl'] = '/Fablabrobot/public/';

function load_controller($relativePath) {
    $full = __DIR__ . '/../' . $relativePath;
    if (!file_exists($full)) {
        throw new Exception("Contrôleur introuvable : $relativePath");
    }
    require_once $full;
}

function new_if_exists(array $classNames) {
    foreach ($classNames as $cn) {
        if (class_exists($cn)) return new $cn();
    }
    return null;
}

$page = $_GET['page'] ?? 'accueil';

try {
    switch ($page) {

        // ------------------ Public ------------------
        case 'accueil':
            load_controller('app/contrôleurs/AccueilControleur.php');
            (new AccueilControleur())->index();
            break;

        case 'articles':
            load_controller('app/contrôleurs/ArticlesControleur.php');
            $ctrl = new_if_exists(['ArticlesControleur','ArticleControleur']);
            if (!$ctrl) throw new Exception("Classe ArticlesControleur (ou ArticleControleur) introuvable. Vérifie le nom exact dans app/contrôleurs/ArticlesControleur.php");
            $ctrl->index();
            break;

        case 'article-detail':
            load_controller('app/contrôleurs/ArticlesControleur.php');
            $ctrl = new_if_exists(['ArticlesControleur','ArticleControleur']);
            if (!$ctrl) throw new Exception("Classe ArticlesControleur (ou ArticleControleur) introuvable. Vérifie le nom exact.");
            if (!isset($_GET['id'])) { echo "<h2>Article introuvable.</h2>"; break; }
            $ctrl->detail($_GET['id']);
            break;
                    case 'article_creation':
            load_controller('app/contrôleurs/ArticlesControleur.php');
            $ctrl = new_if_exists(['ArticlesControleur','ArticleControleur']);
            if (!$ctrl) throw new Exception("Classe ArticlesControleur (ou ArticleControleur) introuvable.");
            $ctrl->creation();
            break;
                    case 'article_enregistrer':
            load_controller('app/contrôleurs/ArticlesControleur.php');
            $ctrl = new_if_exists(['ArticlesControleur','ArticleControleur']);
            if (!$ctrl) throw new Exception("Classe ArticlesControleur (ou ArticleControleur) introuvable.");
            $ctrl->enregistrer();
            break;



        case 'projets':
            load_controller('app/contrôleurs/ProjetsControleur.php');
            (new ProjetsControleur())->index();
            break;

        case 'projet':
            load_controller('app/contrôleurs/ProjetControleur.php');
            if (!isset($_GET['id'])) { echo "<h2>Projet introuvable.</h2>"; break; }
            (new ProjetControleur())->detail($_GET['id']);
            break;
case 'projet_creation':
    load_controller('app/contrôleurs/ProjetsControleur.php');
    (new ProjetsControleur())->creation();
    break;

case 'projet_enregistrer':
    load_controller('app/contrôleurs/ProjetsControleur.php');
    (new ProjetsControleur())->enregistrer();
    break;

        case 'webtv':
            load_controller('app/contrôleurs/WebtvControleur.php');
            (new WebtvControleur())->index();
            break;

        case 'contact':
            load_controller('app/contrôleurs/ContactControleur.php');
            (new ContactControleur())->index();
            break;
            
   case 'profil':
            load_controller('app/contrôleurs/ProfilControleur.php');
            $ctrl = new ProfilControleur();
            // ✅ Traite tout POST (upload OU delete) dans la même méthode
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $ctrl->updatePhoto();
            } else {
                $ctrl->index();
            }
            break;
        // ------------------ Auth ------------------
        case 'login':
            load_controller('app/contrôleurs/AuthControleur.php');
            (new AuthControleur())->login();
            break;

        case 'inscription':
            load_controller('app/contrôleurs/AuthControleur.php');
            (new AuthControleur())->inscription();
            break;

        case 'logout':
            load_controller('app/contrôleurs/AuthControleur.php');
            (new AuthControleur())->deconnexion();
            break;

        case 'mdp-oublie':
            load_controller('app/contrôleurs/AuthControleur.php');
            (new AuthControleur())->mdpOublie();
            break;

        // ------------------ Admin ------------------
        case 'admin':
            load_controller('app/contrôleurs/AdminDashboardControleur.php');
            (new AdminDashboardControleur())->index();
            break;

        case 'admin-projets':
            load_controller('app/contrôleurs/AdminProjetsControleur.php');
            (new AdminProjetsControleur())->handleRequest($_POST['action'] ?? null);
            break;

        case 'admin-articles':
            load_controller('app/contrôleurs/AdminArticlesControleur.php');
            (new AdminArticlesControleur())->handleRequest($_POST['action'] ?? null);
            break;

        case 'admin-webtv':
            load_controller('app/contrôleurs/AdminWebtvControleur.php');
            (new AdminWebtvControleur())->handleRequest($_POST['action'] ?? null);
            break;

        case 'admin-comments':
            load_controller('app/contrôleurs/AdminCommentairesControleur.php');
            (new AdminCommentairesControleur())->handleRequest($_POST['action'] ?? null);
            break;

        // 👥 Gestion des utilisateurs (Admin)
        case 'admin-utilisateurs':
            load_controller('app/contrôleurs/AdminUtilisateursControleur.php');
            (new AdminUtilisateursControleur())->handleRequest($_POST['action'] ?? null);
            break;

        // 👥 Alias optionnel pour accéder à la même page via une autre route
        case 'utilisateurs-admin':
            load_controller('app/contrôleurs/AdminUtilisateursControleur.php');
            (new AdminUtilisateursControleur())->handleRequest($_POST['action'] ?? null);
            break;

        case 'admin-contact':
            load_controller('app/contrôleurs/AdminContactControleur.php');
            (new AdminContactControleur())->handleRequest($_POST['action'] ?? null);
            break;

        // ------------------ Default ------------------
        default:
            load_controller('app/contrôleurs/AccueilControleur.php');
            (new AccueilControleur())->index();
            break;
    }

} catch (Throwable $e) {
    http_response_code(500);
    echo "<pre style='padding:16px;background:#111;color:#f55;border:1px solid #400;border-radius:8px;'>
Erreur fatale dans le routeur :
" . htmlspecialchars($e->getMessage()) . "
</pre>";
}
