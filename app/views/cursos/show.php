<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1><?= htmlspecialchars($curso['titulo']) ?></h1>
        <p class="lead"><?= htmlspecialchars($curso['descricao']) ?></p>
        <small>Professor: <?= htmlspecialchars($curso['professor_nome']) ?></small>
    </div>
    <div>
        <a href="<?= BASE_URL ?>/dashboard" class="btn btn-secondary">Voltar ao Dashboard</a>
    </div>
</div>

<?php
// Condição para o professor, dono do curso, ver o código da turma
if (isset($_SESSION['perfil']) && $_SESSION['perfil'] === 'professor' && $curso['professor_id'] == $_SESSION['usuario_id']):
?>
<div class="alert alert-success">
    <strong>Código da Turma:</strong> 
    <span style="font-family: monospace; font-size: 1.2rem; font-weight: bold; letter-spacing: 2px;"><?= htmlspecialchars($curso['codigo_turma']) ?></span>
    <br>
    <small>Compartilhe este código com seus alunos para que eles possam entrar na turma.</small>
</div>
<?php endif; ?>

<hr style="border-color: var(--border-color); margin: 2rem 0;">

<?php
// Variável para simplificar a lógica de permissão de visualização do conteúdo
$podeVerConteudo = (isset($_SESSION['perfil']) && $_SESSION['perfil'] === 'professor' && $curso['professor_id'] == $_SESSION['usuario_id']) 
                  || (isset($_SESSION['perfil']) && $_SESSION['perfil'] === 'aluno' && $alunoInscrito);
?>

<?php if ($podeVerConteudo): ?>
    <div class="d-flex justify-content-between align-items-center my-4">
        <h2>Materiais e Atividades</h2>
        <?php if ($_SESSION['perfil'] === 'professor' && $curso['professor_id'] == $_SESSION['usuario_id']): ?>
            <a href="<?= BASE_URL ?>/materiais/create/<?= $curso['id'] ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Adicionar Material</a>
        <?php endif; ?>
    </div>

    <?php if (empty($materiais)): ?>
        <div class="card">
            <div class="card-body text-center">
                <p>Nenhum material postado neste curso ainda.</p>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($materiais as $material): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($material['titulo']) ?> (<?= ucfirst(htmlspecialchars($material['tipo'])) ?>)</h5>
                    <p class="card-text" style="color: var(--text-primary);"><?= nl2br(htmlspecialchars($material['conteudo'])) ?></p>
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

<?php include __DIR__ . '/../partials/footer.php'; ?>