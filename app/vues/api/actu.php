<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Connexion à la base de données
$host = 'localhost';
$dbname = 'fablab';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Récupérer les actualités depuis la base de données
    $stmt = $pdo->prepare("SELECT titre, date, image, contenu FROM actualites ORDER BY created_at DESC");
    $stmt->execute();
    $actus = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Si aucune actualité en base, retourner des données par défaut
    if (empty($actus)) {
        $actus = [
            [
                'titre' => 'Nouvelle série FabLab',
                'date' => 'il y a 1 jour',
                'image' => 'https://img.youtube.com/vi/x8Pc9hqTEO8/hqdefault.jpg',
                'contenu' => 'Découvrez notre nouvelle série de tutoriels sur la robotique avancée ! Chaque semaine, apprenez à créer vos propres robots avec des experts du domaine. Cette série s\'adresse aussi bien aux débutants qu\'aux makers confirmés. Nous aborderons des sujets variés : conception mécanique, programmation Arduino, intégration de capteurs, vision par ordinateur et intelligence artificielle. Rejoignez notre communauté et participez aux projets collaboratifs !'
            ],
            [
                'titre' => 'Concours Innovation 2025',
                'date' => 'il y a 3 jours',
                'image' => 'https://img.youtube.com/vi/mSyo25hKnfo/hqdefault.jpg',
                'contenu' => 'Participez au grand concours d\'innovation ! Soumettez vos projets avant le 31 décembre et gagnez du matériel pour votre FabLab. Que vous soyez étudiant, maker amateur ou professionnel, ce concours est ouvert à tous. Les catégories incluent : robotique, impression 3D, IoT, développement durable et projets artistiques. Les gagnants recevront des imprimantes 3D, des kits Arduino, et un accès premium à notre plateforme pendant un an. Jury composé d\'experts du secteur et de membres de la communauté.'
            ],
            [
                'titre' => 'Live Workshop',
                'date' => 'il y a 5 jours',
                'image' => 'https://img.youtube.com/vi/Ikownb7GSjE/hqdefault.jpg',
                'contenu' => 'Rejoignez-nous pour un live exceptionnel ce samedi à 14h ! Au programme : impression 3D en direct et session Q&A avec nos experts. Nous imprimerons en direct plusieurs pièces complexes et expliquerons les techniques de calibration, choix des matériaux et optimisation des paramètres. La session Q&A vous permettra de poser toutes vos questions sur l\'impression 3D, la modélisation 3D, et les meilleures pratiques du FabLab. L\'événement sera streamé en direct sur notre chaîne et restera disponible en replay.'
            ],
            [
                'titre' => 'Nouveau partenariat',
                'date' => 'il y a 1 semaine',
                'image' => 'https://img.youtube.com/vi/msY6LTbBc2s/hqdefault.jpg',
                'contenu' => 'Nous sommes fiers d\'annoncer notre partenariat avec MakerSpace France ! Accédez à de nouvelles ressources et formations exclusives. Ce partenariat stratégique nous permet d\'offrir à notre communauté un accès privilégié à plus de 200 formations en ligne, des réductions sur le matériel professionnel, et la possibilité de participer à des événements nationaux. Les membres bénéficieront également d\'un réseau élargi de FabLabs partenaires à travers toute la France. Inscrivez-vous dès maintenant pour profiter des avantages.'
            ]
        ];
    }
    
    echo json_encode($actus);
    
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Erreur de base de données',
        'message' => $e->getMessage()
    ]);
}
?>