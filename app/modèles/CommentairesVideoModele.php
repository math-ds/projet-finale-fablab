<?php
require_once __DIR__ . '/../config/database.php';

class CommentairesVideoModele
{
    private PDO $db;
    
    public function __construct() 
    { 
        $this->db = getDatabase(); 
    }

    /**
     * Liste les commentaires pour une vidéo (par youtube_id ou video_id)
     */
    public function listForVideo(string $videoId, bool $isAdmin = false): array
    {
        $sql = "SELECT c.id, c.video_id, c.video_db_id, c.user_id, c.auteur, 
                       c.texte, c.statut, c.created_at,
                       u.nom as user_nom, u.photo as user_photo
                FROM commentaires_videos c
                LEFT JOIN connexion u ON u.id = c.user_id
                WHERE c.video_id = :vid";

        // Admins voient tout sauf 'deleted', utilisateurs normaux voient seulement 'approved'
        $sql .= $isAdmin ? " AND c.statut <> 'deleted'" : " AND c.statut = 'approved'";
        $sql .= " ORDER BY c.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':vid' => $videoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crée un nouveau commentaire
     */
    public function create(string $videoId, ?int $videoDbId, ?int $userId, string $auteur, string $texte): bool
    {
        $sql = "INSERT INTO commentaires_videos
                (video_id, video_db_id, user_id, auteur, texte, statut, ip_address, user_agent, created_at)
                VALUES
                (:vid, :vdb, :uid, :auteur, :texte, 'pending', :ip, :ua, NOW())";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':vid'    => $videoId,
            ':vdb'    => $videoDbId,
            ':uid'    => $userId,
            ':auteur' => $auteur,
            ':texte'  => $texte,
            ':ip'     => $_SERVER['REMOTE_ADDR'] ?? null,
            ':ua'     => substr((string)($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255)
        ]);
    }

    /**
     * Compte les commentaires pour une vidéo
     */
    public function countForVideo(string $videoId, bool $onlyApproved = true): int
    {
        $sql = "SELECT COUNT(*) FROM commentaires_videos WHERE video_id = :vid";
        $sql .= $onlyApproved ? " AND statut = 'approved'" : " AND statut <> 'deleted'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':vid' => $videoId]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Supprime un commentaire (soft delete)
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("UPDATE commentaires_videos SET statut = 'deleted' WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Met à jour le contenu d'un commentaire
     */
    public function update(int $id, string $texte): bool
    {
        $stmt = $this->db->prepare("UPDATE commentaires_videos SET texte = :texte, updated_at = NOW() WHERE id = :id");
        return $stmt->execute([':texte' => $texte, ':id' => $id]);
    }

    /**
     * Change le statut d'un commentaire
     */
    public function updateStatut(int $id, string $statut): bool
    {
        $allowed = ['pending', 'approved', 'spam', 'deleted'];
        if (!in_array($statut, $allowed, true)) {
            throw new InvalidArgumentException("Statut invalide.");
        }
        
        $stmt = $this->db->prepare("UPDATE commentaires_videos SET statut = :s, updated_at = NOW() WHERE id = :id");
        return $stmt->execute([':s' => $statut, ':id' => $id]);
    }
}