<?php

class Inscricao {
    public ?int $id = null;
    public ?int $aluno_id = null;
    public ?int $curso_id = null;

    public function create(PDO $pdo): bool {
        if ($this->findByUserAndCourse($pdo, $this->aluno_id, $this->curso_id)) {
            return false; // Já inscrito
        }
        $sql = "INSERT INTO inscricoes_cursos (aluno_id, curso_id) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$this->aluno_id, $this->curso_id]);
    }
    
    public function findByUserAndCourse(PDO $pdo, int $aluno_id, int $curso_id): ?array {
        $stmt = $pdo->prepare("SELECT * FROM inscricoes_cursos WHERE aluno_id = ? AND curso_id = ?");
        $stmt->execute([$aluno_id, $curso_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function findCoursesByUser(PDO $pdo, int $aluno_id): array {
        $stmt = $pdo->prepare("SELECT curso_id FROM inscricoes_cursos WHERE aluno_id = ?");
        $stmt->execute([$aluno_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Remove uma inscrição específica do banco de dados.
     * @param PDO $pdo Conexão com o banco de dados.
     * @param int $aluno_id ID do aluno.
     * @param int $curso_id ID do curso.
     * @return bool Retorna true em caso de sucesso, false em caso de falha.
     */
    public function deleteByAlunoAndCurso(PDO $pdo, int $aluno_id, int $curso_id): bool {
        $stmt = $pdo->prepare("DELETE FROM inscricoes_cursos WHERE aluno_id = ? AND curso_id = ?");
        return $stmt->execute([$aluno_id, $curso_id]);
    }
}