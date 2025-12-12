<?php
require_once __DIR__ . '/../config/database.php';

class ArticleModele {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function getAllArticles() {
        $stmt = $this->db->query("SELECT * FROM articles ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getArticleById($id) {
        $stmt = $this->db->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
