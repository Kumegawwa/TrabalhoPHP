<?php

class AtividadeAtribuicao {

    /**
     * Cria uma nova atribuição de atividade para um aluno.
     * @param PDO $pdo
     * @param int $material_id
     * @param int $aluno_id
     * @return bool
     */
    public function create(PDO $pdo, int $material_id, int $aluno_id): bool {
        $sql = "INSERT INTO atividade_atribuicoes (material_id, aluno_id) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$material_id, $aluno_id]);
    }

    /**
     * Deleta todas as atribuições de uma determinada atividade.
     * Usado ao atualizar uma atividade para limpar as atribuições antigas.
     * @param PDO $pdo
     * @param int $material_id
     * @return bool
     */
    public function deleteByMaterial(PDO $pdo, int $material_id): bool {
        $sql = "DELETE FROM atividade_atribuicoes WHERE material_id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$material_id]);
    }

    /**
     * Busca os IDs dos alunos para quem uma atividade foi atribuída.
     * @param PDO $pdo
     * @param int $material_id
     * @return array
     */
    public function findAlunosByMaterial(PDO $pdo, int $material_id): array {
        $sql = "SELECT aluno_id FROM atividade_atribuicoes WHERE material_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$material_id]);
        // Retorna um array simples de IDs [1, 5, 12]
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}