<?php
class WebtvModele {
    // Plus tard tu pourras connecter une base de données
    // Pour l’instant, on simule des données (comme ton ancien code)
    public function getVideos() {
        return [
            [
                'id' => 'x8Pc9hqTEO8',
                'title' => 'Introduction au Fablab',
                'description' => 'Présentation générale du FabLab et de ses projets innovants en robotique.',
                'start' => 2
            ],
            [
                'id' => 'mSyo25hKnfo',
                'title' => 'Robotique & Impression 3D',
                'description' => 'Projet étudiant de robotique utilisant des pièces imprimées en 3D.',
                'start' => 35
            ],
            [
                'id' => 'Ikownb7GSjE',
                'title' => 'Impression 3D avancée',
                'description' => 'Techniques d\'impression 3D complexes utilisées dans le FabLab.',
                'start' => 58
            ],
            [
                'id' => 'msY6LTbBc2s',
                'title' => 'Atelier Robotique',
                'description' => 'Démonstration robotique et présentation des projets étudiants associés.',
                'start' => 0
            ],
            [
                'id' => 'N8R8eWBI8Qk',
                'title' => 'Découpe laser',
                'description' => 'Formation à l\'utilisation de la découpe laser pour créer des prototypes.',
                'start' => 0
            ],
            [
                'id' => 'qJ6B_0Xv06E',
                'title' => 'Projets collaboratifs',
                'description' => 'Présentation de projets collaboratifs menés par la communauté du FabLab.',
                'start' => 0
            ]
        ];
    }
}
