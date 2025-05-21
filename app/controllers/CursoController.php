<?php
class CursoController {
    public function index() {
        global $pdo;
        $cursoModel = new Curso();
        $cursos = $cursoModel->getAll($pdo);
        // Caminho da view agora relativo à raiz do projeto ou use __DIR__
        require __DIR__ . '/../views/cursos/index.php';
    }

    public function create() {
        // Verifica se o usuário é professor
        if (!isset($_SESSION['usuario_id']) || $_SESSION['perfil'] !== 'professor') {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        require __DIR__ . '/../views/cursos/form.php';
    }

    public function store() {
        // Verifica se o usuário é professor
        if (!isset($_SESSION['usuario_id']) || $_SESSION['perfil'] !== 'professor') {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        global $pdo;
        $curso = new Curso();
        $curso->titulo = $_POST['titulo'];
        $curso->descricao = $_POST['descricao'];
        $curso->professor_id = $_SESSION['usuario_id']; // Assumindo que o professor logado cria o curso
        $curso->create($pdo);
        header('Location: ' . BASE_URL . '/cursos'); // Use BASE_URL
        exit;
    }

    // Exemplo de método show que não existia antes
    public function show($id) {
        global $pdo;
        $cursoModel = new Curso(); // Supondo que você tenha um método findById no seu model
        $curso = $cursoModel->findById($pdo, $id); // Você precisará criar este método
        
        $materialModel = new Material(); // Supondo que você tenha getByCursoId no Material model
        $materiais = $materialModel->getByCursoId($pdo, $id); // Você precisará criar este método

        if (!$curso) {
            http_response_code(404);
            echo "Curso não encontrado";
            exit;
        }
        require __DIR__ . '/../views/cursos/show.php'; // Crie esta view
    }
}