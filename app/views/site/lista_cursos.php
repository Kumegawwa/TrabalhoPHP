<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="text-center mb-4">
    <h1>Cursos Disponíveis</h1>
    <p>Explore os cursos abertos em nossa plataforma.</p>
</div>

<div class="row">
    <?php if (empty($cursos)): ?>
        <p class="text-center">Nenhum curso disponível no momento.</p>
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
                        <a href="<?= BASE_URL ?>/login" class="btn btn-secondary">Faça login para se inscrever</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>