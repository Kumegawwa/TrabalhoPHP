-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS sga CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sga;

-- Estrutura da tabela `atividade_atribuicoes`
CREATE TABLE `atividade_atribuicoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `material_id` int(11) NOT NULL,
  `aluno_id` int(11) NOT NULL,
  `data_atribuicao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `material_aluno_unique` (`material_id`,`aluno_id`),
  KEY `aluno_id` (`aluno_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estrutura da tabela `cursos`
CREATE TABLE `cursos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `professor_id` int(11) NOT NULL,
  `codigo_turma` varchar(10) DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo_turma` (`codigo_turma`),
  KEY `professor_id` (`professor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estrutura da tabela `entregas_atividades`
CREATE TABLE `entregas_atividades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `atividade_id` int(11) NOT NULL,
  `aluno_id` int(11) NOT NULL,
  `arquivo_entrega` varchar(255) DEFAULT NULL COMMENT 'Caminho para o arquivo',
  `texto_entrega` text DEFAULT NULL COMMENT 'Resposta em texto',
  `data_entrega` timestamp NOT NULL DEFAULT current_timestamp(),
  `nota` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `atividade_id` (`atividade_id`),
  KEY `aluno_id` (`aluno_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estrutura da tabela `inscricoes_cursos`
CREATE TABLE `inscricoes_cursos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aluno_id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `data_inscricao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `aluno_curso_unique` (`aluno_id`,`curso_id`),
  KEY `curso_id` (`curso_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estrutura da tabela `materiais_atividades`
CREATE TABLE `materiais_atividades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `curso_id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `conteudo` text DEFAULT NULL,
  `tipo` enum('material','atividade') NOT NULL,
  `data_postagem` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `curso_id` (`curso_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estrutura da tabela `usuarios`
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `perfil` enum('aluno','professor','admin') NOT NULL,
  `cpf` varchar(14) NOT NULL COMMENT 'Formato com máscara: 123.456.789-00',
  `data_nascimento` date NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `cpf` (`cpf`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserção de dados
INSERT INTO `cursos` (`id`, `titulo`, `descricao`, `professor_id`, `codigo_turma`, `data_criacao`) VALUES
(3, 'Java', 'Aulas ', 1, 'MV2UQE', '2025-06-11 06:45:29');

INSERT INTO `inscricoes_cursos` (`id`, `aluno_id`, `curso_id`, `data_inscricao`) VALUES
(4, 2, 3, '2025-06-11 18:16:27'),
(5, 5, 3, '2025-06-11 18:19:21');

INSERT INTO `materiais_atividades` (`id`, `curso_id`, `titulo`, `conteudo`, `tipo`, `data_postagem`) VALUES
(3, 3, 'Lista 1', ' h', 'atividade', '2025-06-11 06:45:42');

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha_hash`, `perfil`, `cpf`, `data_nascimento`, `data_cadastro`) VALUES
(1, 'Professor Admin', 'prof@sga.com', '$2y$10$d4IDd3DL6fAbCrO4bMa/6ez2uzBX3DEhrGg523YGbO4A5HqNuyF6m', 'professor', '111.111.111-11', '1990-01-01', '2025-06-11 04:48:35'),
(2, 'Aluno Teste', 'aluno@sga.com', '$2y$10$sn1m4vppYbv7bMyXZRWt2.XauteInZQ/yBdXSwzceYCevXpoeNDuq', 'aluno', '222.222.222-22', '2000-05-10', '2025-06-11 04:48:35'),
(3, 'Martin lutero', 'teste@gmail.com', '$2y$10$RgSojU23VtVBQhTWLt1dqukFfVDLWi7Lo/fmm0gyjLx.913fCXAEK', 'aluno', '333.333.333-33', '2002-10-28', '2025-06-11 05:24:28'),
(5, 'Lucas', 'teste1@gmail.com', '$2y$10$E/dFgL/VnAPo5/SprkhJc.d.Ko6OoMX4OpjUIcE00zTvRthsTGBce', 'aluno', '444444444444', '1979-01-01', '2025-06-11 16:52:35');

-- Adição das constraints (chaves estrangeiras)
ALTER TABLE `atividade_atribuicoes`
  ADD CONSTRAINT `atividade_atribuicoes_ibfk_1` FOREIGN KEY (`material_id`) REFERENCES `materiais_atividades` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `atividade_atribuicoes_ibfk_2` FOREIGN KEY (`aluno_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

ALTER TABLE `cursos`
  ADD CONSTRAINT `cursos_ibfk_1` FOREIGN KEY (`professor_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

ALTER TABLE `entregas_atividades`
  ADD CONSTRAINT `entregas_atividades_ibfk_1` FOREIGN KEY (`atividade_id`) REFERENCES `materiais_atividades` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `entregas_atividades_ibfk_2` FOREIGN KEY (`aluno_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

ALTER TABLE `inscricoes_cursos`
  ADD CONSTRAINT `inscricoes_cursos_ibfk_1` FOREIGN KEY (`aluno_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inscricoes_cursos_ibfk_2` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE;
