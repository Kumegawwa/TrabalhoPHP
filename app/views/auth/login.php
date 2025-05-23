<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Inclua isso no topo se ainda não estiver lá ou no index.php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/TrabalhoPHP'); // Ajuste se necessário
}

// Inclua seu helper CSRF se tiver um arquivo separado para ele
// require_once __DIR__ . '/../../../helpers/csrf.php';
// A função generateCsrfToken() deve estar disponível
if (function_exists('generateCsrfToken')) {
    $token = generateCsrfToken();
} else {
    // Fallback simples se a função não existir
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - SGA</title>
    <!-- Ajuste o caminho do CSS para usar BASE_URL ou caminho relativo correto -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <script defer src="<?= BASE_URL ?>/public/js/main.js"></script>
</head>
<body>

<?php 
// O path para os partials precisa ser ajustado com base na localização de login.php
// Se login.php está em /app/views/auth/login.php
include __DIR__ . '/../partials/header.php'; 
?>

<main class="container">
    <div class="card" style="max-width: 400px; margin: 2rem auto;">
        <div class="card-header">Login</div>
        <div class="card-body">
            <!-- A action do formulário deve apontar para a rota de processamento de login -->
            <form action="<?= BASE_URL ?>/login" method="POST" data-validate>
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($token) ?>">

                <div class="form-group">
                    <label class="label" for="email">Email</label>
                    <input class="input" type="email" name="email" id="email" required>
                </div>

                <div class="form-group">
                    <label class="label" for="senha">Senha</label>
                    <input class="input" type="password" name="senha" id="senha" required>
                </div>

                <div class="form-group">
                    <button class="btn btn-primary" type="submit">Entrar</button>
                </div>

                <p><a href="<?= BASE_URL ?>/recuperar">Esqueci minha senha</a></p>
            </form>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>

</body>
</html>