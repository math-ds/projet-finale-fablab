<?php
require_once __DIR__ . '/../config/database.php';

class AdminContactModele
{
    private PDO $db;
    public function __construct() { $this->db = getDatabase(); }

    public function all(): array
    {
        $sql = "SELECT * FROM contact_messages ORDER BY date_envoi DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatut(int $id, string $statut): bool
    {
        $stmt = $this->db->prepare("UPDATE contact_messages SET statut = :statut, date_lecture = NOW() WHERE id = :id");
        return $stmt->execute([':id' => $id, ':statut' => $statut]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM contact_messages WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
