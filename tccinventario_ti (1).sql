-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 18/09/2026 às 19:28
-- Versão do servidor: 8.3.0
-- Versão do PHP: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `tccinventario_ti`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `equipamento`
--

DROP TABLE IF EXISTS `equipamento`;
CREATE TABLE IF NOT EXISTS `equipamento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `hostname` varchar(100) DEFAULT NULL,
  `ipv4` varchar(15) DEFAULT NULL,
  `mac` varchar(17) DEFAULT NULL,
  `patrimonio` varchar(100) DEFAULT NULL,
  `local` varchar(100) DEFAULT NULL,
  `atividade` enum('Em uso','Disponível','Descarte','Manutenção') DEFAULT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `tipo_equipamento` varchar(100) DEFAULT NULL,
  `data_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `sistema_operacional` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `equipamento`
--

INSERT INTO `equipamento` (`id`, `hostname`, `ipv4`, `mac`, `patrimonio`, `local`, `atividade`, `marca`, `tipo_equipamento`, `data_registro`, `sistema_operacional`) VALUES
(7, 'Lab1-28', '192.168.56.1', 'xxxxx', 'PAT12345', 'Laboratório 1', 'Descarte', 'Dell', 'Notebook', '2026-07-08 12:03:06', 'Windows 11'),
(4, 'computador_giulia', '192.168.20.102', 'AC:F2:3C:3D:4C:84', '---', 'Dispositivo Móvel', 'Em uso', 'Dell', 'Computador', '2026-06-02 02:08:19', 'Microsoft Windows 11 24H2 - 25H2 (precisão: 100%)'),
(5, 'Lab1-25', '192.168.0.35', '10:EE:25:30:09:AC', 'PAT12345', 'Laboratório 1', 'Disponível', 'Dell', 'Computador', '2026-06-19 19:25:56', 'Windows 11');

-- --------------------------------------------------------

--
-- Estrutura para tabela `manutencao`
--

DROP TABLE IF EXISTS `manutencao`;
CREATE TABLE IF NOT EXISTS `manutencao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_equipamento` int DEFAULT NULL,
  `id_usuario` int DEFAULT NULL,
  `tipo_manutencao` enum('Preventiva','Software','Hardware','Rede') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `custo` int DEFAULT NULL,
  `descricao` longtext,
  `data_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_equipamento` (`id_equipamento`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `manutencao`
--

INSERT INTO `manutencao` (`id`, `id_equipamento`, `id_usuario`, `tipo_manutencao`, `custo`, `descricao`, `data_registro`) VALUES
(4, 4, 36, 'Preventiva', 0, 'Limpeza do pc', '2026-06-02 13:41:26'),
(6, 4, 36, 'Rede', 0, 'Ajuste no wifi', '2026-07-06 18:37:56');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `email` varchar(1000) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `nome` varchar(1000) DEFAULT NULL,
  `id` int NOT NULL AUTO_INCREMENT,
  `cpf` varchar(14) DEFAULT NULL,
  `data_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `cargo` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`email`, `senha`, `nome`, `id`, `cpf`, `data_registro`, `cargo`) VALUES
('admin@iffar.edu.br', '$2y$10$e4/3lSt2oXmiagN3ujCzcu/fvePrMUo68kPijO0bTJX4s02f9BODW', 'Administrador', 36, '000.000.000-00', '2026-05-22 05:46:04', 'A'),
('giulia.04908@aluno.iffar.edu.br', '$2y$10$wZWNomQr6ZQTNebujkKyzuRbTAhldb8RApwoH9hlyABBduU44FuQ6', 'Giulia Teles Moccelin Titello', 53, '049.085.930-51', '2026-07-07 12:04:18', 'A'),
('usuario.comum@iffar.edu.br', '$2y$10$MWRU4sUMY91CtDEOWH4Qwea9.ygAd90hOgIKl6c3wWcKcmGKIRwIG', 'Usuário Comum', 52, '000.000.000-01', '2026-07-06 17:56:53', 'U');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
