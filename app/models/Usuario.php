<?php
class Usuario {
    public $id, $nome, $email, $senha_hash, $perfil, $cpf, $data_nascimento;

    /**
     * Cria um novo usuário no banco de dados.
     * @param PDO $pdo Conexão com o banco de dados.
     * @return bool Retorna true em caso de sucesso, false em caso de falha.
     */
    public function create($pdo) {
        $sql = "INSERT INTO usuarios (nome, email, senha_hash, perfil, cpf, data_nascimento) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        // Hasheia a senha antes de salvar
        $this->senha_hash = password_hash($this->senha_hash, PASSWORD_DEFAULT);
        return $stmt->execute([$this->nome, $this->email, $this->senha_hash, $this->perfil, $this->cpf, $this->data_nascimento]);
    }

    /**
     * Busca um usuário pelo seu ID.
     * @param PDO $pdo Conexão com o banco de dados.
     * @param int $id ID do usuário.
     * @return array|false Dados do usuário ou false se não encontrado.
     */
    public function findById($pdo, $id) {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Busca um usuário pelo seu email.
     * @param PDO $pdo Conexão com o banco de dados.
     * @param string $email Email do usuário.
     * @return array|false Dados do usuário ou false se não encontrado.
     */
    public function findByEmail($pdo, $email) {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Atualiza os dados de um usuário (exceto senha).
     * @param PDO $pdo Conexão com o banco de dados.
     * @return bool Retorna true em caso de sucesso, false em caso de falha.
     */
    public function update($pdo) {
        $sql = "UPDATE usuarios SET nome = ?, email = ?, perfil = ?, cpf = ?, data_nascimento = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$this->nome, $this->email, $this->perfil, $this->cpf, $this->data_nascimento, $this->id]);
    }

    /**
     * Atualiza apenas a senha de um usuário.
     * @param PDO $pdo Conexão com o banco de dados.
     * @return bool Retorna true em caso de sucesso, false em caso de falha.
     */
    public function updatePassword($pdo) {
        $sql = "UPDATE usuarios SET senha_hash = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $this->senha_hash = password_hash($this->senha_hash, PASSWORD_DEFAULT);
        return $stmt->execute([$this->senha_hash, $this->id]);
    }

    /**
     * Deleta um usuário do banco de dados.
     * @param PDO $pdo Conexão com o banco de dados.
     * @param int $id ID do usuário a ser deletado.
     * @return bool Retorna true em caso de sucesso, false em caso de falha.
     */
    public function delete($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Valida as credenciais para recuperação de senha.
     * @param PDO $pdo Conexão com o banco de dados.
     * @param string $cpf CPF do usuário.
     * @param string $data_nascimento Data de nascimento do usuário.
     * @return array|false Dados do usuário ou false se as credenciais não baterem.
     */
    public function validatePasswordReset($pdo, $cpf, $data_nascimento) {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE cpf = ? AND data_nascimento = ?");
        $stmt->execute([$cpf, $data_nascimento]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Busca todos os cursos em que um aluno específico está inscrito.
     * CORREÇÃO: Adicionado JOIN com a tabela de usuários para buscar o nome do professor.
     * @param PDO $pdo Conexão com o banco de dados.
     * @param int $aluno_id ID do aluno.
     * @return array Lista de cursos em que o aluno está inscrito.
     */
    public function getCursosInscritos($pdo, $aluno_id) {
        $stmt = $pdo->prepare("
            SELECT c.*, u.nome as professor_nome
            FROM inscricoes_cursos ic
            JOIN cursos c ON ic.curso_id = c.id
            JOIN usuarios u ON c.professor_id = u.id
            WHERE ic.aluno_id = ?
            ORDER BY c.titulo ASC
        ");
        $stmt->execute([$aluno_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Verifica se o usuário logado é um professor.
     * @return bool
     */
    public function isProfessor() {
        return isset($_SESSION['perfil']) && $_SESSION['perfil'] === 'professor';
    }
}