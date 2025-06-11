<?php
class CursoController {

    /**
     * Lista todos os cursos publicamente.
     */
    public function index() {
        global $pdo;
        $cursoModel = new Curso();
        $cursos = $cursoModel->getAll($pdo);
        
        // Esta view é pública, então não precisa de lógica de inscrição aqui.
        // A ideia é que ela funcione como um "catálogo" de cursos.
        require __DIR__ . '/../views/site/lista_cursos.php';
    }

    /**
     * Exibe o formulário para criar um novo curso.
     */
    public function create() {
        $viewData = ['titulo_pagina' => 'Criar Novo Curso', 'action' => BASE_URL . '/cursos/store', 'curso' => null];
        require __DIR__ . '/../views/cursos/form.php';
    }

    /**
     * Salva um novo curso no banco de dados.
     */
    public function store() {
        global $pdo;
        $curso = new Curso();
        $curso->titulo = $_POST['titulo'];
        $curso->descricao = $_POST['descricao'];
        $curso->professor_id = $_SESSION['usuario_id'];
        
        if ($curso->create($pdo)) {
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
    public function show($id) {
        global $pdo;
        $cursoModel = new Curso();
        $curso = $cursoModel->findById($pdo, $id);

        if (!$curso) { http_response_code(404); echo "Curso não encontrado"; exit; }
        
        $materiais = $cursoModel->getMateriais($pdo, $id);
        
        // Verifica se o aluno está inscrito para exibir o conteúdo
        $alunoInscrito = false;
        if (isset($_SESSION['perfil']) && $_SESSION['perfil'] === 'aluno') {
            $inscricaoModel = new Inscricao();
            if($inscricaoModel->findByUserAndCourse($pdo, $_SESSION['usuario_id'], $id)) {
                $alunoInscrito = true;
            }
        }
        
        require __DIR__ . '/../views/cursos/show.php';
    }

    /**
     * Exibe o formulário para editar um curso existente.
     */
    public function edit($id) {
        global $pdo;
        $cursoModel = new Curso();
        $curso = $cursoModel->findById($pdo, $id);
        
        if (!$curso || $curso['professor_id'] != $_SESSION['usuario_id']) {
            $_SESSION['error_message'] = "Acesso negado.";
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        $viewData = ['titulo_pagina' => 'Editar Curso', 'action' => BASE_URL . '/cursos/update/' . $id, 'curso' => $curso];
        require __DIR__ . '/../views/cursos/form.php';
    }

    /**
     * Atualiza um curso no banco de dados.
     */
    public function update($id) {
        global $pdo;
        $curso = new Curso();
        $curso->id = $id;
        $curso->titulo = $_POST['titulo'];
        $curso->descricao = $_POST['descricao'];
        $curso->professor_id = $_SESSION['usuario_id'];
        
        if ($curso->update($pdo)) {
            $_SESSION['success_message'] = "Curso atualizado com sucesso!";
        } else {
            $_SESSION['error_message'] = "Erro ao atualizar o curso ou permissão negada.";
        }
        header('Location: ' . BASE_URL . '/dashboard');
        exit;
    }
    
    /**
     * Deleta um curso.
     */
    public function delete($id) {
        global $pdo;
        $cursoModel = new Curso();
        
        if ($cursoModel->delete($pdo, $id, $_SESSION['usuario_id'])) {
             $_SESSION['success_message'] = "Curso deletado com sucesso!";
        } else {
             $_SESSION['error_message'] = "Erro ao deletar o curso ou permissão negada.";
        }
        header('Location: ' . BASE_URL . '/dashboard');
        exit;
    }
    
    /**
     * Inscreve um aluno em um curso (método legado, pode ser removido se a inscrição for só por código).
     */
    public function enroll($curso_id) {
        global $pdo;
        $inscricao = new Inscricao();
        $inscricao->aluno_id = $_SESSION['usuario_id'];
        $inscricao->curso_id = $curso_id;
        
        if ($inscricao->create($pdo)) {
            $_SESSION['success_message'] = "Inscrição realizada com sucesso!";
        } else {
            $_SESSION['error_message'] = "Você já está inscrito neste curso ou ocorreu um erro.";
        }
        header('Location: ' . BASE_URL . '/cursos/show/' . $curso_id);
        exit;
    }

    /**
     * Inscreve um aluno em um curso usando o código da turma.
     */
    public function joinByCode() {
        global $pdo;
        
        $codigo_turma = $_POST['codigo_turma'] ?? '';
        if (empty($codigo_turma)) {
            $_SESSION['error_message'] = "O código da turma não pode ser vazio.";
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        $cursoModel = new Curso();
        $curso = $cursoModel->findByCode($pdo, strtoupper($codigo_turma));

        if (!$curso) {
            $_SESSION['error_message'] = "Código de turma inválido. Tente novamente.";
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        $inscricao = new Inscricao();
        $inscricao->aluno_id = $_SESSION['usuario_id'];
        $inscricao->curso_id = $curso['id'];
        
        if ($inscricao->create($pdo)) {
            $_SESSION['success_message'] = "Inscrição no curso '" . htmlspecialchars($curso['titulo']) . "' realizada com sucesso!";
        } else {
            $_SESSION['error_message'] = "Você já está inscrito neste curso.";
        }
        
        header('Location: ' . BASE_URL . '/dashboard');
        exit;
    }
}