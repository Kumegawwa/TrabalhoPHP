<?php
class MaterialController {
    public function create($curso_id) {
        global $pdo;
        // Verifica se o usuário é o professor do curso
        $cursoModel = new Curso();
        $curso = $cursoModel->findById($pdo, $curso_id);
        if (!$curso || $curso['professor_id'] != $_SESSION['usuario_id']) {
            $_SESSION['error_message'] = "Acesso negado.";
            header('Location: ' . BASE_URL . '/cursos');
            exit;
        }

        $viewData = ['titulo_pagina' => 'Adicionar Material/Atividade', 'action' => BASE_URL . '/materiais/store', 'material' => null, 'curso_id' => $curso_id];
        require __DIR__ . '/../views/materiais/form.php';
    }

    public function store() {
        global $pdo;
        $material = new Material();
        $material->curso_id = $_POST['curso_id'];
        $material->titulo = $_POST['titulo'];
        $material->conteudo = $_POST['conteudo'];
        $material->tipo = $_POST['tipo'];
        
        if ($material->create($pdo)) {
            $_SESSION['success_message'] = 'Material adicionado com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao adicionar material.';
        }
        header("Location: " . BASE_URL . "/cursos/show/{$_POST['curso_id']}");
        exit;
    }
    
    // Implemente edit, update e delete de forma similar ao CursoController se necessário
}