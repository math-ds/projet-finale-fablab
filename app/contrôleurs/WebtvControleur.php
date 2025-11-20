<?php
require_once __DIR__ . '/../modèles/webtvModele.php';

class WebtvControleur {
    public function index() {
        // Inclut le modèle si plus tard tu veux charger depuis la BDD
        require_once __DIR__ . '/../modèles/WebtvModele.php';

        $modele = new WebtvModele();
        $videos = $modele->getVideos();

        require_once __DIR__ . '/../vues/webtv/webtv.php';
    }
}
