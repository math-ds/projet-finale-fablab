<?php
// app/modèles/AdminCommentairesModele.php
require_once __DIR__ . '/../config/database.php';

class AdminCommentairesModele
{
    private PDO $db;

    public function __construct() {
        $this->db = getDatabase();
    }

    // Récupérer tous les commentaires + la vidéo associée
    public function all(): array
    {
        $sql = "SELECT c.id, c.video_id, c.auteur, c.texte, c.created_at,
                       v.titre AS video_titre
                FROM commentaires_videos as c
                JOIN videos as v ON c.video_id = v.id
                ORDER BY c.created_at DESC";



        $sql = "SELECT c.id, c.video_id, c.auteur, c.texte, c.created_at
                FROM commentaires_videos as c
                ORDER BY c.created_at DESC";
         return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Modifier un commentaire
    public function update(int $id, string $message): bool
    {
        $sql = "UPDATE commentaires_videos SET texte = :texte WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':texte' => $message,
            ':id'      => $id
        ]);
    }

    // Supprimer un commentaire
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM commentaires_videos WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
