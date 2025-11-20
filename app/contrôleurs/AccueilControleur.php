<?php
// app/contrôleurs/AccueilControleur.php
// --------------------------
// Contrôleur de la page d'accueil
// --------------------------

class AccueilControleur {
    public function index() {
        // On charge simplement la vue correspondante
        require __DIR__ . '/../vues/accueil/index.php';
    }
}
