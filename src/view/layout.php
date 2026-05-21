<?php

declare(strict_types=1);

$pageTitle = $pageTitle ?? 'Andino Products Management';
$currentPage = $currentPage ?? 'products';
$assetBase = $assetBase ?? '/public';

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars((string) $pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="stylesheet" href="<?= htmlspecialchars($assetBase . '/css/styles.css', ENT_QUOTES, 'UTF-8') ?>">
</head>
<body>
  <nav class="navbar">
    <div class="navbar-inner">
      <a class="navbar-brand" href="index.php?page=products">Andino Products</a>
      <div class="navbar-links">
        <a class="navbar-link<?= $currentPage === 'products' ? ' active' : '' ?>" href="index.php?page=products">View Products</a>
        <a class="navbar-link<?= $currentPage === 'add' ? ' active' : '' ?>" href="index.php?page=add">Add Product</a>
      </div>
    </div>
  </nav>

  <div class="page-shell">
    <div class="container">
      <?= $content ?? '' ?>
    </div>
  </div>

  <script src="<?= htmlspecialchars($assetBase . '/js/validation.js', ENT_QUOTES, 'UTF-8') ?>"></script>
</body>
</html>