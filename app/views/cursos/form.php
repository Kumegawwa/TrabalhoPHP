<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="container">
    <h1><?= $viewData['titulo_pagina'] ?></h1>
    <form method="POST" action="<?= $viewData['action'] ?>">
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
        
        <?php if (isset($viewData['curso'])): // Para o método POST de deleção ?>
        <input type="hidden" name="_method" value="POST">
        <?php endif; ?>

        <div class="form-group">
            <label for="titulo">Título do Curso</label>
            <input type="text" name="titulo" id="titulo" class="input" placeholder="Título do Curso" required value="<?= htmlspecialchars($viewData['curso']['titulo'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="descricao">Descrição</label>
            <textarea name="descricao" id="descricao" class="textarea" placeholder="Descrição do Curso" required><?= htmlspecialchars($viewData['curso']['descricao'] ?? '') ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Salvar Curso</button>
        <a href="<?= BASE_URL ?>/cursos" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>