<?php

class DashboardController {
    /**
     * Exibe a página principal para usuários logados.
     * Mostra os cursos do professor ou os cursos em que o aluno está inscrito.
     */
    public function index() {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $usuarioModel = new Usuario();
        $cursoModel = new Curso();
        $viewData = [];

        if ($_SESSION['perfil'] === 'professor') {
            // Busca apenas os cursos criados pelo professor logado
            $viewData['cursos'] = $cursoModel->findByProfessorId($pdo, $_SESSION['usuario_id']);
            $viewData['titulo'] = 'Meus Cursos Criados';
        } else { // Aluno
            // Busca os cursos em que o aluno está inscrito
            $viewData['cursos'] = $usuarioModel->getCursosInscritos($pdo, $_SESSION['usuario_id']);
            $viewData['titulo'] = 'Meus Cursos Inscritos';
        }
        
        require __DIR__ . '/../views/dashboard/index.php';
    }
}