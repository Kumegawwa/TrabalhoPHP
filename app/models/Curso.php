<?php
class Curso {
    public $id, $titulo, $descricao, $professor_id;

    public function create($pdo) {
        $stmt = $pdo->prepare("INSERT INTO cursos (titulo, descricao, professor_id) VALUES (?, ?, ?)");
        $stmt->execute([$this->titulo, $this->descricao, $this->professor_id]);
    }

    public function getAll($pdo) {
        $stmt = $pdo->query("SELECT c.*, u.nome as professor_nome 
                             FROM cursos c
                             JOIN usuarios u ON c.professor_id = u.id
                             ORDER BY c.titulo ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca um curso pelo seu ID.
     * @param PDO $pdo Conexão com o banco de dados.
     * @param int $id ID do curso a ser buscado.
     * @return array|false Retorna os dados do curso como array associativo ou false se não encontrado.
     */
    public function findById($pdo, $id) {
        $stmt = $pdo->prepare("SELECT c.*, u.nome as professor_nome
                               FROM cursos c
                               JOIN usuarios u ON c.professor_id = u.id
                               WHERE c.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Retorna uma única linha
    }

    /**
     * Atualiza os dados de um curso existente.
     * @param PDO $pdo Conexão com o banco de dados.
     * @return bool Retorna true se a atualização foi bem-sucedida, false caso contrário.
     */
    public function update($pdo) {
        if (!$this->id) {
            return false; // ID é necessário para atualizar
        }
        $stmt = $pdo->prepare("UPDATE cursos SET titulo = ?, descricao = ?, professor_id = ? WHERE id = ?");
        return $stmt->execute([$this->titulo, $this->descricao, $this->professor_id, $this->id]);
    }

    /**
     * Deleta um curso pelo seu ID.
     * @param PDO $pdo Conexão com o banco de dados.
     * @param int $id ID do curso a ser deletado.
     * @return bool Retorna true se a deleção foi bem-sucedida, false caso contrário.
     */
    public function delete($pdo, $id) {
        // Antes de deletar um curso, você pode querer verificar/deletar materiais e inscrições associadas
        // para manter a integridade referencial, ou configurar o banco para ON DELETE CASCADE.

        // Exemplo: Deletar materiais associados
        $stmtMateriais = $pdo->prepare("DELETE FROM materiais_atividades WHERE curso_id = ?");
        $stmtMateriais->execute([$id]);

        // Exemplo: Deletar inscrições associadas
        $stmtInscricoes = $pdo->prepare("DELETE FROM inscricoes_cursos WHERE curso_id = ?");
        $stmtInscricoes->execute([$id]);
        
        $stmt = $pdo->prepare("DELETE FROM cursos WHERE id = ?");
        return $stmt->execute([$id]);
    }
}