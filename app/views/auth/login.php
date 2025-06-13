<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('BASE_URL')) {
    define('BASE_URL', '/TrabalhoPHP');
}

if (function_exists('generateCsrfToken')) {
    $token = generateCsrfToken();
} else {

    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - SGA</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <script defer src="<?= BASE_URL ?>/public/js/main.js"></script>
</head>
<body>

<?php include __DIR__ . '/../partials/header.php'; ?>
<main class="container">
    <div class="card" style="max-width: 400px; margin: 2rem auto;">
        <div class="card-header">Login</div>
        <div class="card-body">
            <form action="<?= BASE_URL ?>/login" method="POST">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <div class="form-group">
                    <label class="label" for="email">Email</label>
                    <input class="input" type="email" name="email" id="email" required>
                </div>
                <div class="form-group">
                    <label class="label" for="senha">Senha</label>
                    <input class="input" type="password" name="senha" id="senha" required>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary w-100" type="submit">Entrar</button>
                </div>
                <p class="text-center"><a href="<?= BASE_URL ?>/recuperar-senha">Esqueci minha senha</a></p>
                <p class="text-center">Não tem uma conta? <a href="<?= BASE_URL ?>/register">Cadastre-se</a></p>
            </form>
        </div>
    </div>
</main>
<?php include __DIR__ . '/../partials/footer.php'; ?>

</body>
</html>