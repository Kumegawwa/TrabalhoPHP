<?php
class Usuario {
    public $id, $nome, $email, $senha_hash, $perfil, $cpf, $data_nascimento;

    public function create($pdo) {
        $sql = "INSERT INTO usuarios (nome, email, senha_hash, perfil, cpf, data_nascimento) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        // Hasheia a senha antes de salvar
        $this->senha_hash = password_hash($this->senha_hash, PASSWORD_DEFAULT);
        return $stmt->execute([$this->nome, $this->email, $this->senha_hash, $this->perfil, $this->cpf, $this->data_nascimento]);
    }

    public function findById($pdo, $id) {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByEmail($pdo, $email) {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($pdo) {
        $sql = "UPDATE usuarios SET nome = ?, email = ?, perfil = ?, cpf = ?, data_nascimento = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$this->nome, $this->email, $this->perfil, $this->cpf, $this->data_nascimento, $this->id]);
    }

    public function updatePassword($pdo) {
        $sql = "UPDATE usuarios SET senha_hash = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $this->senha_hash = password_hash($this->senha_hash, PASSWORD_DEFAULT);
        return $stmt->execute([$this->senha_hash, $this->id]);
    }

    public function delete($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function validatePasswordReset($pdo, $cpf, $data_nascimento) {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE cpf = ? AND data_nascimento = ?");
        $stmt->execute([$cpf, $data_nascimento]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCursosInscritos($pdo, $aluno_id) {
        $stmt = $pdo->prepare("
            SELECT c.* FROM cursos c
            JOIN inscricoes_cursos ic ON c.id = ic.curso_id
            WHERE ic.aluno_id = ?
        ");
        $stmt->execute([$aluno_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function isProfessor() {
        return isset($_SESSION['perfil']) && $_SESSION['perfil'] === 'professor';
    }
}