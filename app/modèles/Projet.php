<?php
require_once __DIR__ . '/../config/database.php';

class Projet {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // 🔹 Récupère tous les projets avec encodage et vérification complète
    public function getTousLesProjets() {
        $stmt = $this->conn->query("SELECT * FROM projects ORDER BY created_at DESC");
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Sécurise chaque champ pour éviter les champs vides ou mal encodés
        foreach ($projects as &$p) {
            foreach ($p as $key => $value) {
                // Convertit tout en UTF-8 et supprime les caractères invisibles
                $p[$key] = htmlspecialchars_decode(trim($value ?? ''), ENT_QUOTES);
            }
        }

        return $projects;
    }

    // 🔹 Récupère un projet spécifique
    public function getProjetParId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        $p = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($p) {
            foreach ($p as $key => $value) {
                $p[$key] = htmlspecialchars_decode(trim($value ?? ''), ENT_QUOTES);
            }
        }

        return $p ?: [];
    }
}
