<?php
class MaterialController {
    public function create($curso_id) {
        require 'app/views/materiais/form.php';
    }

    public function store() {
        global $pdo;
        $material = new Material();
        $material->curso_id = $_POST['curso_id'];
        $material->titulo = $_POST['titulo'];
        $material->conteudo = $_POST['conteudo'];
        $material->tipo = $_POST['tipo'];
        $material->create($pdo);
        header("Location: /cursos/show/{$_POST['curso_id']}");
    }
}
