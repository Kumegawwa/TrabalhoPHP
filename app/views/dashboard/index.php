<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><?= htmlspecialchars($viewData['titulo']) ?></h1>
    <?php if ($_SESSION['perfil'] === 'professor'): ?>
        <a href="<?= BASE_URL ?>/cursos/create" class="btn btn-primary"><i class="fas fa-plus"></i> Criar Novo Curso</a>
    <?php endif; ?>
</div>

<?php if ($_SESSION['perfil'] === 'aluno'): ?>
<div class="card mb-4">
    <div class="card-body">
        <form action="<?= BASE_URL ?>/cursos/join" method="POST" class="d-flex gap-2">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <input type="text" name="codigo_turma" class="input" placeholder="Digite o código da turma" required style="flex-grow: 1;">
            <button type="submit" class="btn btn-secondary">Entrar na Turma</button>
        </form>
    </div>
</div>
<?php endif; ?>


<div class="row">
    <?php if (empty($viewData['cursos'])): ?>
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <p>
                        <?php if ($_SESSION['perfil'] === 'professor'): ?>
                            Você ainda não criou nenhum curso.
                        <?php else: ?>
                            Você ainda não está inscrito em nenhum curso.
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($viewData['cursos'] as $curso): ?>
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
                        <a href="<?= BASE_URL ?>/cursos/show/<?= $curso['id'] ?>" class="btn btn-primary">Acessar Curso</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>