<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($page_title ?? "Complaint Management System") ?></title>
<link rel="stylesheet" href="<?= htmlspecialchars($asset_prefix ?? "") ?>assets/style.css">
</head>
<body>
<header class="topbar">
  <a class="brand" href="<?= htmlspecialchars($home_link ?? "index.php") ?>">Online Complaint Management System</a>
  <nav>
    <?php if (!empty($_SESSION["user_id"])): ?>
      <span><?= htmlspecialchars($_SESSION["name"] ?? "User") ?></span>
      <a href="<?= htmlspecialchars($logout_link ?? "logout.php") ?>">Logout</a>
    <?php else: ?>
      <a href="<?= htmlspecialchars($login_link ?? "login.php") ?>">Login</a>
      <a href="<?= htmlspecialchars($register_link ?? "register.php") ?>">Register</a>
    <?php endif; ?>
  </nav>
</header>
<main class="container">
