<?php
class AuthController {
    public function login() {
        require __DIR__ . '/../views/auth/login.php';
    }

    public function processLogin() {
        global $pdo;
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        $usuarioModel = new Usuario();
        $user = $usuarioModel->findByEmail($pdo, $email);

        if ($user && password_verify($senha, $user['senha_hash'])) {
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['perfil'] = $user['perfil'];
            // Modificado para incluir o prefixo TrabalhoPHP no redirecionamento
            header('Location: /TrabalhoPHP/home');
        } else {
            echo "Login inválido";
        }
    }
}
