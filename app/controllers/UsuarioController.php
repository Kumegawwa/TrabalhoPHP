<?php

class UsuarioController extends BaseController {

    public function create() {
        require __DIR__ . '/../views/usuarios/register.php';
    }

    public function store() {
        if (empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['senha']) || empty($_POST['cpf']) || empty($_POST['data_nascimento'])) {
            $_SESSION['error_message'] = "Todos os campos são obrigatórios.";
            header('Location: ' . BASE_URL . '/register');
            exit;
        }

        $usuarioModel = new Usuario();
        if ($usuarioModel->findByEmail($this->pdo, $_POST['email'])) {
             $_SESSION['error_message'] = "Este email já está cadastrado.";
             header('Location: ' . BASE_URL . '/register');
             exit;
        }

        $usuario = new Usuario();
        $usuario->nome = $_POST['nome'];
        $usuario->email = $_POST['email'];
        $usuario->senha_hash = $_POST['senha'];
        $usuario->cpf = $_POST['cpf'];
        $usuario->data_nascimento = $_POST['data_nascimento'];
        $usuario->perfil = 'aluno';

        if ($usuario->create($this->pdo)) {
            $_SESSION['success_message'] = 'Cadastro realizado com sucesso! Faça o login.';
            header('Location: ' . BASE_URL . '/login');
        } else {
            $_SESSION['error_message'] = 'Ocorreu um erro ao cadastrar. Tente novamente.';
            header('Location: ' . BASE_URL . '/register');
        }
        exit;
    }
}