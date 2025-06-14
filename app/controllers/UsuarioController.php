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

        $senha = $_POST["senha"];

        // Verifica se a senha tem pelo menos 8 caracteres, com letras e números
        if (preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $senha)) {
            $_SESSION['success_message'] =  "Senha válida.";
            $usuario->senha_hash = $senha;
        } else {
            $_SESSION['error_message'] = "Senha inválida. Deve ter pelo menos 8 caracteres, incluindo letras e números.";
            header('Location: ' . BASE_URL . '/register');
            exit;
        }
        
        $cpf = $_POST["cpf"];

        // Remove qualquer caractere que não seja número
        $cpf = preg_replace('/\D/', '', $cpf);

        // Verifica se o CPF tem 11 dígitos
        if (strlen($cpf) != 11) {
            $_SESSION['error_message'] = "CPF inválido. Deve conter 11 dígitos.";
            header('Location: ' . BASE_URL . '/register');
            exit;
        } else {
            $_SESSION['success_message'] = "CPF válido: " . $cpf;
            $usuario->cpf = $cpf;
        }

        $usuario->data_nascimento = $_POST['data_nascimento'];
        $usuario->perfil = 'aluno'; // Padrão é sempre aluno no auto-cadastro

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