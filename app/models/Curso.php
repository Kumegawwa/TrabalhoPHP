<?php
class Curso {
    public $id, $titulo, $descricao, $professor_id;

    public function create($pdo) {
        $stmt = $pdo->prepare("INSERT INTO cursos (titulo, descricao, professor_id) VALUES (?, ?, ?)");
        $stmt->execute([$this->titulo, $this->descricao, $this->professor_id]);
    }

    public function getAll($pdo) {
        $stmt = $pdo->query("SELECT * FROM cursos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
