<?php

class CursoController extends BaseController {

    public function index() {
        $cursoModel = new Curso();
        $cursos = $cursoModel->getAll($this->pdo);
        require __DIR__ . '/../views/site/lista_cursos.php';
    }

    public function create() {
        $this->checkProfessor();
        $viewData = ['titulo_pagina' => 'Criar Novo Curso', 'action' => BASE_URL . '/cursos/store', 'curso' => null, 'is_edit' => false];
        require __DIR__ . '/../views/cursos/form.php';
    }

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

    public function show(int $id) {
        $this->checkAuth();
        $cursoModel = new Curso();
        $curso = $cursoModel->findById($this->pdo, $id);

        if (!$curso) { http_response_code(404); echo "Curso não encontrado"; exit; }
        
        $materiais = $cursoModel->getMateriais($this->pdo, $id, $_SESSION['usuario_id'], $_SESSION['perfil']);
        
        $alunoInscrito = false;
        if ($_SESSION['perfil'] === 'aluno') {
            $inscricaoModel = new Inscricao();
            if($inscricaoModel->findByUserAndCourse($this->pdo, $_SESSION['usuario_id'], $id)) {
                $alunoInscrito = true;
            }
        }

        $alunos_inscritos = [];
        if ($_SESSION['perfil'] === 'professor' && $curso['professor_id'] == $_SESSION['usuario_id']) {
            $inscricaoModel = new Inscricao();
            $alunos_inscritos = $inscricaoModel->findUsersByCourse($this->pdo, $id);
        }
        
        require __DIR__ . '/../views/cursos/show.php';
    }

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
    
    public function delete(int $id) {
        $this->checkProfessor();
        $cursoModel = new Curso();
        
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

    public function leave(int $curso_id) {
        $this->checkAuth();
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

    /**
     * Permite que um professor remova um aluno de sua turma.
     */
    public function removeAluno(int $curso_id, int $aluno_id) {
        $this->checkProfessor();

        $cursoModel = new Curso();
        $curso = $cursoModel->findById($this->pdo, $curso_id);
        if (!$curso || $curso['professor_id'] != $_SESSION['usuario_id']) {
            $_SESSION['error_message'] = "Você não tem permissão para remover alunos desta turma.";
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        $inscricaoModel = new Inscricao();
        if ($inscricaoModel->deleteByAlunoAndCurso($this->pdo, $aluno_id, $curso_id)) {
            $_SESSION['success_message'] = "Aluno removido da turma com sucesso.";
        } else {
            $_SESSION['error_message'] = "Ocorreu um erro ao remover o aluno.";
        }

        header('Location: ' . BASE_URL . "/cursos/show/{$curso_id}");
        exit;
    }
}
