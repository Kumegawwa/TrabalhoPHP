<?php
class Material {
    public $id, $curso_id, $titulo, $conteudo, $tipo, $data_postagem;

    public function create($pdo) {
        $sql = "INSERT INTO materiais_atividades (curso_id, titulo, conteudo, tipo) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->curso_id, $this->titulo, $this->conteudo, $this->tipo]);
    }

    /**
     * Busca todos os materiais e atividades de um curso específico.
     * @param PDO $pdo Conexão com o banco de dados.
     * @param int $curso_id ID do curso.
     * @return array Retorna um array de materiais/atividades.
     */
    public function getByCursoId($pdo, $curso_id) {
        $stmt = $pdo->prepare("SELECT * FROM materiais_atividades WHERE curso_id = ? ORDER BY data_postagem DESC");
        $stmt->execute([$curso_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca um material/atividade pelo seu ID.
     * @param PDO $pdo Conexão com o banco de dados.
     * @param int $id ID do material/atividade.
     * @return array|false
     */
    public function findById($pdo, $id) {
        $stmt = $pdo->prepare("SELECT * FROM materiais_atividades WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Atualiza um material/atividade existente.
     * @param PDO $pdo Conexão com o banco de dados.
     * @return bool
     */
    public function update($pdo) {
        if (!$this->id) {
            return false;
        }
        $sql = "UPDATE materiais_atividades SET curso_id = ?, titulo = ?, conteudo = ?, tipo = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$this->curso_id, $this->titulo, $this->conteudo, $this->tipo, $this->id]);
    }

    /**
     * Deleta um material/atividade pelo seu ID.
     * @param PDO $pdo Conexão com o banco de dados.
     * @param int $id ID do material/atividade.
     * @return bool
     */
    public function delete($pdo, $id) {
        // Considere deletar entregas de atividades associadas se este for uma atividade
        $stmt = $pdo->prepare("DELETE FROM materiais_atividades WHERE id = ?");
        return $stmt->execute([$id]);
    }
}