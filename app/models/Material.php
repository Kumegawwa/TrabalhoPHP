<?php

class Material {
    public ?int $id = null;
    public ?int $curso_id = null;
    public ?string $titulo = null;
    public ?string $conteudo = null;
    public ?string $tipo = null;
    // A propriedade $aluno_id foi removida daqui.

    /**
     * Cria um novo material/atividade e retorna o seu ID para uso posterior.
     * @param PDO $pdo
     * @return int|null O ID do material recém-criado ou null em caso de falha.
     */
    public function create(PDO $pdo): ?int {
        $sql = "INSERT INTO materiais_atividades (curso_id, titulo, conteudo, tipo) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$this->curso_id, $this->titulo, $this->conteudo, $this->tipo])) {
            return (int)$pdo->lastInsertId();
        }
        return null;
    }

    /**
     * Busca materiais de um curso com lógica de permissão para multi-atribuição.
     * @param PDO $pdo
     * @param int $curso_id
     * @param int $user_id
     * @param string $user_profile
     * @return array
     */
    public function getByCursoId(PDO $pdo, int $curso_id, int $user_id, string $user_profile): array {
        // Professor vê todos os materiais e a contagem de atribuições.
        if ($user_profile === 'professor') {
            $sql = "SELECT m.*, (SELECT COUNT(*) FROM atividade_atribuicoes WHERE material_id = m.id) as atribuicoes_count
                    FROM materiais_atividades m 
                    WHERE m.curso_id = ? 
                    ORDER BY m.data_postagem DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$curso_id]);
        } else { // Aluno
            // Lógica: um material é para todos SE ele não tiver NENHUMA atribuição na tabela 'atividade_atribuicoes'.
            // Se tiver atribuições, o aluno só o vê se uma delas for para ele.
            $sql = "SELECT m.* FROM materiais_atividades m
                    WHERE m.curso_id = ? 
                    AND (
                        NOT EXISTS (SELECT 1 FROM atividade_atribuicoes WHERE material_id = m.id)
                        OR 
                        EXISTS (SELECT 1 FROM atividade_atribuicoes WHERE material_id = m.id AND aluno_id = ?)
                    )
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
        $sql = "UPDATE materiais_atividades SET titulo = ?, conteudo = ?, tipo = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$this->titulo, $this->conteudo, $this->tipo, $this->id]);
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
