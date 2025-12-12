<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Tous les champs sont requis.";
    } else {
        $host = "localhost";
        $user = "root";
        $pass = "";
        $dbname = "fablab";

        $conn = new mysqli($host, $user, $pass, $dbname);

        if ($conn->connect_error) {
            die("Erreur de connexion : " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT id, nom, mot_de_passe, role FROM connexion WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $nom, $hashedPassword, $role);
            $stmt->fetch();

            if (password_verify($password, $hashedPassword)) {
                $_SESSION['utilisateur_id'] = $id;
                $_SESSION['utilisateur_nom'] = $nom;
                $_SESSION['utilisateur_email'] = $email;
                $_SESSION['utilisateur_role'] = !empty($role) ? $role : 'Utilisateur';

                // Avatar automatique
                $initiales = '';
                if (!empty($nom)) {
                    $parts = preg_split('/\s+/', trim($nom));
                    $initiales = (count($parts) >= 2)
                        ? strtoupper(substr($parts[0], 0, 1) . substr(end($parts), 0, 1))
                        : strtoupper(substr($parts[0], 0, 2));
                } else {
                    $initiales = 'US';
                }
                $_SESSION['utilisateur_avatar'] = "https://via.placeholder.com/40/232e59/ffffff?text=" . $initiales;

                // ✅ Redirection MVC
                header("Location: ?page=accueil");
                exit;
            } else {
                $error = "Mot de passe incorrect.";
            }
        } else {
            $error = "Aucun compte trouvé avec cet email.";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - FABLAB</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/utilisateurs.css">
</head>
<body>

<div class="particles" id="particles"></div>

<header class="header">
    <div class="nav-container">
        <div class="logo-section">
            <img src="../public/images/global/ajc_logo_blanc.png" alt="Logo FABLAB" class="navbar-logo" />
            <a href="?page=accueil" class="brand-name">FABLAB ROBOTIQUE</a>
        </div>
    </div>
</header>

<main class="main-container">
    <div class="registration-card">
        <h2 class="card-title">Se connecter</h2>

        <?php if (isset($error)): ?>
            <div class="message error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form id="loginForm" method="POST" action="">
            <div class="form-group">
                <label for="email" class="form-label">Adresse email</label>
                <div class="input-group">
                    <input type="email" class="form-input" id="email" name="email"
                           placeholder="votre.email@exemple.com"
                           required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    <i class="fas fa-envelope input-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Mot de passe</label>
                <div class="input-group">
                    <input type="password" class="form-input" id="password" name="password"
                           placeholder="Votre mot de passe" required>
                    <i class="fas fa-lock input-icon"></i>
                </div>
            </div>

            <button type="submit" class="submit-btn">
                <i class="fas fa-sign-in-alt"></i> Se connecter
            </button>
        </form>

        <div class="login-link">
            <p><a href="?page=mdp-oublie">Mot de passe oublié ?</a></p>
        </div>

        <div class="login-link">
            <p>Pas encore inscrit ?</p>
            <a href="?page=inscription">Créer un compte</a>
        </div>
    </div>
</main>

<footer class="footer">
    <p>&copy; 2025 FSR. Tous droits réservés.</p>
</footer>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const particles = document.getElementById('particles');
    for (let i = 0; i < 50; i++) {
        const p = document.createElement('div');
        p.className = 'particle';
        p.style.left = Math.random() * 100 + '%';
        p.style.top = Math.random() * 100 + '%';
        p.style.animationDelay = Math.random() * 6 + 's';
        particles.appendChild(p);
    }
});
</script>

</body>
</html>
