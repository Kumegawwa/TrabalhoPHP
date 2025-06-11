<?php
class SiteController {
    public function home() {
        // Redireciona para a lista de cursos se já estiver logado
        if (isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL . '/cursos');
            exit;
        }
        require __DIR__ . '/../views/site/home.php';
    }

    public function sobre() {
        require __DIR__ . '/../views/site/sobre.php';
    }

    public function listaCursosPublicos() {
        global $pdo;
        $cursoModel = new Curso();
        $cursos = $cursoModel->getAll($pdo);
        require __DIR__ . '/../views/site/lista_cursos.php';
    }
}