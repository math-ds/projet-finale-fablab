<?php
// app/modèles/AdminWebtvModele.php
require_once __DIR__ . '/../config/database.php';

class AdminWebtvModele
{
    private PDO $db;
    public function __construct() { $this->db = getDatabase(); }

    public function all(): array
    {
        $sql = "SELECT id, titre, description, categorie, type, fichier, youtube_id, vignette, created_at
                FROM videos ORDER BY created_at DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO videos (titre, description, categorie, type, fichier, youtube_id, vignette, created_at)
                VALUES (:titre, :description, :categorie, :type, :fichier, :youtube_id, :vignette, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':titre'       => $data['titre'],
            ':description' => $data['description'] ?? null,
            ':categorie'   => $data['categorie'] ?? null,
            ':type'        => $data['type'] ?? 'local',
            ':fichier'     => $data['fichier'] ?? null,
            ':youtube_id'  => $data['youtube_id'] ?? null,
            ':vignette'    => $data['vignette'] ?? null
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE videos
                SET titre=:titre, description=:description, categorie=:categorie, type=:type,
                    fichier=:fichier, youtube_id=:youtube_id, vignette=:vignette
                WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':titre'       => $data['titre'],
            ':description' => $data['description'] ?? null,
            ':categorie'   => $data['categorie'] ?? null,
            ':type'        => $data['type'] ?? 'local',
            ':fichier'     => $data['fichier'] ?? null,
            ':youtube_id'  => $data['youtube_id'] ?? null,
            ':vignette'    => $data['vignette'] ?? null,
            ':id'          => $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM videos WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
