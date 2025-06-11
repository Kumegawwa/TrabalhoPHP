<?php
class Material {
    public $id, $curso_id, $titulo, $conteudo, $tipo;

    public function create($pdo) {
        $sql = "INSERT INTO materiais_atividades (curso_id, titulo, conteudo, tipo) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$this->curso_id, $this->titulo, $this->conteudo, $this->tipo]);
    }

    public function getByCursoId($pdo, $curso_id) {
        $stmt = $pdo->prepare("SELECT * FROM materiais_atividades WHERE curso_id = ? ORDER BY data_postagem DESC");
        $stmt->execute([$curso_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function findById($pdo, $id) {
        $stmt = $pdo->prepare("SELECT * FROM materiais_atividades WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($pdo) {
        $sql = "UPDATE materiais_atividades SET titulo = ?, conteudo = ?, tipo = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$this->titulo, $this->conteudo, $this->tipo, $this->id]);
    }

    public function delete($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM materiais_atividades WHERE id = ?");
        return $stmt->execute([$id]);
    }
}