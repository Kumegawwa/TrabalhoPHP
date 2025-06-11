<?php
class CursoController {
    // Lista todos os cursos (página principal para usuários logados)
    public function index() {
        global $pdo;
        $cursoModel = new Curso();
        $cursos = $cursoModel->getAll($pdo);
        
        $inscricaoModel = new Inscricao();
        $cursosInscritos = [];
        if (isset($_SESSION['usuario_id']) && $_SESSION['perfil'] === 'aluno') {
            $inscricoes = $inscricaoModel->findCoursesByUser($pdo, $_SESSION['usuario_id']);
            foreach ($inscricoes as $insc) {
                $cursosInscritos[] = $insc['curso_id'];
            }
        }

        require __DIR__ . '/../views/cursos/index.php';
    }

    // Exibe o formulário para criar um novo curso
    public function create() {
        $viewData = ['titulo_pagina' => 'Criar Novo Curso', 'action' => BASE_URL . '/cursos/store', 'curso' => null];
        require __DIR__ . '/../views/cursos/form.php';
    }

    // Salva um novo curso no banco de dados
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
        header('Location: ' . BASE_URL . '/cursos');
        exit;
    }

    // Mostra detalhes de um curso específico e seus materiais
    public function show($id) {
        global $pdo;
        $cursoModel = new Curso();
        $curso = $cursoModel->findById($pdo, $id);

        if (!$curso) { http_response_code(404); echo "Curso não encontrado"; exit; }
        
        $materiais = $cursoModel->getMateriais($pdo, $id);
        
        // Verifica se o aluno está inscrito
        $alunoInscrito = false;
        if ($_SESSION['perfil'] === 'aluno') {
            $inscricaoModel = new Inscricao();
            if($inscricaoModel->findByUserAndCourse($pdo, $_SESSION['usuario_id'], $id)) {
                $alunoInscrito = true;
            }
        }
        
        require __DIR__ . '/../views/cursos/show.php';
    }

    // Exibe o formulário para editar um curso existente
    public function edit($id) {
        global $pdo;
        $cursoModel = new Curso();
        $curso = $cursoModel->findById($pdo, $id);
        
        // Validação de permissão
        if (!$curso || $curso['professor_id'] != $_SESSION['usuario_id']) {
            $_SESSION['error_message'] = "Acesso negado.";
            header('Location: ' . BASE_URL . '/cursos');
            exit;
        }

        $viewData = ['titulo_pagina' => 'Editar Curso', 'action' => BASE_URL . '/cursos/update/' . $id, 'curso' => $curso];
        require __DIR__ . '/../views/cursos/form.php';
    }

    // Atualiza um curso no banco de dados
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
        header('Location: ' . BASE_URL . '/cursos');
        exit;
    }
    
    // Deleta um curso
    public function delete($id) {
        global $pdo;
        $cursoModel = new Curso();
        
        if ($cursoModel->delete($pdo, $id, $_SESSION['usuario_id'])) {
             $_SESSION['success_message'] = "Curso deletado com sucesso!";
        } else {
             $_SESSION['error_message'] = "Erro ao deletar o curso ou permissão negada.";
        }
        header('Location: ' . BASE_URL . '/cursos');
        exit;
    }
    
    // Inscreve um aluno em um curso
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
}