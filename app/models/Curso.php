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
            // Garante que o código gerado já não exista no banco
        } while ($this->findByCode($pdo, $code));
        
        return $code;
    }

    /**
     * Cria um novo curso no banco de dados, incluindo um código de turma gerado automaticamente.
     * @param PDO $pdo Conexão com o banco de dados.
     * @return bool Retorna true em caso de sucesso, false em caso de falha.
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
     * @return array Lista de todos os cursos.
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
     * @return array|null Dados do curso ou null se não encontrado.
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
     * @return array|null Dados do curso ou null se não encontrado.
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
     * @return array Lista de cursos do professor.
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
     * @return bool Retorna true em caso de sucesso, false em caso de falha.
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
     * @return bool Retorna true em caso de sucesso, false em caso de falha.
     */
    public function delete(PDO $pdo, int $id, int $professor_id): bool
    {
        // Garante que apenas o professor dono do curso pode deletá-lo
        $stmt = $pdo->prepare("DELETE FROM cursos WHERE id = ? AND professor_id = ?");
        return $stmt->execute([$id, $professor_id]);
    }

    /**
     * Busca todos os materiais associados a um curso.
     * @param PDO $pdo Conexão com o banco de dados.
     * @param int $curso_id ID do curso.
     * @return array Lista de materiais.
     */
    public function getMateriais(PDO $pdo, int $curso_id): array
    {
        $materialModel = new Material();
        return $materialModel->getByCursoId($pdo, $curso_id);
    }
}