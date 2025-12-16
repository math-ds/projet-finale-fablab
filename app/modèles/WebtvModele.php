<?php
require_once __DIR__ . '/../config/database.php';

class WebtvModele
{
    private PDO $db;
    
    public function __construct() 
    { 
        $this->db = getDatabase(); 
    }

    /**
     * Récupère toutes les vidéos avec filtres optionnels
     */
    public function all(?string $q = null, ?string $cat = null): array
    {
        $where = [];
        $params = [];

        if ($q && trim($q) !== '') {
            $where[] = "(titre LIKE :q OR description LIKE :q OR auteur LIKE :q)";
            $params[':q'] = "%" . trim($q) . "%";
        }
        
        if ($cat && trim($cat) !== '') {
            $where[] = "categorie = :cat";
            $params[':cat'] = trim($cat);
        }

        $sql = "SELECT id, titre, description, categorie, type, fichier, youtube_id, 
                       vignette, vues, duree, auteur, likes, created_at
                FROM videos";
        
        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        
        $sql .= " ORDER BY created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les catégories distinctes
     */
    public function categories(): array
    {
        $sql = "SELECT DISTINCT categorie 
                FROM videos 
                WHERE categorie IS NOT NULL AND categorie != '' 
                ORDER BY categorie ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Trouve une vidéo par son ID
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM videos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $video = $stmt->fetch(PDO::FETCH_ASSOC);
        return $video ?: null;
    }

    /**
     * Trouve une vidéo par son youtube_id
     */
    public function findByYoutubeId(string $youtubeId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM videos WHERE youtube_id = :y LIMIT 1");
        $stmt->execute([':y' => $youtubeId]);
        $video = $stmt->fetch(PDO::FETCH_ASSOC);
        return $video ?: null;
    }

    /**
     * Incrémente le compteur de vues
     */
    public function incrementViews(int $id): void
    {
        $stmt = $this->db->prepare("UPDATE videos SET vues = COALESCE(vues, 0) + 1 WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    /**
     * Compte le nombre total de vidéos
     */
    public function count(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM videos")->fetchColumn();
    }
}