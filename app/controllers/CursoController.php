<?php
class CursoController {
    public function index() {
        global $pdo;
        $cursoModel = new Curso();
        $cursos = $cursoModel->getAll($pdo);
        require 'app/views/cursos/index.php';
    }

    public function create() {
        require 'app/views/cursos/form.php';
    }

    public function store() {
        global $pdo;
        $curso = new Curso();
        $curso->titulo = $_POST['titulo'];
        $curso->descricao = $_POST['descricao'];
        $curso->professor_id = $_SESSION['usuario_id'];
        $curso->create($pdo);
        header('Location: /cursos');
    }
}
