<?php
class ProfilModele
{
    private PDO $db;

    public function __construct()
    {
        $this->db = new PDO('mysql:host=127.0.0.1:3306;dbname=fablab;charset=utf8mb4', 'root', '');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getUserById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM utilisateurs WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function updatePhoto(int $id, string $photo): void
    {
        $stmt = $this->db->prepare("UPDATE utilisateurs SET photo = ? WHERE id = ?");
        $stmt->execute([$photo, $id]);
    }
}
