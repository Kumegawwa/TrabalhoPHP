<?php
class Inscricao {
    public $id, $aluno_id, $curso_id;

    public function create($pdo) {
        // Verifica se já não está inscrito para evitar duplicatas
        if ($this->findByUserAndCourse($pdo, $this->aluno_id, $this->curso_id)) {
            return false; // Já inscrito
        }
        $sql = "INSERT INTO inscricoes_cursos (aluno_id, curso_id) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$this->aluno_id, $this->curso_id]);
    }
    
    public function findByUserAndCourse($pdo, $aluno_id, $curso_id) {
        $stmt = $pdo->prepare("SELECT * FROM inscricoes_cursos WHERE aluno_id = ? AND curso_id = ?");
        $stmt->execute([$aluno_id, $curso_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($pdo, $aluno_id, $curso_id) {
        $stmt = $pdo->prepare("DELETE FROM inscricoes_cursos WHERE aluno_id = ? AND curso_id = ?");
        return $stmt->execute([$aluno_id, $curso_id]);
    }

    /**
     * MÉTODO ADICIONADO PARA CORREÇÃO
     * Busca todos os cursos em que um aluno está inscrito.
     * @param PDO $pdo Conexão com o banco de dados.
     * @param int $aluno_id ID do aluno.
     * @return array Retorna um array com os IDs dos cursos.
     */
    public function findCoursesByUser($pdo, $aluno_id) {
        $stmt = $pdo->prepare("SELECT curso_id FROM inscricoes_cursos WHERE aluno_id = ?");
        $stmt->execute([$aluno_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}