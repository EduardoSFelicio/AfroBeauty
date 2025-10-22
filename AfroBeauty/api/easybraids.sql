-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 09/06/2025 às 17:10
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `easybraids`
--

CREATE DATABASE easybraids;
USE easybraids;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_administradores`
--

CREATE TABLE `tbl_administradores` (
  `id_adminstrador` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_agendamentos`
--

CREATE TABLE `tbl_agendamentos` (
  `id_agendamento` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_servico` int(11) DEFAULT NULL,
  `qtd_cliente` int(11) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `hora` time NOT NULL,
  `duracao_min` int(11) NOT NULL,
  `status` enum('pendente','confirmado','cancelado') DEFAULT 'pendente',
  `data_agendamento` date NOT NULL,
  `comentarios` varchar(600) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_bloqueios`
--

CREATE TABLE `tbl_bloqueios` (
  `id_bloqueio` int(11) NOT NULL,
  `id_trancista` int(11) NOT NULL,
  `data_bloqueada` date NOT NULL,
  `motivo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_clientes`
--

CREATE TABLE `tbl_clientes` (
  `id_cliente` int(11) NOT NULL,
  `email` varchar(40) NOT NULL,
  `nome` varchar(30) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `data_nascimento` date NOT NULL,
  `genero` varchar(20) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `foto_perfil` varchar(400) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `e_google` tinyint(1) DEFAULT 0,
  `data_criacao` date DEFAULT current_timestamp(),
  `favorito_tranca` varchar(250) NOT NULL,
  `favorito_trancista` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `tbl_clientes`
--

INSERT INTO `tbl_clientes` (`id_cliente`, `email`, `nome`, `telefone`, `data_nascimento`, `genero`, `senha`, `foto_perfil`, `endereco`, `e_google`, `data_criacao`, `favorito_tranca`, `favorito_trancista`) VALUES
(1, 'zbawe@gmail.com', 'zbwae', '', '0000-00-00', '', '$2y$10$McZLdMacMNOvwe8.C92kw.UuTpnn3R/jA6ZEKYiOo174iHbUcuMIK', 'uploads/682126b880bd5_foto_perfil.png', '', 0, '2025-05-11', '', ''),
(2, 'wafda@gmail.com', 'wafda', '', '0000-00-00', '', '$2y$10$SYz/x4qrjRCOYvtX5sEONuxjP2iJartGaSxi1Y9Be7MfahL/Uleha', 'assets/foto-padrao-cliente-1x.png', '', 0, '2025-05-11', '', ''),
(3, 'sapro@gmail.com', 'sapro@gmail.com', '', '0000-00-00', '', '$2y$10$YP.EUAPwlhm3JzSO7MC0jeg3nBW3PyzC.9WHdC.oPKqtALf4Fl4Sy', 'uploads/6821d92c4e811_foto_perfil.png', '', 0, '2025-05-12', '', ''),
(4, 'wat@gmail.com', 'wat', '', '0000-00-00', '', '$2y$10$fRVzhlNpysv2XxsxwY1pFe5HrJNmZhlYHva4Ok0/Phph3ys3E.mMm', 'uploads/6821db365290d_foto_perfil.png', '', 0, '2025-05-12', '', ''),
(5, 'ska@gmail.com', 'ska', '', '0000-00-00', '', '$2y$10$wfW7wIjxvDTGtqVsWhGNG.ztCcwjNrc4QlwDF0vL1BJ93.emS3oB6', 'assets/foto-padrao-cliente-1x.png', '', 0, '2025-05-12', '', ''),
(6, 'dcsw@gmail.com', 'csw', '', '0000-00-00', '', '$2y$10$i6Sv2PUvOkKM4jx.ExpwEuIu4/3pDGQRuc4lz.9paV2SDbKBcr6ly', 'assets/foto-padrao-cliente-1x.png', '', 0, '2025-05-12', '', ''),
(7, 'yikes@gmail.com', 'yikes', '', '0000-00-00', '', '$2y$10$uGc56ACfFx8LMgGY4PkSpOjS7JM/v0KRpaPdU/i7.JycLMVris04C', 'uploads/6821f88d62eaf_foto_perfil.png', '', 0, '2025-05-12', '', ''),
(8, 'awawwww@gmail.com', 'aww', '', '0000-00-00', '', '$2y$10$lIrST03fDBw5EY7CER18Y.Dm4ig3cPE8J7W3SmWTCBUgMmDbPRqpG', 'assets/foto-padrao-cliente-1x.png', '', 0, '2025-05-12', '', ''),
(9, 'cesarww@gmail.com', 'cesarww', '(92) 18412-5132', '2006-07-01', 'masculino', '$2y$10$PlbeEi3Cx2OIQI0aMfSHi.jvkcjVZ.wCDzfgPEtWOhUjb8UHsHwi2', 'assets/foto-padrao-cliente-1x.png', '', 0, '2025-05-12', '', ''),
(21, 'saproacc24@gmail.com', 'saproacc21', '(11) 92715-3231', '2001-06-21', 'naoBinario', '$2y$10$scmK9GhTDlu0e5kk.xjYgerqYTOs4uqxv2Dy9uLiH3bw9PTotdUwK', 'uploads/683b63071979e_foto_perfil.jpeg', '', 0, '2025-05-19', '', ''),
(26, 'aaee@gmail.com', 'aaee', '(11) 9232-3141', '2006-07-01', 'masculino', '$2y$10$rQ0uPyI2bpVM3KZ4KBZHFuCkkxTb1lgqOOKH14VVZqMokW7NELioy', 'assets/foto-padrao-cliente-1x.png', '', 0, '2025-05-25', '', ''),
(36, 'yolo@gmail.com', 'yolo', '(11) 49232-3141', '2006-07-01', 'masculino', '$2y$10$NlwNSFaYVVyYgk19xDsffOZM.IJN7ykee4yJH5WWsZ3o4lypU94Sm', 'uploads/6833d889c6c13_foto_perfil.png', '', 0, '2025-05-25', '', ''),
(63, 'giopereira25@gmail.com', 'Giovanna Pereira Santos', '(11) 93266-3400', '2001-02-24', 'feminino', '$2y$10$OF27Pn6Hr3AayIaBq4GN4uNJXwvireOSRWQwuUyxhha1Qfb1dcnLa', 'uploads/683ef08acd599_foto_perfil.jpeg', '', 0, '2025-06-03', '', ''),
(78, 'playergamef@gmail.com', 'Fabricio', '', '1970-07-13', 'masculino', '', 'uploads/68424b1e5a7ed_foto_perfil.jpg', '', 1, '2025-06-05', '', '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_clientes_deletados`
--

CREATE TABLE `tbl_clientes_deletados` (
  `id_cliente` int(11) NOT NULL,
  `email` varchar(40) NOT NULL,
  `nome` varchar(30) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `data_nascimento` date NOT NULL,
  `genero` varchar(20) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `foto_perfil` varchar(400) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `e_google` tinyint(1) DEFAULT 0,
  `data_criacao` date NOT NULL,
  `favorito_tranca` varchar(250) NOT NULL,
  `favorito_trancista` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `tbl_clientes_deletados`
--

INSERT INTO `tbl_clientes_deletados` (`id_cliente`, `email`, `nome`, `telefone`, `data_nascimento`, `genero`, `senha`, `foto_perfil`, `endereco`, `e_google`, `data_criacao`, `favorito_tranca`, `favorito_trancista`) VALUES
(77, 'lbras@gmail.com', 'L\'Braspital', '(11) 98235-3221', '2006-07-01', 'masculino', '$2y$10$zXy1cW2LUthNrWpxmIEzK.S7dsA9/y/FR94.tfs9u9gagiKG1VOVG', 'assets/foto-padrao-cliente-1x.png', '', 0, '2025-06-05', '', '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_disponibilidades`
--

CREATE TABLE `tbl_disponibilidades` (
  `id_disponibilidade` int(11) NOT NULL,
  `id_trancista` int(11) NOT NULL,
  `dia_semana` tinyint(4) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fim` time NOT NULL,
  `intervalo_min` int(11) NOT NULL DEFAULT 30
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbl_disponibilidades`
--

INSERT INTO `tbl_disponibilidades` (`id_disponibilidade`, `id_trancista`, `dia_semana`, `hora_inicio`, `hora_fim`, `intervalo_min`) VALUES
(29, 29, 0, '10:15:00', '19:00:00', 30),
(30, 29, 1, '07:00:00', '18:00:00', 30),
(31, 29, 2, '07:00:00', '18:00:00', 30),
(32, 29, 3, '07:00:00', '18:00:00', 30),
(33, 29, 4, '07:00:00', '18:00:00', 30),
(34, 29, 5, '07:00:00', '18:00:00', 30),
(35, 29, 6, '09:00:00', '22:00:00', 30);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_historico_clientes`
--

CREATE TABLE `tbl_historico_clientes` (
  `id_clientela` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_trancista` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_servicos`
--

CREATE TABLE `tbl_servicos` (
  `id_servico` int(11) NOT NULL,
  `id_trancista` int(11) DEFAULT NULL,
  `id_tranca` int(11) DEFAULT NULL,
  `preco` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_trancas`
--

CREATE TABLE `tbl_trancas` (
  `id_tranca` int(11) NOT NULL,
  `titulo` varchar(30) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `preco` double NOT NULL,
  `duracao_min` int(11) NOT NULL,
  `foto_tranca` varchar(400) DEFAULT NULL,
  `data_criacao` date NOT NULL DEFAULT current_timestamp(),
  `id_trancista` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `tbl_trancas`
--

INSERT INTO `tbl_trancas` (`id_tranca`, `titulo`, `descricao`, `preco`, `duracao_min`, `foto_tranca`, `data_criacao`, `id_trancista`) VALUES
(1, 'ttt', 'fadesc', 42, 0, 'assets/icone-chave-inglesa-4x.png', '2025-06-07', 29),
(2, 'Box braids', '', 230, 0, 'uploads/servico_68463322d655a.jpg', '2025-06-07', 29),
(5, 'titulo', 'aaa', 24, 0, 'assets/icone-chave-inglesa-4x.png', '2025-06-08', 29),
(6, 'titulo 2', 'a', 2424, 0, 'assets/icone-chave-inglesa-4x.png', '2025-06-08', 29),
(7, 'trwe', 'aa', 23, 0, 'assets/icone-chave-inglesa-4x.png', '2025-06-08', 29),
(8, 'nov', 'awa', 444, 0, 'assets/icone-chave-inglesa-4x.png', '2025-06-08', 29),
(9, 'ttaaaaaaaaa', 'a', 42, 0, 'assets/icone-chave-inglesa-4x.png', '2025-06-08', 29);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_trancistas`
--

CREATE TABLE `tbl_trancistas` (
  `id_trancista` int(11) NOT NULL,
  `nome` varchar(30) NOT NULL,
  `email` varchar(40) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `data_nascimento` date NOT NULL,
  `genero` varchar(20) NOT NULL,
  `cpfcnpj` varchar(20) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `foto_perfil` varchar(400) NOT NULL,
  `data_criacao` date DEFAULT current_timestamp(),
  `qtd_servicos` int(11) NOT NULL,
  `estabelecimento` varchar(3) NOT NULL DEFAULT 'nao',
  `residencial` varchar(4) NOT NULL DEFAULT 'nao',
  `media_notas` float NOT NULL,
  `galeria` varchar(400) NOT NULL,
  `link` varchar(400) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `tbl_trancistas`
--

INSERT INTO `tbl_trancistas` (`id_trancista`, `nome`, `email`, `senha`, `data_nascimento`, `genero`, `cpfcnpj`, `telefone`, `endereco`, `foto_perfil`, `data_criacao`, `qtd_servicos`, `estabelecimento`, `residencial`, `media_notas`, `galeria`, `link`) VALUES
(1, 'Eduardo Felício', 'dudulindao@gmail.com', 'dudulindo123', '2007-10-10', '', '5', '40028922', 'Rua do presidente', 'jdwy89ew7r983huh48t49802hew', '2025-02-05', 10, '0', 'nao', 10, 'y87264378ryheurgeqiueggedwu', 'www.trancistaAfroBeauty.com.br'),
(2, 'conta', 'conta_teste2@gmail.com', '$2y$10$nkasroF92KuugbxR5.bvWegHVVMH5xzCEmbqRun1jiaLcFhTbzi5C', '2006-07-01', 'feminino', '60.967.551/0001-50', '(11) 97623-2354', '', 'uploads/681cc4e83b353_foto_perfil.jpeg', '2025-05-08', 0, '0', 'nao', 0, '', ''),
(3, 'eua', 'eu@gmail.com', '$2y$10$Rn7FKfXqreBW.owwlFmfmO5/G7NlZSZhNWpSiBPcXkS45iY5sNOcu', '2006-02-01', 'feminino', '14.675.270/0002-98', '(11) 9284-8253', '', 'uploads/681d507aa3750_foto_perfil.jpeg', '2025-05-08', 0, '0', 'nao', 0, '', ''),
(5, 'eub', 'eub@gmail.com', '$2y$10$q0YKn7xgeOLbJXa18y4FPualswU4iTP45GV1bBj4BD1ghHo8JhiE6', '2005-02-02', 'feminino', '09.267.863/0001-02', '(11) 9827-4252', '', 'uploads/681d5641b4e9e_foto_perfil.png', '2025-05-08', 0, '0', 'nao', 0, '', ''),
(6, 'saproacc', 'saproacc@gmail.com', '$2y$10$mC53N//y5q3CxWRc6C1a3uuguD6PfBck4mpspw2HWtwHx2dRmg.h6', '2009-03-03', 'masculino', '60.879.848/0001-64', '(11) 9257-2535', '', 'uploads/681d58e374690_foto_perfil.png', '2025-05-08', 0, 'nao', 'nao', 0, '', ''),
(29, 'test23', 'test23@gmail.com', '$2y$10$3z9d7AhX2IhVn6ouqCZh.uzc72YxqTkeJNWKvazndnVwmD3gM5jea', '2006-07-01', 'masculino', '09.376.495/0001-22', '(11) 98333-4504', '', 'uploads/68437475c3995_foto_perfil.jpeg', '2025-06-06', 0, 'sim', 'sim', 0, '', ''),
(30, 'test24', 'test24@gmail.com', '$2y$10$4x/DVbwykWcenapl7o6bfe4r7jOKOZOTAdSTBedW8orK46kYodA8K', '2024-12-02', 'masculino', '00.394.445/0188-17', '(11) 98834-0531', '', 'uploads/6844e442ae557_foto_perfil.jpeg', '2025-06-07', 0, 'nao', 'nao', 0, '', '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_trancistas_deletados`
--

CREATE TABLE `tbl_trancistas_deletados` (
  `id_trancista` int(11) NOT NULL,
  `nome` varchar(30) NOT NULL,
  `email` varchar(40) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `data_nascimento` date NOT NULL,
  `genero` varchar(20) NOT NULL,
  `cpfcnpj` varchar(20) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `foto_perfil` varchar(400) NOT NULL,
  `data_criacao` date NOT NULL,
  `qtd_servicos` int(11) NOT NULL,
  `estabelecimento` varchar(3) NOT NULL DEFAULT 'nao',
  `residencial` varchar(4) NOT NULL DEFAULT 'nao',
  `media_notas` float NOT NULL,
  `galeria` varchar(400) NOT NULL,
  `link` varchar(400) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `tbl_trancistas_deletados`
--

INSERT INTO `tbl_trancistas_deletados` (`id_trancista`, `nome`, `email`, `senha`, `data_nascimento`, `genero`, `cpfcnpj`, `telefone`, `endereco`, `foto_perfil`, `data_criacao`, `qtd_servicos`, `estabelecimento`, `residencial`, `media_notas`, `galeria`, `link`) VALUES
(7, 'brasempboy', 'brasempboy@gmail.com', '$2y$10$cRLgHNih.CuqP.YwpXUjqOESvQRyzxQIsr2YYuErl0bvadaAZLa/G', '2006-01-01', 'masculino', '22.311.442/0001-91', '(11) 03824-8735', '', 'uploads/6825e50162e7c_foto_perfil.jpeg', '0000-00-00', 0, '0', 'nao', 0, '', ''),
(8, 'Brasiooooooooooooooaaaaab', 'brasi@gmail.com', '$2y$10$WuzS9AjWR8Vfz52m6cSPy.8T./O.D.0bgOS8wF269ZlW5psMhx5Mu', '2006-07-01', 'masculino', '61.695.227/0001-93', '(11) 87236-4526', '', 'uploads/683ca28719b53_foto_perfil.jpeg', '0000-00-00', 0, '0', 'nao', 0, '', ''),
(9, 'Pedro Henrique Souza', 'pedrohenrique1990@gmail.com', '$2y$10$25lWZ97CQerKS./VxsufMOfLd7AwaKbNKAOZRG.4ZqOHK/eFmfToy', '1990-04-01', 'masculino', '00.394.445/0188-17', '(11) 93444-1799', '', 'uploads/683eee7e07b56_foto_perfil.jpeg', '0000-00-00', 0, '0', 'nao', 0, '', '');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tbl_administradores`
--
ALTER TABLE `tbl_administradores`
  ADD PRIMARY KEY (`id_adminstrador`);

--
-- Índices de tabela `tbl_agendamentos`
--
ALTER TABLE `tbl_agendamentos`
  ADD PRIMARY KEY (`id_agendamento`),
  ADD KEY `id_servico` (`id_servico`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Índices de tabela `tbl_bloqueios`
--
ALTER TABLE `tbl_bloqueios`
  ADD PRIMARY KEY (`id_bloqueio`),
  ADD UNIQUE KEY `id_trancista` (`id_trancista`,`data_bloqueada`);

--
-- Índices de tabela `tbl_clientes`
--
ALTER TABLE `tbl_clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `tbl_clientes_deletados`
--
ALTER TABLE `tbl_clientes_deletados`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `tbl_disponibilidades`
--
ALTER TABLE `tbl_disponibilidades`
  ADD PRIMARY KEY (`id_disponibilidade`),
  ADD KEY `id_trancista` (`id_trancista`);

--
-- Índices de tabela `tbl_historico_clientes`
--
ALTER TABLE `tbl_historico_clientes`
  ADD PRIMARY KEY (`id_clientela`),
  ADD KEY `id_trancista` (`id_trancista`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Índices de tabela `tbl_servicos`
--
ALTER TABLE `tbl_servicos`
  ADD PRIMARY KEY (`id_servico`),
  ADD KEY `id_trancista` (`id_trancista`),
  ADD KEY `id_tranca` (`id_tranca`);

--
-- Índices de tabela `tbl_trancas`
--
ALTER TABLE `tbl_trancas`
  ADD PRIMARY KEY (`id_tranca`),
  ADD KEY `id_trancista` (`id_trancista`);

--
-- Índices de tabela `tbl_trancistas`
--
ALTER TABLE `tbl_trancistas`
  ADD PRIMARY KEY (`id_trancista`),
  ADD UNIQUE KEY `cpfcnpj` (`cpfcnpj`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `tbl_trancistas_deletados`
--
ALTER TABLE `tbl_trancistas_deletados`
  ADD PRIMARY KEY (`id_trancista`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `cpfcnpj` (`cpfcnpj`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tbl_administradores`
--
ALTER TABLE `tbl_administradores`
  MODIFY `id_adminstrador` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbl_agendamentos`
--
ALTER TABLE `tbl_agendamentos`
  MODIFY `id_agendamento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbl_bloqueios`
--
ALTER TABLE `tbl_bloqueios`
  MODIFY `id_bloqueio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbl_clientes`
--
ALTER TABLE `tbl_clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT de tabela `tbl_disponibilidades`
--
ALTER TABLE `tbl_disponibilidades`
  MODIFY `id_disponibilidade` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de tabela `tbl_historico_clientes`
--
ALTER TABLE `tbl_historico_clientes`
  MODIFY `id_clientela` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbl_servicos`
--
ALTER TABLE `tbl_servicos`
  MODIFY `id_servico` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbl_trancas`
--
ALTER TABLE `tbl_trancas`
  MODIFY `id_tranca` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `tbl_trancistas`
--
ALTER TABLE `tbl_trancistas`
  MODIFY `id_trancista` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `tbl_agendamentos`
--
ALTER TABLE `tbl_agendamentos`
  ADD CONSTRAINT `tbl_agendamentos_ibfk_1` FOREIGN KEY (`id_servico`) REFERENCES `tbl_servicos` (`id_servico`),
  ADD CONSTRAINT `tbl_agendamentos_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `tbl_clientes` (`id_cliente`);

--
-- Restrições para tabelas `tbl_bloqueios`
--
ALTER TABLE `tbl_bloqueios`
  ADD CONSTRAINT `tbl_bloqueios_ibfk_1` FOREIGN KEY (`id_trancista`) REFERENCES `tbl_trancistas` (`id_trancista`);

--
-- Restrições para tabelas `tbl_disponibilidades`
--
ALTER TABLE `tbl_disponibilidades`
  ADD CONSTRAINT `tbl_disponibilidades_ibfk_1` FOREIGN KEY (`id_trancista`) REFERENCES `tbl_trancistas` (`id_trancista`);

--
-- Restrições para tabelas `tbl_historico_clientes`
--
ALTER TABLE `tbl_historico_clientes`
  ADD CONSTRAINT `tbl_historico_clientes_ibfk_1` FOREIGN KEY (`id_trancista`) REFERENCES `tbl_trancistas` (`id_trancista`),
  ADD CONSTRAINT `tbl_historico_clientes_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `tbl_clientes` (`id_cliente`);

--
-- Restrições para tabelas `tbl_servicos`
--
ALTER TABLE `tbl_servicos`
  ADD CONSTRAINT `tbl_servicos_ibfk_1` FOREIGN KEY (`id_trancista`) REFERENCES `tbl_trancistas` (`id_trancista`),
  ADD CONSTRAINT `tbl_servicos_ibfk_2` FOREIGN KEY (`id_tranca`) REFERENCES `tbl_trancas` (`id_tranca`);

--
-- Restrições para tabelas `tbl_trancas`
--
ALTER TABLE `tbl_trancas`
  ADD CONSTRAINT `tbl_trancas_ibfk_1` FOREIGN KEY (`id_trancista`) REFERENCES `tbl_trancistas` (`id_trancista`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
