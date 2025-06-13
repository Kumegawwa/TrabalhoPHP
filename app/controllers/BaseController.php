<?php

abstract class BaseController {
    protected PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Garante que o usuário esteja autenticado. Redireciona para o login caso não esteja.
     */
    protected function checkAuth(): void {
        if (!isset($_SESSION['usuario_id'])) {
            $_SESSION['error_message'] = 'Você precisa estar logado para acessar esta página.';
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    protected function checkProfessor(): void {
        $this->checkAuth();
        if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] !== 'professor') {
            $_SESSION['error_message'] = 'Acesso negado. Apenas professores podem realizar esta ação.';
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
    }
}