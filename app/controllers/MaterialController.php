<?php
class MaterialController {
    public function create($curso_id) {
        // Verifica se o usuário é professor
        if (!isset($_SESSION['usuario_id']) || $_SESSION['perfil'] !== 'professor') {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        // Passa $curso_id para a view, para que o form.php saiba a qual curso o material pertence
        require __DIR__ . '/../views/materiais/form.php';
    }

    public function store() {
        // Verifica se o usuário é professor
        if (!isset($_SESSION['usuario_id']) || $_SESSION['perfil'] !== 'professor') {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        global $pdo;
        $material = new Material();
        $material->curso_id = $_POST['curso_id'];
        $material->titulo = $_POST['titulo'];
        $material->conteudo = $_POST['conteudo'];
        $material->tipo = $_POST['tipo'];
        $material->create($pdo);
        header("Location: " . BASE_URL . "/cursos/show/{$_POST['curso_id']}"); // Use BASE_URL
        exit;
    }
}