<?php include __DIR__ . '/../partials/header.php'; ?>
<main class="container">
    <div class="card" style="max-width: 400px; margin: 2rem auto;">
        <div class="card-header">Recuperar Senha</div>
        <div class="card-body">
            <p>Informe seu CPF e data de nascimento para redefinir sua senha.</p>
            <form action="<?= BASE_URL ?>/recuperar-senha" method="POST">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <div class="form-group">
                    <label class="label" for="cpf">CPF</label>
                    <input class="input" type="text" name="cpf" id="cpf" placeholder="123.456.789-00" required>
                </div>
                 <div class="form-group">
                    <label class="label" for="data_nascimento">Data de Nascimento</label>
                    <input class="input" type="date" name="data_nascimento" id="data_nascimento" required>
                </div>
                <div class="form-group">
                    <label class="label" for="nova_senha">Nova Senha</label>
                    <input class="input" type="password" name="nova_senha" id="nova_senha" required>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary w-100" type="submit">Redefinir Senha</button>
                </div>
            </form>
        </div>
    </div>
</main>
<?php include __DIR__ . '/../partials/footer.php'; ?>