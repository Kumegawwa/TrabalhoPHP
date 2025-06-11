<?php include __DIR__ . '/../partials/header.php'; ?>

<section class="hero-section">
    <h1>Aprender e Ensinar, Sem Limites.</h1>
    <p class="lead">Uma plataforma moderna e intuitiva para conectar professores e alunos. Crie, gerencie e participe de turmas com facilidade.</p>
    <a href="<?= BASE_URL ?>/register" class="btn btn-primary btn-lg" style="font-size: 1.1rem; padding: 0.8rem 2rem;">Comece Agora, É Grátis</a>
</section>

<hr>

<section class="features-section py-5">
    <div class="row">
        <div class="col-md-4">
            <div class="feature-item">
                <div class="icon"><i class="fas fa-chalkboard-teacher"></i></div>
                <h3>Para Professores</h3>
                <p>Crie cursos, poste materiais, gerencie suas turmas e acompanhe o progresso de seus alunos de forma centralizada.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-item">
                <div class="icon"><i class="fas fa-user-graduate"></i></div>
                <h3>Para Alunos</h3>
                <p>Acesse o material de estudo, participe de atividades e interaja com sua turma usando um código de acesso simples e seguro.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-item">
                <div class="icon"><i class="fas fa-mobile-alt"></i></div>
                <h3>Design Moderno</h3>
                <p>Interface limpa, rápida e responsiva, que se adapta perfeitamente a computadores, tablets e celulares.</p>
            </div>
        </div>
    </div>
</section>

<section class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="stat-item">
                    <span class="number"><?= $viewData['total_cursos'] ?>+</span>
                    <span class="label">Cursos Criados</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-item">
                    <span class="number"><?= $viewData['total_alunos'] ?>+</span>
                    <span class="label">Alunos Inscritos</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-item">
                    <span class="number"><?= $viewData['total_professores'] ?>+</span>
                    <span class="label">Professores Ativos</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="latest-courses-section py-5">
    <div class="text-center mb-5">
        <h2>Explore Nossos Cursos Mais Recentes</h2>
        <p>Veja o que os professores da nossa comunidade estão criando.</p>
    </div>
    <div class="row">
        <?php if (empty($viewData['cursos_recentes'])): ?>
            <p class="text-center">Nenhum curso para exibir no momento.</p>
        <?php else: ?>
            <?php foreach ($viewData['cursos_recentes'] as $curso): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header"><?= htmlspecialchars($curso['titulo']) ?></div>
                        <div class="card-body">
                            <p><?= htmlspecialchars(substr($curso['descricao'], 0, 100)) . '...' ?></p>
                            <small class="text-muted">Professor: <?= htmlspecialchars($curso['professor_nome']) ?></small>
                        </div>
                        <div class="card-footer">
                            <a href="<?= BASE_URL ?>/login" class="btn btn-secondary w-100">Ver Curso</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<section class="cta-section">
    <h2>Pronto para transformar sua forma de ensinar e aprender?</h2>
    <p class="lead">Junte-se à nossa comunidade hoje mesmo.</p>
    <a href="<?= BASE_URL ?>/register" class="btn btn-primary btn-lg" style="font-size: 1.1rem; padding: 0.8rem 2rem;">Criar Minha Conta Gratuita</a>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>