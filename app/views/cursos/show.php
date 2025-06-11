<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1><?= htmlspecialchars($curso['titulo']) ?></h1>
        <p class="lead"><?= htmlspecialchars($curso['descricao']) ?></p>
        <small class="text-muted">Professor: <?= htmlspecialchars($curso['professor_nome']) ?></small>
    </div>
    <div>
        <a href="<?= BASE_URL ?>/dashboard" class="btn btn-secondary">Voltar ao Dashboard</a>
    </div>
</div>

<?php
if (isset($_SESSION['perfil']) && $_SESSION['perfil'] === 'professor' && $curso['professor_id'] == $_SESSION['usuario_id']):
?>
<div class="alert alert-success">
    <strong>Código da Turma:</strong> 
    <span style="font-family: monospace; font-size: 1.2rem; font-weight: bold; letter-spacing: 2px;"><?= htmlspecialchars($curso['codigo_turma']) ?></span>
    <br>
    <small>Compartilhe este código com seus alunos para que eles possam entrar na turma.</small>
</div>
<?php endif; ?>

<hr>

<?php
$podeVerConteudo = (isset($_SESSION['perfil']) && $_SESSION['perfil'] === 'professor' && $curso['professor_id'] == $_SESSION['usuario_id']) 
                  || (isset($_SESSION['perfil']) && $_SESSION['perfil'] === 'aluno' && $alunoInscrito);
?>

<?php if ($podeVerConteudo): ?>
    <!-- Seção de Materiais -->
    <div class="d-flex justify-content-between align-items-center my-4">
        <h2>Materiais e Atividades</h2>
        <?php if ($_SESSION['perfil'] === 'professor' && $curso['professor_id'] == $_SESSION['usuario_id']): ?>
            <a href="<?= BASE_URL ?>/materiais/create/<?= $curso['id'] ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Adicionar Material</a>
        <?php endif; ?>
    </div>

    <?php if (empty($materiais)): ?>
        <div class="card"><div class="card-body text-center"><p>Nenhum material postado neste curso ainda.</p></div></div>
    <?php else: ?>
        <?php foreach ($materiais as $material): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title"><?= htmlspecialchars($material['titulo']) ?> (<?= ucfirst(htmlspecialchars($material['tipo'])) ?>)</h5>
                            <!-- MOSTRA SE A ATIVIDADE É INDIVIDUAL (Visível para o professor) -->
                            <?php if ($_SESSION['perfil'] === 'professor' && $material['atribuicoes_count'] > 0): ?>
                                <span class="badge badge-individual">Atribuição Específica (<?= $material['atribuicoes_count'] ?> aluno(s))</span>
                            <?php endif; ?>
                        </div>
                        <?php if ($_SESSION['perfil'] === 'professor' && $curso['professor_id'] == $_SESSION['usuario_id']): ?>
                            <div class="material-actions d-flex gap-2">
                                <a href="<?= BASE_URL ?>/materiais/edit/<?= $material['id'] ?>" class="btn btn-sm btn-secondary" title="Editar"><i class="fas fa-pen"></i></a>
                                <form action="<?= BASE_URL ?>/materiais/delete/<?= $material['id'] ?>" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este material? Esta ação não pode ser desfeita.')">
                                    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" title="Excluir"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                    <p class="card-text" style="color: var(--text-primary); margin-top: 1rem;"><?= nl2br(htmlspecialchars($material['conteudo'])) ?></p>
                    <small class="text-muted">Postado em: <?= date('d/m/Y H:i', strtotime($material['data_postagem'])) ?></small>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

<?php elseif (isset($_SESSION['perfil']) && $_SESSION['perfil'] === 'aluno' && !$alunoInscrito): ?>
    <div class="alert alert-warning text-center">
        <h4>Você precisa se inscrever neste curso para ver os materiais.</h4> 
        <p>Use o código da turma fornecido pelo seu professor na sua Dashboard para entrar.</p>
    </div>
<?php else: ?>
     <div class="alert alert-error text-center">
        <h4>Acesso Negado</h4>
        <p>Você não tem permissão para ver o conteúdo deste curso.</p>
    </div>
<?php endif; ?>


<!-- SEÇÃO DE ALUNOS INSCRITOS (Visível apenas para o professor) -->
<?php if (isset($_SESSION['perfil']) && $_SESSION['perfil'] === 'professor' && $curso['professor_id'] == $_SESSION['usuario_id']): ?>
<section class="alunos-inscritos mt-5">
    <hr>
    <h2 class="my-4">Alunos Inscritos na Turma</h2>
    <div class="card">
        <div class="card-body">
            <?php if (empty($alunos_inscritos)): ?>
                <p class="text-center text-secondary">Ainda não há alunos inscritos nesta turma.</p>
            <?php else: ?>
                <ul class="list-group">
                    <?php foreach ($alunos_inscritos as $aluno): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-user mr-2 text-secondary"></i>
                                <strong><?= htmlspecialchars($aluno['nome']) ?></strong>
                                <br>
                                <small class="text-secondary"><?= htmlspecialchars($aluno['email']) ?></small>
                            </div>
                            <form action="<?= BASE_URL ?>/cursos/<?= $curso['id'] ?>/remove-aluno/<?= $aluno['id'] ?>" method="POST" onsubmit="return confirm('Tem certeza que deseja remover este aluno da turma?');">
                                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                                <button type="submit" class="btn btn-sm btn-danger" title="Remover Aluno"><i class="fas fa-user-times"></i> Remover</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php include __DIR__ . '/../partials/footer.php'; ?>

<!-- ESTILOS LOCAIS (Você pode mover para o seu style.css se preferir) -->
<style>
.list-group { list-style: none; padding: 0; }
.list-group-item { 
    background-color: var(--surface-color); 
    border-bottom: 1px solid var(--border-color); 
    padding: 1rem 1.5rem;
}
.list-group-item:last-child { border-bottom: none; }
.mr-2 { margin-right: 0.5rem; }
.mt-5 { margin-top: 3rem !important; }
.badge { display: inline-block; padding: .35em .65em; font-size: .75em; font-weight: 700; line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; }
.badge-individual { color: #fff; background-color: var(--primary-accent); opacity: 0.8; }
</style>
