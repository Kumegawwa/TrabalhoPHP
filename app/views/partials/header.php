<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestão de Aprendizagem</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <script defer src="/public/js/main.js"></script>
</head>
<body>
    <header class="navbar">
        <div class="container">
            <div class="logo-container">
                <h1 class="logo"><a href="/"><i class="fas fa-graduation-cap"></i> SGA</a></h1>
            </div>
            <nav>
                <input type="checkbox" id="menu-toggle">
                <label for="menu-toggle" class="hamburger" aria-label="Menu"><i class="fas fa-bars"></i></label>
                <ul class="nav-links">
                    <li><a href="/home" class="<?= $current == 'home.php' ? 'active' : '' ?>"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="/cursos" class="<?= $current == 'cursos.php' ? 'active' : '' ?>"><i class="fas fa-book"></i> Cursos</a></li>
                    <li><a href="/sobre" class="<?= $current == 'sobre.php' ? 'active' : '' ?>"><i class="fas fa-info-circle"></i> Sobre</a></li>
                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle"><i class="fas fa-user-circle"></i> Minha Conta <i class="fas fa-chevron-down"></i></a>
                            <ul class="dropdown-menu">
                                <li><a href="/painel"><i class="fas fa-tachometer-alt"></i> Painel</a></li>
                                <li><a href="/perfil"><i class="fas fa-user-edit"></i> Perfil</a></li>
                                <li><a href="/logout" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li><a href="/login" class="login-btn"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="main-content">
        <div class="container">
