<?php include(__DIR__ . '/../parties/header.php'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact</title>
  <link rel="stylesheet" href="<?= '/Fablabrobot/public/css/contact.css' ?>">
</head>
<body>
<main class="main-container">
  <div class="contact-card">
    <h1 class="card-title">Contactez-nous</h1>
    <p class="card-subtitle">
      Vous avez une question, une suggestion ou souhaitez signaler un problème ?
      N'hésitez pas à nous contacter !
    </p>

    <?php if ($message_sent): ?>
      <div class="message success">
        ✓ Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.
      </div>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
      <div class="message error">
        ✕ <?= $error_message ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="" id="contactForm">
      <div class="form-group">
        <label for="name" class="form-label">Nom complet *</label>
        <input type="text" id="name" name="name" class="form-input" placeholder="Entrez votre nom"
               value="<?= $_POST['name'] ?? '' ?>" required>
      </div>

      <div class="form-group">
        <label for="email" class="form-label">Email *</label>
        <input type="email" id="email" name="email" class="form-input"
               placeholder="votre@email.com" value="<?= $_POST['email'] ?? '' ?>" required>
      </div>

      <div class="form-group">
        <label for="subject" class="form-label">Sujet *</label>
        <select id="subject" name="subject" class="form-select" required>
          <option value="">Sélectionnez un sujet</option>
          <option value="suggestion" <?= (($_POST['subject'] ?? '') === 'suggestion') ? 'selected' : '' ?>>Suggestion d'amélioration</option>
          <option value="bug" <?= (($_POST['subject'] ?? '') === 'bug') ? 'selected' : '' ?>>Signaler un bug</option>
          <option value="question" <?= (($_POST['subject'] ?? '') === 'question') ? 'selected' : '' ?>>Question générale</option>
          <option value="feedback" <?= (($_POST['subject'] ?? '') === 'feedback') ? 'selected' : '' ?>>Feedback</option>
          <option value="other" <?= (($_POST['subject'] ?? '') === 'other') ? 'selected' : '' ?>>Autre</option>
        </select>
      </div>

      <div class="form-group">
        <label for="message" class="form-label">Message *</label>
        <textarea id="message" name="message" class="form-textarea"
                  placeholder="Décrivez votre message en détail..." required><?= $_POST['message'] ?? '' ?></textarea>
      </div>

      <button type="submit" class="submit-btn">Envoyer le message</button>
    </form>
  </div>
</main>

<?php include(__DIR__ . '/../parties/footer.php'); ?>

<?php if ($message_sent): ?>
<script>
setTimeout(() => {
  window.location.href = '/Fablabrobot/public/index.php';
}, 5000);
</script>
<?php endif; ?>

<script>
const inputs = document.querySelectorAll('input, textarea, select');
inputs.forEach(input => {
  input.addEventListener('blur', () => {
    input.style.borderColor = input.value.trim() ? 'var(--primary-color)' : 'rgba(255, 255, 255, 0.1)';
  });
});
const messages = document.querySelectorAll('.message');
messages.forEach(m => setTimeout(() => m.style.display = 'none', 5000));
</script>
</body>
</html>
