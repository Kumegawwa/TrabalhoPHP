<?php

class Material {
    public ?int $id = null;
    public ?int $curso_id = null;
    public ?string $titulo = null;
    public ?string $conteudo = null;
    public ?string $tipo = null;
    public ?int $aluno_id = null; // Coluna para atribuição individual

    /**
     * Cria um novo material/atividade no banco de dados.
     * @param PDO $pdo
     * @return bool
     */
    public function create(PDO $pdo): bool {
        $sql = "INSERT INTO materiais_atividades (curso_id, titulo, conteudo, tipo, aluno_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        // Se aluno_id for uma string vazia do formulário, converte para null para o banco
        $alunoIdParaSalvar = empty($this->aluno_id) ? null : $this->aluno_id;
        return $stmt->execute([$this->curso_id, $this->titulo, $this->conteudo, $this->tipo, $alunoIdParaSalvar]);
    }

    /**
     * Busca materiais de um curso, com lógica de permissão para alunos.
     * @param PDO $pdo
     * @param int $curso_id
     * @param int $user_id
     * @param string $user_profile
     * @return array
     */
    public function getByCursoId(PDO $pdo, int $curso_id, int $user_id, string $user_profile): array {
        // Professor vê todos os materiais do curso, com o nome do aluno se for individual
        if ($user_profile === 'professor') {
            $sql = "SELECT m.*, u.nome as aluno_nome 
                    FROM materiais_atividades m 
                    LEFT JOIN usuarios u ON m.aluno_id = u.id
                    WHERE m.curso_id = ? 
                    ORDER BY m.data_postagem DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$curso_id]);
        } else { 
            // Aluno vê apenas materiais para todos (aluno_id IS NULL) ou para si mesmo
            $sql = "SELECT m.*, u.nome as aluno_nome 
                    FROM materiais_atividades m
                    LEFT JOIN usuarios u ON m.aluno_id = u.id
                    WHERE m.curso_id = ? AND (m.aluno_id IS NULL OR m.aluno_id = ?)
                    ORDER BY m.data_postagem DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$curso_id, $user_id]);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Busca um material/atividade específico pelo seu ID.
     * @param PDO $pdo
     * @param int $id
     * @return array|null
     */
    public function findById(PDO $pdo, int $id): ?array {
        $stmt = $pdo->prepare("SELECT * FROM materiais_atividades WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Atualiza um material/atividade existente.
     * @param PDO $pdo
     * @return bool
     */
    public function update(PDO $pdo): bool {
        $sql = "UPDATE materiais_atividades SET titulo = ?, conteudo = ?, tipo = ?, aluno_id = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $alunoIdParaSalvar = empty($this->aluno_id) ? null : $this->aluno_id;
        return $stmt->execute([$this->titulo, $this->conteudo, $this->tipo, $alunoIdParaSalvar, $this->id]);
    }

    /**
     * Deleta um material/atividade pelo seu ID.
     * @param PDO $pdo
     * @param int $id
     * @return bool
     */
    public function delete(PDO $pdo, int $id): bool {
        $stmt = $pdo->prepare("DELETE FROM materiais_atividades WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
