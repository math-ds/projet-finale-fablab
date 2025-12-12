<?php
require_once __DIR__ . '/../modèles/Projet.php';

class ProjetControleur {

    public function detail($id) {
        $modele = new Projet();
        $projet = $modele->getProjetParId($id);

        // Nettoyage pour éviter les "vides" à cause d'espaces ou retours ligne
        if ($projet) {
            foreach ($projet as $key => $value) {
                if (is_string($value)) {
                    $projet[$key] = trim($value);
                }
            }
        }

        require __DIR__ . '/../vues/projets/detail.php';
    }
}
