<?php
require_once __DIR__ . '/../modèles/AuthModele.php';
require_once __DIR__ . '/../config/database.php';



class AuthControleur {
    private $modele;

    public function __construct() {
        // ✅ On récupère la connexion à la BDD via Database.php
        $db = (new Database())->getConnection();
        $this->modele = new AuthModele($db);
       
    }

    /**
     * Page de connexion
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $motdepasse = $_POST['password'] ?? '';

            if (empty($email) || empty($motdepasse)) {
                $error = "Tous les champs sont requis.";
                require __DIR__ . '/../vues/utilisateurs/login.php';
                return;
            }

            $utilisateur = $this->modele->verifierConnexion($email, $motdepasse);

            if ($utilisateur) {
                $_SESSION['utilisateur_id'] = $utilisateur['id'];
                $_SESSION['utilisateur_nom'] = $utilisateur['nom'];
                $_SESSION['utilisateur_email'] = $utilisateur['email'];
                $_SESSION['utilisateur_role'] = $utilisateur['role'];

                header("Location: ?page=accueil");
                exit;
            } else {
                $error = "Email ou mot de passe incorrect.";
            }
        }

        require __DIR__ . '/../vues/utilisateurs/login.php';
    }

    /**
     * Page d'inscription
     */
    public function inscription() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $motdepasse = $_POST['password'] ?? '';
            $confirm = $_POST['confirm-password'] ?? '';

            if (empty($nom) || empty($email) || empty($motdepasse) || empty($confirm)) {
                $error = "Tous les champs sont requis.";
            } elseif ($motdepasse !== $confirm) {
                $error = "Les mots de passe ne correspondent pas.";
            } elseif ($this->modele->emailExiste($email)) {
                $error = "Cet email est déjà utilisé.";
            } else {
                $hash = password_hash($motdepasse, PASSWORD_DEFAULT);
                $this->modele->createUser($nom, $email, $hash);
                $_SESSION['success'] = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
                header("Location: ?page=login");
                exit;
            }
        }

        require __DIR__ . '/../vues/utilisateurs/inscription.php';
    }

    /**
     * Déconnexion
     */
    public function deconnexion() {
        session_destroy();
        header("Location: ?page=accueil");
        exit;
    }

    /**
     * Mot de passe oublié (optionnel)
     */
    public function mdpOublie() {
        require __DIR__ . '/../../app/vues/utilisateurs/motdepasseoublie.php';
    }
}
