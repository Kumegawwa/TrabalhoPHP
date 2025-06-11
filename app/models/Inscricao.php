<?php

class Inscricao {
    public ?int $id = null;
    public ?int $aluno_id = null;
    public ?int $curso_id = null;

    /**
     * Cria uma nova inscrição no banco de dados.
     * @param PDO $pdo
     * @return bool
     */
    public function create(PDO $pdo): bool {
        // Verifica se já não está inscrito para evitar duplicatas
        if ($this->findByUserAndCourse($pdo, $this->aluno_id, $this->curso_id)) {
            return false; // Já inscrito
        }
        $sql = "INSERT INTO inscricoes_cursos (aluno_id, curso_id) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$this->aluno_id, $this->curso_id]);
    }
    
    /**
     * Busca uma inscrição específica por aluno e curso.
     * @param PDO $pdo
     * @param int $aluno_id
     * @param int $curso_id
     * @return array|null
     */
    public function findByUserAndCourse(PDO $pdo, int $aluno_id, int $curso_id): ?array {
        $stmt = $pdo->prepare("SELECT * FROM inscricoes_cursos WHERE aluno_id = ? AND curso_id = ?");
        $stmt->execute([$aluno_id, $curso_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Busca os IDs dos cursos em que um aluno está inscrito.
     * @param PDO $pdo
     * @param int $aluno_id
     * @return array
     */
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
     * @return bool
     */
    public function deleteByAlunoAndCurso(PDO $pdo, int $aluno_id, int $curso_id): bool {
        $stmt = $pdo->prepare("DELETE FROM inscricoes_cursos WHERE aluno_id = ? AND curso_id = ?");
        return $stmt->execute([$aluno_id, $curso_id]);
    }

    /**
     * Busca os dados dos usuários (alunos) inscritos em um curso específico.
     * @param PDO $pdo Conexão com o banco de dados.
     * @param int $curso_id ID do curso.
     * @return array Lista de alunos inscritos.
     */
    public function findUsersByCourse(PDO $pdo, int $curso_id): array {
        $stmt = $pdo->prepare("
            SELECT u.id, u.nome, u.email
            FROM usuarios u
            JOIN inscricoes_cursos ic ON u.id = ic.aluno_id
            WHERE ic.curso_id = ? AND u.perfil = 'aluno'
            ORDER BY u.nome ASC
        ");
        $stmt->execute([$curso_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
