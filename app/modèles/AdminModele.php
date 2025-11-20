<?php
require_once __DIR__ . '/../config/database.php';

class AdminModele {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
public function getAllProjects() {
    $stmt = $this->db->query("SELECT * FROM projects ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function getStatistiques() {
        // Exemple de récupération de stats (optionnel)
        $stats = [];
        $stats['total_projets'] = $this->db->query("SELECT COUNT(*) FROM projects")->fetchColumn();
        $stats['total_articles'] = $this->db->query("SELECT COUNT(*) FROM articles")->fetchColumn();
        $stats['total_utilisateurs'] = $this->db->query("SELECT COUNT(*) FROM connexion")->fetchColumn();
        return $stats;
    }
}
