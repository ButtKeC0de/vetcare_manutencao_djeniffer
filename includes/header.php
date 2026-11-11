<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$titulo = $titulo ?? 'VetCare';
$baseUrl = '/vetcare_manutencao_djeniffer';
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($titulo) ?> | VetCare</title>
<link rel="stylesheet" href="<?= $baseUrl ?>/assets/style.css">
</head>
<body>
<header class="topbar">
  <a class="brand" href="<?= $baseUrl ?>/index.php">🐾 VetCare</a>
  <nav>
    <?php if (!empty($_SESSION['usuario_id'])): ?>
      <a href="<?= $baseUrl ?>/index.php">Painel</a>
      <a href="<?= $baseUrl ?>/tutores/index.php">Tutores</a>
      <a href="<?= $baseUrl ?>/animais/index.php">Animais</a>
      <a href="<?= $baseUrl ?>/consultas/index.php">Consultas</a>
      <a href="<?= $baseUrl ?>/chamados/index.php">Suporte</a>
      <a href="<?= $baseUrl ?>/logout.php">Sair</a>
    <?php endif; ?>
  </nav>
</header>
<main class="container">
