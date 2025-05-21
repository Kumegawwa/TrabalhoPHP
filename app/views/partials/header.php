<?php
session_start();
$current = basename($_SERVER['PHP_SELF']);
?>
<header class="navbar">
    <div class="container">
        <h1 class="logo"><a href="/">SGA</a></h1>
        <nav>
            <input type="checkbox" id="menu-toggle">
            <label for="menu-toggle" class="hamburger"><i class="fas fa-bars"></i></label>
            <ul class="nav-links">
                <li><a href="/home" class="<?= $current == 'home.php' ? 'active' : '' ?>">Home</a></li>
                <li><a href="/cursos" class="<?= $current == 'cursos.php' ? 'active' : '' ?>">Cursos</a></li>
                <li><a href="/sobre" class="<?= $current == 'sobre.php' ? 'active' : '' ?>">Sobre</a></li>
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <li><a href="/painel">Painel</a></li>
                    <li><a href="/logout">Logout</a></li>
                <?php else: ?>
                    <li><a href="/login">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
