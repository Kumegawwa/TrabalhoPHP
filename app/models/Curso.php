<?php
class Curso {
    public $id, $titulo, $descricao, $professor_id, $codigo_turma;

    /**
     * Gera um código alfanumérico único para a turma.
     * @param PDO $pdo A conexão com o banco de dados para verificar a unicidade.
     * @param int $length O comprimento do código a ser gerado.
     * @return string O código único gerado.
     */
    private function generateCode($pdo, $length = 6) {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';
        do {
            $code = '';
            for ($i = 0; $i < $length; $i++) {
                $code .= $characters[rand(0, strlen($characters) - 1)];
            }
            // Garante que o código gerado já não exista no banco (chance mínima)
        } while ($this->findByCode($pdo, $code));
        
        return $code;
    }

    /**
     * Cria um novo curso no banco de dados, incluindo um código de turma gerado automaticamente.
     * @param PDO $pdo Conexão com o banco de dados.
     * @return bool Retorna true em caso de sucesso, false em caso de falha.
     */
    public function create($pdo) {
        // Gera um código único antes de criar o curso
        $this->codigo_turma = $this->generateCode($pdo);

        $stmt = $pdo->prepare("INSERT INTO cursos (titulo, descricao, professor_id, codigo_turma) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$this->titulo, $this->descricao, $this->professor_id, $this->codigo_turma]);
    }

    /**
     * Busca todos os cursos, juntando o nome do professor.
     * @param PDO $pdo Conexão com o banco de dados.
     * @return array Lista de todos os cursos.
     */
    public function getAll($pdo) {
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
     * @return array|false Dados do curso ou false se não encontrado.
     */
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

    /**
     * Busca um curso pelo seu código de turma único.
     * @param PDO $pdo Conexão com o banco de dados.
     * @param string $code Código da turma.
     * @return array|false Dados do curso ou false se não encontrado.
     */
    public function findByCode($pdo, $code) {
        $stmt = $pdo->prepare("SELECT * FROM cursos WHERE codigo_turma = ?");
        $stmt->execute([$code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Busca todos os cursos criados por um professor específico.
     * @param PDO $pdo Conexão com o banco de dados.
     * @param int $professor_id ID do professor.
     * @return array Lista de cursos do professor.
     */
    public function findByProfessorId($pdo, $professor_id) {
        $stmt = $pdo->prepare("SELECT c.*, u.nome as professor_nome FROM cursos c JOIN usuarios u ON c.professor_id = u.id WHERE c.professor_id = ? ORDER BY c.titulo ASC");
        $stmt->execute([$professor_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Atualiza os dados de um curso existente.
     * @param PDO $pdo Conexão com o banco de dados.
     * @return bool Retorna true em caso de sucesso, false em caso de falha.
     */
    public function update($pdo) {
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
    public function delete($pdo, $id, $professor_id) {
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
    public function getMateriais($pdo, $curso_id) {
        $materialModel = new Material();
        return $materialModel->getByCursoId($pdo, $curso_id);
    }
}