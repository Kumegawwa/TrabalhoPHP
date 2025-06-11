<?php

class DashboardController extends BaseController {
    
    public function index() {
        $this->checkAuth();

        $usuarioModel = new Usuario();
        $cursoModel = new Curso();
        $viewData = [];

        if ($_SESSION['perfil'] === 'professor') {
            $viewData['titulo'] = 'Meus Cursos Criados';
            $viewData['cursos'] = $cursoModel->findByProfessorId($this->pdo, $_SESSION['usuario_id']);
        } else {
            $viewData['titulo'] = 'Meus Cursos';
            $viewData['cursos'] = $usuarioModel->getCursosInscritos($this->pdo, $_SESSION['usuario_id']);
        }
        
        require __DIR__ . '/../views/dashboard/index.php';
    }
}