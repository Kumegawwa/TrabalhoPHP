<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="container">
    <h1>Criar Novo Curso</h1>
    <form method="POST" action="<?= BASE_URL ?>/cursos/store">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generateCsrfToken()) ?>">
        <div class="form-group">
            <label for="titulo">Título do Curso</label>
            <input type="text" name="titulo" id="titulo" class="input" placeholder="Título do Curso" required>
        </div>
        <div class="form-group">
            <label for="descricao">Descrição</label>
            <textarea name="descricao" id="descricao" class="textarea" placeholder="Descrição do Curso" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Salvar Curso</button>
    </form>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>