<?php
class AuthController {
    public function login() {
        // O roteador agora cuida de chamar as views
        require __DIR__ . '/../views/auth/login.php';
    }

    public function processLogin() {
        global $pdo; // Certifique-se que $pdo está disponível globalmente ou passe como parâmetro

        // Validação CSRF (se você implementou no seu helper/index)
        // if (!verifyCsrfToken()) { die('CSRF token inválido.'); }


        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        if (empty($email) || empty($senha)) {
            // Redirecionar de volta com erro
            $_SESSION['login_error'] = "Email e senha são obrigatórios.";
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $usuarioModel = new Usuario();
        $user = $usuarioModel->findByEmail($pdo, $email);

        if ($user && password_verify($senha, $user['senha_hash'])) {
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['perfil'] = $user['perfil'];
            $_SESSION['usuario_nome'] = $user['nome']; // Útil para o header
            
            unset($_SESSION['login_error']); // Limpa erro de login
            header('Location: ' . BASE_URL . '/home'); // Use a constante BASE_URL
            exit;
        } else {
            $_SESSION['login_error'] = "Login inválido. Verifique seu email e senha.";
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}