<?php

class AdminWebtvModele {

    private $pdo;

    public function __construct() {
        $this->pdo = new PDO("mysql:host=localhost;dbname=fablab;charset=utf8mb4","root","");
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getComments($videoId) {
        $stmt = $this->pdo->prepare("SELECT * FROM commentaires_videos WHERE video_id=? ORDER BY created_at DESC");
        $stmt->execute([$videoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addComment($videoId, $texte) {
        $stmt = $this->pdo->prepare("INSERT INTO commentaires_videos(video_id, texte) VALUES (?,?)");
        $stmt->execute([$videoId, htmlspecialchars($texte)]);
    }
}
