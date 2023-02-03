-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Creato il: Apr 04, 2019 alle 09:32
-- Versione del server: 5.7.25-0ubuntu0.18.04.2
-- Versione PHP: 7.2.15-0ubuntu0.18.04.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `2019_cdrsangiuseppe`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_pagetemplates`
--

CREATE TABLE `tbl_pagetemplates` (
  `id` int(8) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `content` text,
  `template` char(50) NOT NULL,
  `ordering` int(4) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `predefinito` int(1) NOT NULL,
  `css_links` varchar(255) NOT NULL,
  `Jscript_init_code` varchar(255) NOT NULL,
  `jscript_links` varchar(255) NOT NULL,
  `jscript_last_links` varchar(255) NOT NULL,
  `base_tpl_page` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `active` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `tbl_pagetemplates`
--

INSERT INTO `tbl_pagetemplates` (`id`, `title`, `content`, `template`, `ordering`, `filename`, `predefinito`, `css_links`, `Jscript_init_code`, `jscript_links`, `jscript_last_links`, `base_tpl_page`, `created`, `active`) VALUES
(1, 'Default', 'Template di default', 'template-default', 1, '', 1, '', '', '', '', '', '2019-04-04 07:22:38', 1);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `tbl_pagetemplates`
--
ALTER TABLE `tbl_pagetemplates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `active` (`active`),
  ADD KEY `ordering` (`ordering`),
  ADD KEY `predefinito` (`predefinito`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `tbl_pagetemplates`
--
ALTER TABLE `tbl_pagetemplates`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
