<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><?= htmlspecialchars($viewData['titulo']) ?></h1>
    <?php if (isset($_SESSION['perfil']) && $_SESSION['perfil'] === 'professor'): ?>
        <a href="<?= BASE_URL ?>/cursos/create" class="btn btn-primary"><i class="fas fa-plus"></i> Criar Novo Curso</a>
    <?php endif; ?>
</div>

<?php if (isset($_SESSION['perfil']) && $_SESSION['perfil'] === 'aluno'): ?>
<div class="card mb-4">
    <div class="card-header">
        Entrar em uma Nova Turma
    </div>
    <div class="card-body">
        <form action="<?= BASE_URL ?>/cursos/join" method="POST" class="d-flex gap-2">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <input type="text" name="codigo_turma" class="input" placeholder="Digite o código da turma" required style="flex-grow: 1; text-transform: uppercase;">
            <button type="submit" class="btn btn-secondary">Entrar na Turma</button>
        </form>
    </div>
</div>
<?php endif; ?>


<div class="row">
    <?php if (empty($viewData['cursos'])): ?>
        <div class="col-12">
            <div class="card text-center" style="padding: 3rem; border-style: dashed;">
                <div class="card-body">
                    <i class="fas fa-ghost fa-3x" style="color: var(--text-secondary); margin-bottom: 1rem;"></i>
                    <h3 style="color: var(--text-primary);">Tudo quieto por aqui...</h3>
                    <p>
                        <?php if (isset($_SESSION['perfil']) && $_SESSION['perfil'] === 'professor'): ?>
                            Parece que você ainda não criou nenhum curso. Que tal começar agora clicando no botão "Criar Novo Curso"?
                        <?php else: ?>
                            Você ainda não está inscrito em nenhum curso. Utilize o código da turma fornecido pelo seu professor para começar.
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
                        <small class="text-muted">Professor: <?= htmlspecialchars($curso['professor_nome']) ?></small>
                    </div>
                    <div class="card-footer">
                        <a href="<?= BASE_URL ?>/cursos/show/<?= $curso['id'] ?>" class="btn btn-primary" style="flex-grow: 1;">Acessar</a>
                        
                        <?php if (isset($_SESSION['perfil']) && $_SESSION['perfil'] === 'professor'): ?>
                            <a href="<?= BASE_URL ?>/cursos/edit/<?= $curso['id'] ?>" class="btn btn-secondary" title="Editar Turma"><i class="fas fa-pen"></i></a>
                            <form action="<?= BASE_URL ?>/cursos/delete/<?= $curso['id'] ?>" method="POST" onsubmit="return confirm('ATENÇÃO: Excluir uma turma é uma ação permanente e removerá todos os seus materiais e inscrições de alunos. Deseja continuar?');">
                                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                                <button type="submit" class="btn btn-danger" title="Excluir Turma"><i class="fas fa-trash"></i></button>
                            </form>
                        <?php elseif (isset($_SESSION['perfil']) && $_SESSION['perfil'] === 'aluno'): ?>
                            <form action="<?= BASE_URL ?>/cursos/leave/<?= $curso['id'] ?>" method="POST" onsubmit="return confirm('Tem certeza que deseja sair desta turma?');">
                                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                                <button type="submit" class="btn btn-danger" title="Sair da Turma"><i class="fas fa-sign-out-alt"></i></button>
                            </form>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>