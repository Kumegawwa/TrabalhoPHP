<?php

class AuthController extends BaseController {

    public function login() {
        require __DIR__ . '/../views/auth/login.php';
    }

    public function processLogin() {
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        if (empty($email) || empty($senha)) {
            $_SESSION['error_message'] = "Email e senha são obrigatórios.";
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $usuarioModel = new Usuario();
        $user = $usuarioModel->findByEmail($this->pdo, $email);

        if ($user && password_verify($senha, $user['senha_hash'])) {
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['perfil'] = $user['perfil'];
            $_SESSION['usuario_nome'] = $user['nome'];
            
            unset($_SESSION['error_message']);
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        } else {
            $_SESSION['error_message'] = "Login inválido. Verifique seu email e senha.";
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    public function logout() {
        $this->checkAuth();
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . '/login');
        exit;
    }

    public function showRecoverForm() {
        require __DIR__ . '/../views/auth/recover.php';
    }

    public function processRecovery() {
        $cpf = $_POST['cpf'] ?? '';
        $data_nascimento = $_POST['data_nascimento'] ?? '';
        $nova_senha = $_POST['nova_senha'] ?? '';

        $usuarioModel = new Usuario();
        $user = $usuarioModel->validatePasswordReset($this->pdo, $cpf, $data_nascimento);

        if ($user && !empty($nova_senha)) {
            $usuarioModel->id = $user['id'];
            $usuarioModel->senha_hash = $nova_senha;
            $usuarioModel->updatePassword($this->pdo);
            
            $_SESSION['success_message'] = "Senha redefinida com sucesso! Você já pode fazer login.";
            header('Location: ' . BASE_URL . '/login');
        } else {
            $_SESSION['error_message'] = "CPF ou data de nascimento inválidos.";
            header('Location: ' . BASE_URL . '/recuperar-senha');
        }
        exit;
    }
}