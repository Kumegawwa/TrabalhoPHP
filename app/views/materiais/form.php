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
                        <option value="material" <?= (isset($viewData['material']['tipo']) && $viewData['material']['tipo'] == 'material') ? 'selected' : '' ?>>Material de Apoio</option>
                        <option value="atividade" <?= (isset($viewData['material']['tipo']) && $viewData['material']['tipo'] == 'atividade') ? 'selected' : '' ?>>Atividade Avaliativa</option>
                    </select>
                </div>

                <!-- CAMPO DE ATRIBUIÇÃO COM CHECKBOXES -->
                <div class="form-group">
                    <label>Atribuir Para</label>
                    <div class="atribuicao-container">
                        <div class="atribuicao-item select-all-item">
                            <input type="checkbox" id="select_all_alunos">
                            <label for="select_all_alunos"><strong>Selecionar Todos / Limpar Seleção</strong></label>
                        </div>
                        <small class="text-secondary d-block mb-3" style="padding: 0 0.5rem;">Se nenhum aluno for selecionado, a atividade será visível para toda a turma.</small>
                        
                        <?php if (empty($viewData['alunos_inscritos'])): ?>
                            <p class="text-secondary" style="padding: 0 0.5rem;">Não há alunos inscritos para atribuição individual.</p>
                        <?php else: ?>
                            <?php foreach ($viewData['alunos_inscritos'] as $aluno): ?>
                                <div class="atribuicao-item">
                                    <input type="checkbox" name="alunos_atribuidos[]" id="aluno_<?= $aluno['id'] ?>" value="<?= $aluno['id'] ?>" 
                                        <?= in_array($aluno['id'], $viewData['alunos_atribuidos']) ? 'checked' : '' ?>>
                                    <label for="aluno_<?= $aluno['id'] ?>"><?= htmlspecialchars($aluno['nome']) ?></label>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="d-flex" style="gap: 1rem;">
                    <button type="submit" class="btn btn-primary"><?= $viewData['is_edit'] ? 'Salvar Alterações' : 'Postar Material' ?></button>
                    <a href="<?= BASE_URL ?>/cursos/show/<?= $viewData['curso_id'] ?>" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script para o checkbox "Selecionar Todos" -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select_all_alunos');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function(e) {
            document.querySelectorAll('input[name="alunos_atribuidos[]"]').forEach(function(checkbox) {
                checkbox.checked = e.target.checked;
            });
        });
    }
});
</script>

<!-- CSS para o container de checkboxes (pode ir para o style.css) -->
<style>
.atribuicao-container { border: 1px solid var(--border-color); border-radius: var(--border-radius); padding: 0.5rem; max-height: 250px; overflow-y: auto; }
.atribuicao-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem; border-radius: 6px; }
.atribuicao-item:hover { background-color: rgba(255, 255, 255, 0.03); }
.atribuicao-item.select-all-item { border-bottom: 1px solid var(--border-color); margin-bottom: 0.5rem; }
.atribuicao-item input[type="checkbox"] { width: 1.1rem; height: 1.1rem; flex-shrink: 0; }
.atribuicao-item label { margin: 0; font-weight: 400; cursor: pointer; }
.d-block { display: block; }
</style>

<?php include __DIR__ . '/../partials/footer.php'; ?>
