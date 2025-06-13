CREATE DATABASE IF NOT EXISTS sga DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE sga;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


CREATE TABLE `cursos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `professor_id` int(11) NOT NULL,
  `codigo_turma` varchar(10) DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `cursos` (`id`, `titulo`, `descricao`, `professor_id`, `codigo_turma`, `data_criacao`) VALUES
(3, 'Java', 'Aulas ', 1, 'MV2UQE', '2025-06-11 06:45:29');

CREATE TABLE `entregas_atividades` (
  `id` int(11) NOT NULL,
  `atividade_id` int(11) NOT NULL,
  `aluno_id` int(11) NOT NULL,
  `arquivo_entrega` varchar(255) DEFAULT NULL COMMENT 'Caminho para o arquivo',
  `texto_entrega` text DEFAULT NULL COMMENT 'Resposta em texto',
  `data_entrega` timestamp NOT NULL DEFAULT current_timestamp(),
  `nota` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `inscricoes_cursos` (
  `id` int(11) NOT NULL,
  `aluno_id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `data_inscricao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `materiais_atividades` (
  `id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `conteudo` text DEFAULT NULL,
  `tipo` enum('material','atividade') NOT NULL,
  `data_postagem` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `materiais_atividades` (`id`, `curso_id`, `titulo`, `conteudo`, `tipo`, `data_postagem`) VALUES
(3, 3, 'Lista 1', ' ', 'atividade', '2025-06-11 06:45:42');

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `perfil` enum('aluno','professor','admin') NOT NULL,
  `cpf` varchar(14) NOT NULL COMMENT 'Formato com máscara: 123.456.789-00',
  `data_nascimento` date NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha_hash`, `perfil`, `cpf`, `data_nascimento`, `data_cadastro`) VALUES
(1, 'Professor Admin', 'prof@sga.com', '$2y$10$d4IDd3DL6fAbCrO4bMa/6ez2uzBX3DEhrGg523YGbO4A5HqNuyF6m', 'professor', '111.111.111-11', '1990-01-01', '2025-06-11 04:48:35'),
(2, 'Aluno Teste', 'aluno@sga.com', '$2y$10$sn1m4vppYbv7bMyXZRWt2.XauteInZQ/yBdXSwzceYCevXpoeNDuq', 'aluno', '222.222.222-22', '2000-05-10', '2025-06-11 04:48:35'),
(3, 'Martin lutero', 'teste@gmail.com', '$2y$10$RgSojU23VtVBQhTWLt1dqukFfVDLWi7Lo/fmm0gyjLx.913fCXAEK', 'aluno', '333.333.333-33', '2002-10-28', '2025-06-11 05:24:28');

ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_turma` (`codigo_turma`),
  ADD KEY `professor_id` (`professor_id`);

ALTER TABLE `entregas_atividades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `atividade_id` (`atividade_id`),
  ADD KEY `aluno_id` (`aluno_id`);

ALTER TABLE `inscricoes_cursos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `aluno_curso_unique` (`aluno_id`,`curso_id`),
  ADD KEY `curso_id` (`curso_id`);

ALTER TABLE `materiais_atividades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `curso_id` (`curso_id`);

ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `cpf` (`cpf`);

ALTER TABLE `cursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `entregas_atividades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `inscricoes_cursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `materiais_atividades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `cursos`
  ADD CONSTRAINT `cursos_ibfk_1` FOREIGN KEY (`professor_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

ALTER TABLE `entregas_atividades`
  ADD CONSTRAINT `entregas_atividades_ibfk_1` FOREIGN KEY (`atividade_id`) REFERENCES `materiais_atividades` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `entregas_atividades_ibfk_2` FOREIGN KEY (`aluno_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

ALTER TABLE `inscricoes_cursos`
  ADD CONSTRAINT `inscricoes_cursos_ibfk_1` FOREIGN KEY (`aluno_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inscricoes_cursos_ibfk_2` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE;

ALTER TABLE `materiais_atividades`
  ADD CONSTRAINT `materiais_atividades_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE;
COMMIT;