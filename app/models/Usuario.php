<?php
class Usuario {
    public $id, $nome, $email, $senha_hash, $perfil, $cpf, $data_nascimento;

    public function create($pdo) {
        $sql = "INSERT INTO usuarios (nome, email, senha_hash, perfil, cpf, data_nascimento)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->nome, $this->email, $this->senha_hash, $this->perfil, $this->cpf, $this->data_nascimento]);
    }

    public function findByEmail($pdo, $email) {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
