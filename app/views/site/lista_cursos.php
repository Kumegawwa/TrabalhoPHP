<h1>Cursos Disponíveis</h1>
<ul>
    <?php foreach ($cursos as $curso): ?>
        <li><?= $curso['titulo'] ?> - <?= $curso['descricao'] ?></li>
    <?php endforeach; ?>
</ul>
