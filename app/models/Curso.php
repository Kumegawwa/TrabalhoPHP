<?php

class Curso
{
    public ?int $id = null;
    public ?string $titulo = null;
    public ?string $descricao = null;
    public ?int $professor_id = null;
    public ?string $codigo_turma = null;

    /**
     * Gera um código alfanumérico único para a turma.
     * @param PDO $pdo A conexão com o banco de dados para verificar a unicidade.
     * @param int $length O comprimento do código a ser gerado.
     * @return string O código único gerado.
     */
    private function generateCode(PDO $pdo, int $length = 6): string
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';
        do {
            $code = '';
            for ($i = 0; $i < $length; $i++) {
                $code .= $characters[rand(0, strlen($characters) - 1)];
            }
        } while ($this->findByCode($pdo, $code));
        
        return $code;
    }

    /**
     * Cria um novo curso no banco de dados.
     * @param PDO $pdo Conexão com o banco de dados.
     * @return bool
     */
    public function create(PDO $pdo): bool
    {
        $this->codigo_turma = $this->generateCode($pdo);
        $stmt = $pdo->prepare("INSERT INTO cursos (titulo, descricao, professor_id, codigo_turma) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$this->titulo, $this->descricao, $this->professor_id, $this->codigo_turma]);
    }

    /**
     * Busca todos os cursos, juntando o nome do professor.
     * @param PDO $pdo Conexão com o banco de dados.
     * @return array
     */
    public function getAll(PDO $pdo): array
    {
        $stmt = $pdo->query("
            SELECT c.*, u.nome as professor_nome 
            FROM cursos c
            JOIN usuarios u ON c.professor_id = u.id
            ORDER BY c.titulo ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca um curso específico pelo seu ID.
     * @param PDO $pdo Conexão com o banco de dados.
     * @param int $id ID do curso.
     * @return array|null
     */
    public function findById(PDO $pdo, int $id): ?array
    {
        $stmt = $pdo->prepare("
            SELECT c.*, u.nome as professor_nome
            FROM cursos c
            JOIN usuarios u ON c.professor_id = u.id
            WHERE c.id = ?
        ");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Busca um curso pelo seu código de turma único.
     * @param PDO $pdo Conexão com o banco de dados.
     * @param string $code Código da turma.
     * @return array|null
     */
    public function findByCode(PDO $pdo, string $code): ?array
    {
        $stmt = $pdo->prepare("SELECT * FROM cursos WHERE codigo_turma = ?");
        $stmt->execute([$code]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Busca todos os cursos criados por um professor específico.
     * @param PDO $pdo Conexão com o banco de dados.
     * @param int $professor_id ID do professor.
     * @return array
     */
    public function findByProfessorId(PDO $pdo, int $professor_id): array
    {
        $stmt = $pdo->prepare("SELECT c.*, u.nome as professor_nome FROM cursos c JOIN usuarios u ON c.professor_id = u.id WHERE c.professor_id = ? ORDER BY c.titulo ASC");
        $stmt->execute([$professor_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Atualiza os dados de um curso existente.
     * @param PDO $pdo Conexão com o banco de dados.
     * @return bool
     */
    public function update(PDO $pdo): bool
    {
        $stmt = $pdo->prepare("UPDATE cursos SET titulo = ?, descricao = ? WHERE id = ? AND professor_id = ?");
        return $stmt->execute([$this->titulo, $this->descricao, $this->id, $this->professor_id]);
    }

    /**
     * Deleta um curso do banco de dados.
     * @param PDO $pdo Conexão com o banco de dados.
     * @param int $id ID do curso.
     * @param int $professor_id ID do professor (para verificação de permissão).
     * @return bool
     */
    public function delete(PDO $pdo, int $id, int $professor_id): bool
    {
        $stmt = $pdo->prepare("DELETE FROM cursos WHERE id = ? AND professor_id = ?");
        return $stmt->execute([$id, $professor_id]);
    }

    /**
     * Busca todos os materiais associados a um curso.
     * @param PDO $pdo Conexão com o banco de dados.
     * @param int $curso_id ID do curso.
     * @param int $user_id ID do usuário logado (para filtro de aluno).
     * @param string $user_profile Perfil do usuário logado (para filtro).
     * @return array Lista de materiais.
     */
    public function getMateriais(PDO $pdo, int $curso_id, int $user_id, string $user_profile): array
    {
        $materialModel = new Material();
        // CORREÇÃO APLICADA: Repassa todos os 4 argumentos para o método correto.
        return $materialModel->getByCursoId($pdo, $curso_id, $user_id, $user_profile);
    }

    /**
     * Conta o total de cursos na plataforma.
     * @param PDO $pdo Conexão com o banco de dados.
     * @return int O número total de cursos.
     */
    public function countAll(PDO $pdo): int {
        $stmt = $pdo->query("SELECT COUNT(id) FROM cursos");
        return (int) $stmt->fetchColumn();
    }

    /**
     * Busca os cursos mais recentes para exibir na home.
     * @param PDO $pdo Conexão com o banco de dados.
     * @param int $limit O número de cursos a serem retornados.
     * @return array Uma lista dos cursos mais recentes.
     */
    public function getLatest(PDO $pdo, int $limit = 3): array {
        $stmt = $pdo->query("
            SELECT c.*, u.nome as professor_nome 
            FROM cursos c
            JOIN usuarios u ON c.professor_id = u.id
            ORDER BY c.data_criacao DESC
            LIMIT " . $limit
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
