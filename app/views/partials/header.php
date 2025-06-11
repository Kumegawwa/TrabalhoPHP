<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Certifique-se que BASE_URL está definida
if (!defined('BASE_URL')) {
    define('BASE_URL', '/TrabalhoPHP'); // Ou o que for correto
}
// $current é um pouco mais complexo com o roteador, vamos simplificar por enquanto
// Você pode obter a rota atual do $route em index.php e passá-la para a view se necessário
// Ou pode analisar $_SERVER['REQUEST_URI']
$current_path = strtok($_SERVER['REQUEST_URI'], '?');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestão de Aprendizagem</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <script defer src="<?= BASE_URL ?>/public/js/main.js"></script>
</head>
<body>
    <header class="navbar">
        </header>
    <main class="main-content">
        <div class="container page-container">
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-error"><?= $_SESSION['error_message'] ?></div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success_message'] ?></div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>
<body>
    <header class="navbar">
        <div class="container">
            <div class="logo-container">
                <h1 class="logo"><a href="<?= BASE_URL ?>/"><i class="fas fa-graduation-cap"></i> SGA</a></h1>
            </div>
            <nav>
                <input type="checkbox" id="menu-toggle">
                <label for="menu-toggle" class="hamburger" aria-label="Menu"><i class="fas fa-bars"></i></label>
                <ul class="nav-links">
                    <li><a href="<?= BASE_URL ?>/home" class="<?= ($current_path == BASE_URL.'/home' || $current_path == BASE_URL.'/') ? 'active' : '' ?>"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="<?= BASE_URL ?>/cursos" class="<?= (strpos($current_path, BASE_URL.'/cursos') === 0) ? 'active' : '' ?>"><i class="fas fa-book"></i> Cursos</a></li>
                    <li><a href="<?= BASE_URL ?>/sobre" class="<?= ($current_path == BASE_URL.'/sobre') ? 'active' : '' ?>"><i class="fas fa-info-circle"></i> Sobre</a></li>
                    <li><a href="<?= BASE_URL ?>/lista-cursos" class="<?= ($current_path == BASE_URL.'/lista-cursos') ? 'active' : '' ?>"><i class="fas fa-list"></i> Lista Cursos</a></li>
                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle"><i class="fas fa-user-circle"></i> <?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Minha Conta') ?> <i class="fas fa-chevron-down"></i></a>
                            <ul class="dropdown-menu">
                                <!-- Adicione rotas para painel e perfil se existirem -->
                                <!-- <li><a href="<?= BASE_URL ?>/painel"><i class="fas fa-tachometer-alt"></i> Painel</a></li> -->
                                <!-- <li><a href="<?= BASE_URL ?>/perfil"><i class="fas fa-user-edit"></i> Perfil</a></li> -->
                                <li><a href="<?= BASE_URL ?>/logout" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li><a href="<?= BASE_URL ?>/login" class="login-btn <?= ($current_path == BASE_URL.'/login') ? 'active' : '' ?>"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="main-content">
        <div class="container page-container"> <!-- Adicionada classe page-container -->