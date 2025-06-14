<?php include __DIR__ . '/../partials/header.php'; ?>
<main class="container">
    <div class="card" style="max-width: 500px; margin: 2rem auto;">
        <div class="card-header">Cadastro de Novo Usuário</div>
        <div class="card-body">
            <form action="<?= BASE_URL ?>/register" method="POST">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <div class="form-group">
                    <label class="label" for="nome">Nome Completo</label>
                    <input class="input" type="text" name="nome" id="nome" required>
                </div>
                <div class="form-group">
                    <label class="label" for="email">Email</label>
                    <input class="input" type="email" name="email" id="email" required>
                </div>
                 <div class="form-group">
                    <label class="label" for="cpf">CPF</label>
                    <input class="input" type="text" name="cpf" id="cpf" pattern="\d{11}" maxlength="11" placeholder="123.456.789-00" required title="Digite exatamente 11 números, sem pontos ou traços.">
                </div>
                 <div class="form-group">
                    <label class="label" for="data_nascimento">Data de Nascimento</label>
                    <input class="input" type="date" name="data_nascimento" id="data_nascimento" required>
                </div>
                <div class="form-group">
                    <label class="label" for="senha">Senha</label>
                    <input class="input" type="password" name="senha" id="senha" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$" title="A senha deve ter pelo menos 8 caracteres, incluindo letras e números." required>
                    
                </div>
                <div class="form-group">
                    <button class="btn btn-primary w-100" type="submit">Cadastrar</button>
                </div>
            </form>
        </div>
    </div>
</main>
<?php include __DIR__ . '/../partials/footer.php'; ?>