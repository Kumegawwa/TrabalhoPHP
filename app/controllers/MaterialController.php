<?php

class MaterialController extends BaseController {

    public function create(int $curso_id) {
        $this->checkProfessor();
        $cursoModel = new Curso();
        $curso = $cursoModel->findById($this->pdo, $curso_id);
        if (!$curso || $curso['professor_id'] != $_SESSION['usuario_id']) {
            $_SESSION['error_message'] = "Acesso negado.";
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        // Busca alunos para o dropdown de atribuição
        $inscricaoModel = new Inscricao();
        $alunos_inscritos = $inscricaoModel->findUsersByCourse($this->pdo, $curso_id);

        $viewData = [
            'titulo_pagina' => 'Adicionar Material/Atividade', 
            'action' => BASE_URL . '/materiais/store', 
            'material' => null, 
            'curso_id' => $curso_id, 
            'is_edit' => false,
            'alunos_inscritos' => $alunos_inscritos
        ];
        require __DIR__ . '/../views/materiais/form.php';
    }

    public function store() {
        $this->checkProfessor();
        $material = new Material();
        $material->curso_id = $_POST['curso_id'];
        $material->titulo = $_POST['titulo'];
        $material->conteudo = $_POST['conteudo'];
        $material->tipo = $_POST['tipo'];
        $material->aluno_id = $_POST['aluno_id'] ?? null; // Pega o aluno_id do form
        
        if ($material->create($this->pdo)) {
            $_SESSION['success_message'] = 'Material adicionado com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao adicionar material.';
        }
        header("Location: " . BASE_URL . "/cursos/show/{$_POST['curso_id']}");
        exit;
    }
    
    public function edit(int $id) {
        $this->checkProfessor();
        $materialModel = new Material();
        $material = $materialModel->findById($this->pdo, $id);

        if (!$material) { http_response_code(404); echo "Material não encontrado."; exit; }

        $cursoModel = new Curso();
        $curso = $cursoModel->findById($this->pdo, $material['curso_id']);

        if (!$curso || $curso['professor_id'] != $_SESSION['usuario_id']) {
            $_SESSION['error_message'] = 'Você não tem permissão para editar este material.';
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        // Busca alunos para o dropdown de atribuição
        $inscricaoModel = new Inscricao();
        $alunos_inscritos = $inscricaoModel->findUsersByCourse($this->pdo, $material['curso_id']);

        $viewData = [
            'titulo_pagina' => 'Editar Material', 
            'action' => BASE_URL . '/materiais/update/' . $id, 
            'material' => $material, 
            'curso_id' => $material['curso_id'], 
            'is_edit' => true,
            'alunos_inscritos' => $alunos_inscritos
        ];
        require __DIR__ . '/../views/materiais/form.php';
    }

    public function update(int $id) {
        $this->checkProfessor();
        $materialModel = new Material();
        $material = $materialModel->findById($this->pdo, $id);

        if (!$material) { http_response_code(404); echo "Material não encontrado."; exit; }

        $cursoModel = new Curso();
        $curso = $cursoModel->findById($this->pdo, $material['curso_id']);

        if (!$curso || $curso['professor_id'] != $_SESSION['usuario_id']) {
            $_SESSION['error_message'] = 'Você não tem permissão para editar este material.';
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        $materialToUpdate = new Material();
        $materialToUpdate->id = $id;
        $materialToUpdate->titulo = $_POST['titulo'];
        $materialToUpdate->conteudo = $_POST['conteudo'];
        $materialToUpdate->tipo = $_POST['tipo'];
        $materialToUpdate->aluno_id = $_POST['aluno_id'] ?? null; // Pega o aluno_id do form
        
        if ($materialToUpdate->update($this->pdo)) {
            $_SESSION['success_message'] = 'Material atualizado com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao atualizar material.';
        }
        header("Location: " . BASE_URL . "/cursos/show/{$material['curso_id']}");
        exit;
    }

    public function delete(int $id) {
        $this->checkProfessor();
        $materialModel = new Material();
        $material = $materialModel->findById($this->pdo, $id);

        if (!$material) { http_response_code(404); echo "Material não encontrado."; exit; }
        
        $cursoModel = new Curso();
        $curso = $cursoModel->findById($this->pdo, $material['curso_id']);

        if (!$curso || $curso['professor_id'] != $_SESSION['usuario_id']) {
            $_SESSION['error_message'] = 'Você não tem permissão para excluir este material.';
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        
        if ($materialModel->delete($this->pdo, $id)) {
            $_SESSION['success_message'] = 'Material excluído com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao excluir material.';
        }

        header("Location: " . BASE_URL . "/cursos/show/{$material['curso_id']}");
        exit;
    }
}
