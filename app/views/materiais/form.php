<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="container">
    <h1>Adicionar Material/Atividade ao Curso</h1>
    <form method="POST" action="<?= BASE_URL ?>/materiais/store">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generateCsrfToken()) ?>">
        <!-- $curso_id deve ser passado pelo controller para esta view -->
        <input type="hidden" name="curso_id" value="<?= htmlspecialchars($curso_id) ?>">
        
        <div class="form-group">
            <label for="titulo">Título do Material/Atividade</label>
            <input type="text" name="titulo" id="titulo" class="input" placeholder="Título" required>
        </div>
        <div class="form-group">
            <label for="conteudo">Conteúdo</label>
            <textarea name="conteudo" id="conteudo" class="textarea" placeholder="Conteúdo" required></textarea>
        </div>
        <div class="form-group">
            <label for="tipo">Tipo</label>
            <select name="tipo" id="tipo" class="select">
                <option value="material">Material</option>
                <option value="atividade">Atividade</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Postar</button>
    </form>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>