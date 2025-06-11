<?php

class SiteController extends BaseController {

    public function home() {
        // Redireciona para a dashboard se o usuário já estiver logado
        if (isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        require __DIR__ . '/../views/site/home.php';
    }

    public function sobre() {
        require __DIR__ . '/../views/site/sobre.php';
    }

    public function listaCursosPublicos() {
        $cursoModel = new Curso();
        $cursos = $cursoModel->getAll($this->pdo);
        require __DIR__ . '/../views/site/lista_cursos.php';
    }
}