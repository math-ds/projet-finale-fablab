<?php
// ===============================
// Vue : Mot de passe oublié
// ===============================
$GLOBALS['baseUrl'] = '/Fablabrobot/public/';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - FABLAB</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/utilisateurs.css">
<body>
    <!-- Particules de fond -->
    <div class="particles" id="particles"></div>

    <!-- Header -->
    <header class="header">
        <div class="nav-container">
            <div class="logo-section">
                <img src="../public/images/global/ajc_logo_blanc.png" alt="Logo FABLAB ROBOTIQUE" class="navbar-logo" />
                <a href="<?= $GLOBALS['baseUrl'] ?>index.php?page=accueil" class="brand-name">FABLAB ROBOTIQUE</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-container">
        <div class="registration-card">
            <h2 class="card-title">Mot de passe oublié</h2>
            
            <!-- Description -->
            <div class="form-description">
                <p>Entrez votre adresse email pour recevoir un lien de réinitialisation de votre mot de passe.</p>
            </div>
            
            <form id="forgotPasswordForm" method="POST" action="">
                <div class="form-group">
                    <label for="email" class="form-label">Adresse email</label>
                    <div class="input-group">
                        <input
                            type="email"
                            class="form-input"
                            id="email"
                            name="email"
                            placeholder="votre.email@exemple.com"
                            required
                        />
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                    <div class="validation-message" id="email-message"></div>
                </div>

                <button type="submit" class="submit-btn" id="submitBtn">
                    <i class="fas fa-paper-plane"></i>
                    Envoyer le lien
                </button>
            </form>

            <div class="login-link">
                <p>Vous vous souvenez de votre mot de passe ?</p>
                <a href="<?= $GLOBALS['baseUrl'] ?>index.php?page=login" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Retour à la connexion
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 FSR. Tous droits réservés.</p>
    </footer>

    <!-- JavaScript -->
    <script>
        function createParticles() {
            const particles = document.getElementById('particles');
            const particleCount = 50;
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 6 + 's';
                particle.style.animationDuration = (Math.random() * 3 + 3) + 's';
                particles.appendChild(particle);
            }
        }

        function validateEmail(email) {
            const emailRegex = /^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/;
            return emailRegex.test(email);
        }

        function showValidationMessage(inputId, message, isValid) {
            const input = document.getElementById(inputId);
            const messageEl = document.getElementById(inputId + '-message');
            if (isValid) {
                input.classList.remove('invalid');
                input.classList.add('valid');
                messageEl.textContent = message;
                messageEl.className = 'validation-message success';
            } else {
                input.classList.remove('valid');
                input.classList.add('invalid');
                messageEl.textContent = message;
                messageEl.className = 'validation-message error';
            }
        }

        document.getElementById('email').addEventListener('input', function() {
            const isValid = validateEmail(this.value);
            showValidationMessage('email',
                isValid ? 'Email valide' : 'Veuillez saisir un email valide',
                isValid
            );
        });

        document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.innerHTML = '<div class="loading"></div> Envoi en cours...';
            submitBtn.disabled = true;

            setTimeout(() => {
                const card = document.querySelector('.registration-card');
                card.innerHTML = `
                    <h2 class="card-title">Email envoyé !</h2>
                    <div class="message success">
                        <i class="fas fa-check-circle"></i>
                        Un lien de réinitialisation a été envoyé à votre adresse email.
                    </div>
                    <div class="form-description">
                        <p>Vérifiez votre boîte mail et suivez les instructions pour réinitialiser votre mot de passe.</p>
                        <p><strong>Note :</strong> Le lien expirera dans 24 heures pour des raisons de sécurité.</p>
                    </div>
                    <div class="login-link">
                        <a href="<?= $GLOBALS['baseUrl'] ?>index.php?page=login" class="back-btn">
                            <i class="fas fa-arrow-left"></i> Retour à la connexion
                        </a>
                    </div>
                `;
            }, 2000);
        });

        document.addEventListener('DOMContentLoaded', createParticles);
    </script>

    <!-- Style interne (juste pour la description) -->

</body>
</html>
