<?php
require_once __DIR__ . '/../modèles/AdminUtilisateursModele.php';

class AdminUtilisateursControleur
{
    private AdminUtilisateursModele $modele;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->modele = new AdminUtilisateursModele();
    }

    public function handleRequest(?string $action = null): void
    {
        $action = $action ?? ($_GET['action'] ?? null);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formAction = $_POST['action'] ?? null;

            try {
                if ($formAction === 'create') {
                    $this->modele->create([
                        'nom' => trim($_POST['nom']),
                        'email' => trim($_POST['email']),
                        'role' => trim($_POST['role']),
                        'mot_de_passe' => trim($_POST['mot_de_passe'])
                    ]);
                    $_SESSION['message'] = "Utilisateur ajouté avec succès.";
                    $_SESSION['message_type'] = "success";
                } elseif ($formAction === 'update') {
                    $id = (int)$_POST['user_id'];
                    $this->modele->update($id, [
                        'nom' => trim($_POST['nom']),
                        'email' => trim($_POST['email']),
                        'role' => trim($_POST['role']),
                        'mot_de_passe' => trim($_POST['mot_de_passe'])
                    ]);
                    $_SESSION['message'] = "Utilisateur mis à jour.";
                    $_SESSION['message_type'] = "success";
                } elseif ($formAction === 'delete') {
                    $id = (int)$_POST['user_id'];
                    $this->modele->delete($id);
                    $_SESSION['message'] = "Utilisateur supprimé.";
                    $_SESSION['message_type'] = "success";
                }
            } catch (Throwable $e) {
                $_SESSION['message'] = "Erreur : " . $e->getMessage();
                $_SESSION['message_type'] = "danger";
            }

            header("Location: ?page=admin-utilisateurs");
            exit;
        }

        $this->index();
    }

    public function index(): void
    {
        $users = $this->modele->all();

        $total_users = count($users);
        $admins = count(array_filter($users, fn($u) => strtolower($u['role']) === 'admin'));
        $editeurs = count(array_filter($users, fn($u) => in_array(strtolower($u['role']), ['editeur', 'éditeur'])));
        $utilisateurs = count(array_filter($users, fn($u) => in_array(strtolower($u['role']), ['user', 'utilisateur'])));

        $stats = compact('total_users', 'admins', 'editeurs', 'utilisateurs');

        include __DIR__ . '/../vues/admin/utilisateurs-admin.php';
    }
}
