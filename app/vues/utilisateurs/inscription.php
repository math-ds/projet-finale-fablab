<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm-password'] ?? '';
    $role = 'Utilisateur';

    if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = "Tous les champs sont requis.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "L'adresse email n'est pas valide.";
    } elseif (strlen($name) < 2) {
        $error = "Le nom doit contenir au moins 2 caractères.";
    } elseif ($password !== $confirmPassword) {
        $error = "Les mots de passe ne correspondent pas.";
    } elseif (strlen($password) < 8) {
        $error = "Le mot de passe doit contenir au moins 8 caractères.";
    } else {
        $conn = new mysqli("localhost", "root", "", "fablab");
        if ($conn->connect_error) {
            die("Erreur de connexion : " . $conn->connect_error);
        }

        $check = $conn->prepare("SELECT id FROM connexion WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Cet email est déjà utilisé.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO connexion (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);

            if ($stmt->execute()) {
                $_SESSION['utilisateur_id'] = $conn->insert_id;
                $_SESSION['utilisateur_nom'] = $name;
                $_SESSION['utilisateur_email'] = $email;
                $_SESSION['utilisateur_role'] = $role;

                $initiales = strtoupper(substr($name, 0, 2));
                $_SESSION['utilisateur_avatar'] = "https://via.placeholder.com/40/232e59/ffffff?text=" . $initiales;

                // ✅ Redirection MVC
                header("Location: ?page=accueil");
                exit;
            } else {
                $error = "Erreur lors de l'inscription.";
            }

            $stmt->close();
        }

        $check->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - FABLAB</title>
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
        <h2 class="card-title">Créer un compte</h2>

        <?php if (isset($error)): ?>
            <div class="message error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <!-- Nom complet -->
            <div class="form-group">
                <label for="name" class="form-label">Nom complet</label>
                <div class="input-group">
                    <input type="text" class="form-input" id="name" name="name"
                           placeholder="Votre nom complet" 
                           required 
                           minlength="2"
                           pattern="[A-Za-zÀ-ÿ\s\-']+"
                           title="Le nom doit contenir au moins 2 caractères (lettres uniquement)"
                           value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                    <i class="fas fa-user input-icon"></i>
                </div>
            </div>

            <!-- Adresse email -->
            <div class="form-group">
                <label for="email" class="form-label">Adresse email</label>
                <div class="input-group">
                    <input type="email" class="form-input" id="email" name="email"
                           placeholder="votre.email@exemple.com" 
                           required
                           pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$"
                           title="Veuillez entrer une adresse email valide (ex: nom@domaine.com)"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    <i class="fas fa-envelope input-icon"></i>
                </div>
            </div>

            <!-- Mot de passe -->
            <div class="form-group">
                <label for="password" class="form-label">Mot de passe</label>
                <div class="input-group">
                    <input type="password" class="form-input" id="password" name="password"
                           placeholder="Votre mot de passe" 
                           required
                           minlength="6"
                           title="Le mot de passe doit contenir au moins 6 caractères">
                    <i class="fas fa-lock input-icon"></i>
                </div>
                <small style="color: #8b92b0; font-size: 0.85rem; margin-top: 0.25rem; display: block;">
                    Minimum 6 caractères
                </small>
            </div>

            <!-- Confirmer le mot de passe -->
            <div class="form-group">
                <label for="confirm-password" class="form-label">Confirmer le mot de passe</label>
                <div class="input-group">
                    <input type="password" class="form-input" id="confirm-password"
                           name="confirm-password" 
                           placeholder="Confirmez votre mot de passe" 
                           required
                           minlength="6"
                           title="Les mots de passe doivent correspondre">
                    <i class="fas fa-lock input-icon"></i>
                </div>
            </div>

            <button type="submit" class="submit-btn">
                <i class="fas fa-user-plus"></i> S'inscrire
            </button>
        </form>

        <div class="login-link">
            <p>Déjà inscrit ?</p>
            <a href="?page=login">Se connecter</a>
        </div>
    </div>
</main>

<footer class="footer">
    <p>&copy; 2025 FSR. Tous droits réservés.</p>
</footer>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Animation des particules
    const particles = document.getElementById('particles');
    for (let i = 0; i < 50; i++) {
        const p = document.createElement('div');
        p.className = 'particle';
        p.style.left = Math.random() * 100 + '%';
        p.style.top = Math.random() * 100 + '%';
        p.style.animationDelay = Math.random() * 6 + 's';
        particles.appendChild(p);
    }

    // Validation en temps réel des mots de passe
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm-password');
    
    confirmPassword.addEventListener('input', () => {
        if (password.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity('Les mots de passe ne correspondent pas');
        } else {
            confirmPassword.setCustomValidity('');
        }
    });
    
    password.addEventListener('input', () => {
        if (confirmPassword.value) {
            confirmPassword.dispatchEvent(new Event('input'));
        }
    });
});
</script>

</body>
</html>