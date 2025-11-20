<?php
// ==========================================
// CONTRÔLEUR : app/contrôleurs/AdminDashboardControleur.php
// ==========================================

class AdminDashboardControleur {
    public function index() {
        // Vérifie si l’utilisateur est connecté et admin
       
        if (!isset($_SESSION['utilisateur_role']) || strtolower($_SESSION['utilisateur_role']) !== 'admin') {
            header('Location: ?page=login');
            exit();
        }

        // Affiche la vue du tableau de bord
        require __DIR__ . '/../vues/admin/dashboard-admin.php';
    }
}
