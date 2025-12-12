<?php
require_once __DIR__ . '/../config/database.php';

class AdminUtilisateursModele {
    private PDO $db;

    public function __construct() { $this->db = getDatabase(); }

    public function all(): array {
        $sql = "SELECT id, nom, email, role, date_creation FROM connexion ORDER BY date_creation DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int {
        $hash = !empty($data['mot_de_passe']) ? password_hash($data['mot_de_passe'], PASSWORD_BCRYPT) : null;
        $sql = "INSERT INTO connexion (nom, email, mot_de_passe, role, date_creation)
                VALUES (:nom, :email, :mot_de_passe, :role, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nom'           => $data['nom'],
            ':email'         => $data['email'],
            ':mot_de_passe'  => $hash,
            ':role'          => $data['role'] ?? 'Utilisateur',
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        if (!empty($data['mot_de_passe'])) {
            $sql = "UPDATE connexion
                    SET nom=:nom, email=:email, role=:role, mot_de_passe=:mot_de_passe
                    WHERE id=:id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':nom'          => $data['nom'],
                ':email'        => $data['email'],
                ':role'         => $data['role'] ?? 'Utilisateur',
                ':mot_de_passe' => password_hash($data['mot_de_passe'], PASSWORD_BCRYPT),
                ':id'           => $id
            ]);
        } else {
            $sql = "UPDATE connexion
                    SET nom=:nom, email=:email, role=:role
                    WHERE id=:id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':nom'   => $data['nom'],
                ':email' => $data['email'],
                ':role'  => $data['role'] ?? 'Utilisateur',
                ':id'    => $id
            ]);
        }
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM connexion WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
