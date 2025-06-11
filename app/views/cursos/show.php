<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1><?= htmlspecialchars($curso['titulo']) ?></h1>
        <p class="lead"><?= htmlspecialchars($curso['descricao']) ?></p>
        <small>Professor: <?= htmlspecialchars($curso['professor_nome']) ?></small>
    </div>
    <div>
        <a href="<?= BASE_URL ?>/cursos" class="btn btn-secondary">Voltar aos Cursos</a>
    </div>
</div>

<hr>

<?php 
$podeVerConteudo = ($_SESSION['perfil'] === 'professor' && $curso['professor_id'] == $_SESSION['usuario_id']) || ($_SESSION['perfil'] === 'aluno' && $alunoInscrito);
?>

<?php if ($podeVerConteudo): ?>
    <div class="d-flex justify-content-between align-items-center my-4">
        <h2>Materiais e Atividades</h2>
        <?php if ($_SESSION['perfil'] === 'professor' && $curso['professor_id'] == $_SESSION['usuario_id']): ?>
            <a href="<?= BASE_URL ?>/materiais/create/<?= $curso['id'] ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Adicionar Material</a>
        <?php endif; ?>
    </div>

    <?php if (empty($materiais)): ?>
        <p>Nenhum material postado neste curso ainda.</p>
    <?php else: ?>
        <?php foreach ($materiais as $material): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($material['titulo']) ?> (<?= ucfirst($material['tipo']) ?>)</h5>
                    <p class="card-text"><?= nl2br(htmlspecialchars($material['conteudo'])) ?></p>
                    <small class="text-muted">Postado em: <?= date('d/m/Y H:i', strtotime($material['data_postagem'])) ?></small>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

<?php elseif ($_SESSION['perfil'] === 'aluno' && !$alunoInscrito): ?>
    <div class="alert alert-warning">
        Você precisa se inscrever neste curso para ver os materiais. 
        <a href="<?= BASE_URL ?>/cursos/enroll/<?= $curso['id'] ?>" class="btn btn-success">Inscrever-se Agora</a>
    </div>
<?php else: ?>
     <div class="alert alert-error">Você não tem permissão para ver o conteúdo deste curso.</div>
<?php endif; ?>

<?php include __DIR__ . '/../partials/footer.php'; ?>