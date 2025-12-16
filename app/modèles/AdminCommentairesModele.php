<?php
require_once __DIR__ . '/../config/database.php';

class AdminCommentairesModele
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDatabase();
    }

    /**
     * Récupère tous les commentaires avec filtres
     */
    public function all(?string $statut = null, ?string $q = null): array
    {
        $where = ["c.statut <> 'deleted'"];
        $params = [];

        if ($statut && trim($statut) !== '') {
            $where[] = "c.statut = :statut";
            $params[':statut'] = trim($statut);
        }

        if ($q && trim($q) !== '') {
            $where[] = "(c.texte LIKE :q OR v.titre LIKE :q OR u.nom LIKE :q OR c.auteur LIKE :q)";
            $params[':q'] = '%' . trim($q) . '%';
        }

        $sql = "SELECT
                    c.id, c.video_id, c.video_db_id, c.user_id, c.auteur,
                    c.texte, c.statut, c.created_at, c.updated_at,
                    v.titre AS video_titre, v.youtube_id,
                    u.nom AS user_nom, u.email AS user_email
                FROM commentaires_videos c
                LEFT JOIN videos v ON v.id = c.video_db_id
                LEFT JOIN connexion u ON u.id = c.user_id
                WHERE " . implode(" AND ", $where) . "
                ORDER BY c.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un commentaire par son ID
     */
    public function findById(int $id): ?array
    {
        $sql = "SELECT c.*, v.titre AS video_titre, u.nom AS user_nom
                FROM commentaires_videos c
                LEFT JOIN videos v ON v.id = c.video_db_id
                LEFT JOIN connexion u ON u.id = c.user_id
                WHERE c.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $comment = $stmt->fetch(PDO::FETCH_ASSOC);
        return $comment ?: null;
    }

    /**
     * Met à jour le contenu d'un commentaire
     */
    public function update(int $id, string $texte): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE commentaires_videos 
             SET texte = :texte, updated_at = NOW() 
             WHERE id = :id"
        );
        return $stmt->execute([':texte' => $texte, ':id' => $id]);
    }

    /**
     * Met à jour le statut d'un commentaire
     */
    public function updateStatut(int $id, string $statut): bool
    {
        $allowed = ['pending', 'approved', 'spam', 'deleted'];
        if (!in_array($statut, $allowed, true)) {
            throw new InvalidArgumentException("Statut invalide : $statut");
        }
        
        $stmt = $this->db->prepare(
            "UPDATE commentaires_videos 
             SET statut = :statut, updated_at = NOW() 
             WHERE id = :id"
        );
        return $stmt->execute([':statut' => $statut, ':id' => $id]);
    }

    /**
     * Suppression définitive (soft delete)
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE commentaires_videos 
             SET statut = 'deleted', updated_at = NOW() 
             WHERE id = :id"
        );
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Statistiques des commentaires
     */
    public function getStats(): array
    {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN statut = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN statut = 'approved' THEN 1 ELSE 0 END) as approved,
                    SUM(CASE WHEN statut = 'spam' THEN 1 ELSE 0 END) as spam
                FROM commentaires_videos
                WHERE statut <> 'deleted'";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [
            'total' => 0,
            'pending' => 0,
            'approved' => 0,
            'spam' => 0
        ];
    }

    /**
     * Commentaires récents
     */
    public function getRecent(int $limit = 10): array
    {
        $sql = "SELECT c.id, c.texte, c.auteur, c.statut, c.created_at,
                       v.titre AS video_titre
                FROM commentaires_videos c
                LEFT JOIN videos v ON v.id = c.video_db_id
                WHERE c.statut <> 'deleted'
                ORDER BY c.created_at DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}