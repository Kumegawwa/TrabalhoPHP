<?php
class Curso {
    public $id, $titulo, $descricao, $professor_id;

    public function create($pdo) {
        $stmt = $pdo->prepare("INSERT INTO cursos (titulo, descricao, professor_id) VALUES (?, ?, ?)");
        return $stmt->execute([$this->titulo, $this->descricao, $this->professor_id]);
    }

    public function getAll($pdo) {
        $stmt = $pdo->query("
            SELECT c.*, u.nome as professor_nome 
            FROM cursos c
            JOIN usuarios u ON c.professor_id = u.id
            ORDER BY c.titulo ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($pdo, $id) {
        $stmt = $pdo->prepare("
            SELECT c.*, u.nome as professor_nome
            FROM cursos c
            JOIN usuarios u ON c.professor_id = u.id
            WHERE c.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($pdo) {
        $stmt = $pdo->prepare("UPDATE cursos SET titulo = ?, descricao = ? WHERE id = ? AND professor_id = ?");
        return $stmt->execute([$this->titulo, $this->descricao, $this->id, $this->professor_id]);
    }

    public function delete($pdo, $id, $professor_id) {
        // Garante que apenas o professor dono do curso pode deletá-lo
        $stmt = $pdo->prepare("DELETE FROM cursos WHERE id = ? AND professor_id = ?");
        return $stmt->execute([$id, $professor_id]);
    }

    public function getMateriais($pdo, $curso_id) {
        $materialModel = new Material();
        return $materialModel->getByCursoId($pdo, $curso_id);
    }
}