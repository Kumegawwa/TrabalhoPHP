<?php

class CursoController extends BaseController {

    /**
     * Exibe a lista pública de todos os cursos.
     */
    public function index() {
        $cursoModel = new Curso();
        $cursos = $cursoModel->getAll($this->pdo);
        require __DIR__ . '/../views/site/lista_cursos.php';
    }

    /**
     * Exibe o formulário para criar um novo curso.
     */
    public function create() {
        $this->checkProfessor();
        $viewData = ['titulo_pagina' => 'Criar Novo Curso', 'action' => BASE_URL . '/cursos/store', 'curso' => null, 'is_edit' => false];
        require __DIR__ . '/../views/cursos/form.php';
    }

    /**
     * Salva um novo curso no banco de dados.
     */
    public function store() {
        $this->checkProfessor();
        $curso = new Curso();
        $curso->titulo = $_POST['titulo'];
        $curso->descricao = $_POST['descricao'];
        $curso->professor_id = $_SESSION['usuario_id'];
        
        if ($curso->create($this->pdo)) {
            $_SESSION['success_message'] = "Curso criado com sucesso!";
        } else {
            $_SESSION['error_message'] = "Erro ao criar o curso.";
        }
        header('Location: ' . BASE_URL . '/dashboard');
        exit;
    }

    /**
     * Mostra detalhes de um curso específico e seus materiais.
     */
    public function show(int $id) {
        $this->checkAuth();
        $cursoModel = new Curso();
        $curso = $cursoModel->findById($this->pdo, $id);

        if (!$curso) { http_response_code(404); echo "Curso não encontrado"; exit; }
        
        $materiais = $cursoModel->getMateriais($this->pdo, $id);
        
        $alunoInscrito = false;
        if ($_SESSION['perfil'] === 'aluno') {
            $inscricaoModel = new Inscricao();
            if($inscricaoModel->findByUserAndCourse($this->pdo, $_SESSION['usuario_id'], $id)) {
                $alunoInscrito = true;
            }
        }
        
        require __DIR__ . '/../views/cursos/show.php';
    }

    /**
     * Exibe o formulário para editar um curso existente.
     */
    public function edit(int $id) {
        $this->checkProfessor();
        $cursoModel = new Curso();
        $curso = $cursoModel->findById($this->pdo, $id);
        
        if (!$curso || $curso['professor_id'] != $_SESSION['usuario_id']) {
            $_SESSION['error_message'] = "Acesso negado.";
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        $viewData = ['titulo_pagina' => 'Editar Curso', 'action' => BASE_URL . '/cursos/update/' . $id, 'curso' => $curso, 'is_edit' => true];
        require __DIR__ . '/../views/cursos/form.php';
    }

    /**
     * Atualiza um curso no banco de dados.
     */
    public function update(int $id) {
        $this->checkProfessor();
        $curso = new Curso();
        $curso->id = $id;
        $curso->titulo = $_POST['titulo'];
        $curso->descricao = $_POST['descricao'];
        $curso->professor_id = $_SESSION['usuario_id'];
        
        if ($curso->update($this->pdo)) {
            $_SESSION['success_message'] = "Curso atualizado com sucesso!";
        } else {
            $_SESSION['error_message'] = "Erro ao atualizar o curso ou permissão negada.";
        }
        header('Location: ' . BASE_URL . '/dashboard');
        exit;
    }
    
    /**
     * Permite que um PROFESSOR delete uma turma.
     */
    public function delete(int $id) {
        $this->checkProfessor();
        $cursoModel = new Curso();
        
        // Validação extra para garantir que o professor logado é o dono do curso
        $curso = $cursoModel->findById($this->pdo, $id);
        if (!$curso || $curso['professor_id'] != $_SESSION['usuario_id']) {
            $_SESSION['error_message'] = "Você não tem permissão para excluir este curso.";
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        if ($cursoModel->delete($this->pdo, $id, $_SESSION['usuario_id'])) {
             $_SESSION['success_message'] = "Turma deletada com sucesso!";
        } else {
             $_SESSION['error_message'] = "Erro ao deletar a turma.";
        }
        header('Location: ' . BASE_URL . '/dashboard');
        exit;
    }
    
    /**
     * Permite que um ALUNO se inscreva em uma turma usando um código.
     */
    public function joinByCode() {
        $this->checkAuth();
        if ($_SESSION['perfil'] !== 'aluno') {
             $_SESSION['error_message'] = "Apenas alunos podem entrar em turmas.";
             header('Location: ' . BASE_URL . '/dashboard');
             exit;
        }
        
        $codigo_turma = strtoupper($_POST['codigo_turma'] ?? '');
        if (empty($codigo_turma)) {
            $_SESSION['error_message'] = "O código da turma não pode ser vazio.";
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        $cursoModel = new Curso();
        $curso = $cursoModel->findByCode($this->pdo, $codigo_turma);

        if (!$curso) {
            $_SESSION['error_message'] = "Código de turma inválido. Tente novamente.";
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        $inscricao = new Inscricao();
        $inscricao->aluno_id = $_SESSION['usuario_id'];
        $inscricao->curso_id = $curso['id'];
        
        if ($inscricao->create($this->pdo)) {
            $_SESSION['success_message'] = "Inscrição no curso '" . htmlspecialchars($curso['titulo']) . "' realizada com sucesso!";
        } else {
            $_SESSION['error_message'] = "Você já está inscrito neste curso.";
        }
        
        header('Location: ' . BASE_URL . '/dashboard');
        exit;
    }

    /**
     * Permite que um ALUNO saia de uma turma (se desinscreva).
     */
    public function leave(int $curso_id) {
        $this->checkAuth(); // Garante que o usuário está logado
        if ($_SESSION['perfil'] !== 'aluno') {
            $_SESSION['error_message'] = "Apenas alunos podem sair de turmas.";
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        $inscricaoModel = new Inscricao();
        
        if ($inscricaoModel->deleteByAlunoAndCurso($this->pdo, $_SESSION['usuario_id'], $curso_id)) {
            $_SESSION['success_message'] = "Você saiu da turma com sucesso.";
        } else {
            $_SESSION['error_message'] = "Ocorreu um erro ao tentar sair da turma.";
        }

        header('Location: ' . BASE_URL . '/dashboard');
        exit;
    }
}