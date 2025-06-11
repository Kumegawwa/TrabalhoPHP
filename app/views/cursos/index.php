<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Meus Cursos</h1>
    <?php if ($_SESSION['perfil'] === 'professor'): ?>
        <a href="<?= BASE_URL ?>/cursos/create" class="btn btn-primary"><i class="fas fa-plus"></i> Criar Novo Curso</a>
    <?php endif; ?>
</div>

<div class="row">
    <?php if (empty($cursos)): ?>
        <p>Nenhum curso encontrado.</p>
    <?php else: ?>
        <?php foreach ($cursos as $curso): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <?= htmlspecialchars($curso['titulo']) ?>
                    </div>
                    <div class="card-body">
                        <p><?= htmlspecialchars($curso['descricao']) ?></p>
                        <small>Professor: <?= htmlspecialchars($curso['professor_nome']) ?></small>
                    </div>
                    <div class="card-footer">
                        <a href="<?= BASE_URL ?>/cursos/show/<?= $curso['id'] ?>" class="btn btn-secondary">Ver Curso</a>
                        <?php if ($_SESSION['perfil'] === 'professor' && $curso['professor_id'] == $_SESSION['usuario_id']): ?>
                             <a href="<?= BASE_URL ?>/cursos/edit/<?= $curso['id'] ?>" class="btn btn-info">Editar</a>
                        <?php elseif ($_SESSION['perfil'] === 'aluno'): ?>
                             <?php if (!in_array($curso['id'], $cursosInscritos)): ?>
                                <a href="<?= BASE_URL ?>/cursos/enroll/<?= $curso['id'] ?>" class="btn btn-success">Inscrever-se</a>
                             <?php else: ?>
                                <span class="btn btn-success disabled">Inscrito</span>
                             <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>