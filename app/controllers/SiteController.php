<?php
class SiteController {
    public function home() {
        require 'app/views/site/home.php';
    }

    public function sobre() {
        require 'app/views/site/sobre.php';
    }

    public function listaCursosPublicos() {
        global $pdo;
        $cursoModel = new Curso();
        $cursos = $cursoModel->getAll($pdo);
        require 'app/views/site/lista_cursos.php';
    }
}
