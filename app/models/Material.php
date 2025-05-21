<?php
class Material {
    public $id, $curso_id, $titulo, $conteudo, $tipo, $data_postagem;

    public function create($pdo) {
        $sql = "INSERT INTO materiais_atividades (curso_id, titulo, conteudo, tipo) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->curso_id, $this->titulo, $this->conteudo, $this->tipo]);
    }
}
