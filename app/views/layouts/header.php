<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="UrbanStep premium shoes for men, women, kids and sports.">
  <meta name="keywords" content="urbanstep, shoes, sneakers, footwear, sports shoes">
  <meta name="robots" content="index, follow">
  <title>UrbanStep</title>
  <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
<header class="site-header">
  <a href="/index.php" class="logo">UrbanStep</a>
  <nav>
    <a href="/index.php?r=products">Shop</a>
    <a href="/index.php?r=cart">Cart</a>
    <?php if (!empty($_SESSION['user'])): ?>
      <a href="/index.php?r=dashboard">Dashboard</a>
      <?php if ((int)$_SESSION['user']['is_admin'] === 1): ?><a href="/index.php?r=admin">Admin</a><?php endif; ?>
      <a href="/index.php?r=logout">Logout</a>
    <?php else: ?>
      <a href="/index.php?r=login">Login</a>
      <a href="/index.php?r=register">Register</a>
    <?php endif; ?>
  </nav>
</header>
<?php if (!empty($_SESSION['flash'])): ?><div class="flash"><?php echo $_SESSION['flash']; unset($_SESSION['flash']); ?></div><?php endif; ?>
<main>
