<?php require __DIR__ . '/../parties/header.php'; ?>
<link rel="stylesheet" href="../public/css/webtv.css">

<div class="youtube-container">
  <div class="youtube-layout">

    <div class="primary-column">
      <?php include __DIR__ . '/player.php'; ?>
      <?php include __DIR__ . '/comments.php'; ?>
    </div>

    <div class="secondary-column">
      <?php include __DIR__ . '/sidebar.php'; ?>
    </div>

  </div>
</div>

<script src="../public/js/webtv.js"></script>
<?php require __DIR__ . '/../parties/footer.php'; ?>
