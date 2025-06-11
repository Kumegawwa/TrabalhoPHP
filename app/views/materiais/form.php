<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="container">
    <h1><?= $viewData['titulo_pagina'] ?></h1>
    <form method="POST" action="<?= $viewData['action'] ?>">
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
        <input type="hidden" name="curso_id" value="<?= htmlspecialchars($viewData['curso_id']) ?>">
        
        <div class="form-group">
            <label for="titulo">Título do Material/Atividade</label>
            <input type="text" name="titulo" id="titulo" class="input" placeholder="Título" required value="<?= htmlspecialchars($viewData['material']['titulo'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="conteudo">Conteúdo</label>
            <textarea name="conteudo" id="conteudo" class="textarea" placeholder="Conteúdo" required><?= htmlspecialchars($viewData['material']['conteudo'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label for="tipo">Tipo</label>
            <select name="tipo" id="tipo" class="select">
                <option value="material" <?= (isset($viewData['material']) && $viewData['material']['tipo'] == 'material') ? 'selected' : '' ?>>Material</option>
                <option value="atividade" <?= (isset($viewData['material']) && $viewData['material']['tipo'] == 'atividade') ? 'selected' : '' ?>>Atividade</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Postar</button>
         <a href="<?= BASE_URL ?>/cursos/show/<?= $viewData['curso_id'] ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>