-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Creato il: Feb 12, 2018 alle 10:12
-- Versione del server: 5.7.21-0ubuntu0.16.04.1
-- Versione PHP: 7.0.22-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `websync_framework_sito350`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_pages_resources`
--

CREATE TABLE `tbl_pages_resources` (
  `id` int(8) NOT NULL,
  `id_owner` int(8) NOT NULL DEFAULT '0',
  `resource_type` varchar(255) NOT NULL,
  `title_it` varchar(255) NOT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `title_es` varchar(255) DEFAULT NULL,
  `title_fr` varchar(255) DEFAULT NULL,
  `title_pl` varchar(255) DEFAULT NULL,
  `content_it` text,
  `content_en` text,
  `content_fr` text,
  `content_es` text,
  `content_pl` text,
  `filename` varchar(255) DEFAULT NULL,
  `org_filename` varchar(255) DEFAULT NULL,
  `code` text,
  `extension` varchar(40) DEFAULT NULL,
  `size_file` varchar(20) DEFAULT NULL,
  `size_image` varchar(40) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `ordering` int(4) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `active` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `tbl_pages_resources`
--
ALTER TABLE `tbl_pages_resources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `active` (`active`),
  ADD KEY `id_folder` (`id_owner`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `tbl_pages_resources`
--
ALTER TABLE `tbl_pages_resources`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
