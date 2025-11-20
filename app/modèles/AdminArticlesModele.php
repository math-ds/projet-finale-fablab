<?php
// app/modèles/AdminArticlesModele.php
require_once __DIR__ . '/../config/database.php';

class AdminArticlesModele
{
    private PDO $db;
    public function __construct() { $this->db = getDatabase(); }

    public function all(): array
    {
        $sql = "SELECT id, titre, contenu, auteur, image_url, created_at, updated_at
                FROM articles ORDER BY created_at DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO articles (titre, contenu, auteur, image_url, created_at, updated_at)
                VALUES (:titre, :contenu, :auteur, :image_url, NOW(), NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':titre'     => $data['titre'],
            ':contenu'   => $data['contenu'],
            ':auteur'    => $data['auteur'],
            ':image_url' => $data['image_url'] ?? null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE articles
                SET titre=:titre, contenu=:contenu, auteur=:auteur, image_url=:image_url, updated_at=NOW()
                WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':titre'     => $data['titre'],
            ':contenu'   => $data['contenu'],
            ':auteur'    => $data['auteur'],
            ':image_url' => $data['image_url'] ?? null,
            ':id'        => $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM articles WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
