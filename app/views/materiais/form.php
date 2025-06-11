<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container">
    <div class="card" style="max-width: 700px; margin: 2rem auto;">
        <div class="card-header">
            <h1><?= $viewData['is_edit'] ? 'Editar Material' : 'Adicionar Material ao Curso' ?></h1>
        </div>
        <div class="card-body">
            <form method="POST" action="<?= $viewData['action'] ?>">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                
                <?php if (!$viewData['is_edit']): ?>
                    <input type="hidden" name="curso_id" value="<?= htmlspecialchars($viewData['curso_id']) ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="titulo">Título do Material/Atividade</label>
                    <input type="text" name="titulo" id="titulo" class="input" placeholder="Ex: Lista de Exercícios 1" required value="<?= htmlspecialchars($viewData['material']['titulo'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="conteudo">Conteúdo/Descrição</label>
                    <textarea name="conteudo" id="conteudo" class="textarea" placeholder="Descreva o material ou cole o conteúdo aqui..." required rows="8"><?= htmlspecialchars($viewData['material']['conteudo'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label for="tipo">Tipo</label>
                    <select name="tipo" id="tipo" class="select">
                        <option value="material" <?= (isset($viewData['material']) && $viewData['material']['tipo'] == 'material') ? 'selected' : '' ?>>Material de Apoio</option>
                        <option value="atividade" <?= (isset($viewData['material']) && $viewData['material']['tipo'] == 'atividade') ? 'selected' : '' ?>>Atividade Avaliativa</option>
                    </select>
                </div>

                <div class="d-flex" style="gap: 1rem;">
                    <button type="submit" class="btn btn-primary"><?= $viewData['is_edit'] ? 'Salvar Alterações' : 'Postar Material' ?></button>
                    <a href="<?= BASE_URL ?>/cursos/show/<?= $viewData['curso_id'] ?>" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>