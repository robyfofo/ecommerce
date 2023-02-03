-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Creato il: Apr 03, 2019 alle 12:02
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
-- Struttura della tabella `tbl_pages`
--

CREATE TABLE `tbl_pages` (
  `id` int(8) NOT NULL,
  `id_user` int(8) NOT NULL DEFAULT '0',
  `parent` int(8) NOT NULL,
  `meta_title_it` varchar(255) DEFAULT NULL,
  `meta_title_en` varchar(255) DEFAULT NULL,
  `meta_description_it` varchar(300) DEFAULT NULL,
  `meta_description_en` varchar(300) DEFAULT NULL,
  `meta_keyword_it` varchar(255) DEFAULT NULL,
  `meta_keyword_en` varchar(255) DEFAULT NULL,
  `title_seo_it` varchar(255) DEFAULT NULL,
  `title_seo_en` varchar(255) DEFAULT NULL,
  `title_it` varchar(255) DEFAULT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `ordering` int(8) NOT NULL DEFAULT '0',
  `id_template` int(8) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `menu` int(1) NOT NULL DEFAULT '0',
  `alias` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `target` varchar(20) DEFAULT NULL,
  `jscript_init_code` mediumtext,
  `filename` varchar(255) DEFAULT NULL,
  `org_filename` varchar(255) DEFAULT NULL,
  `filename1` varchar(255) DEFAULT NULL,
  `org_filename1` varchar(255) DEFAULT NULL,
  `access_read` text,
  `access_write` text,
  `created` datetime NOT NULL,
  `active` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `tbl_pages`
--

INSERT INTO `tbl_pages` (`id`, `id_user`, `parent`, `meta_title_it`, `meta_title_en`, `meta_description_it`, `meta_description_en`, `meta_keyword_it`, `meta_keyword_en`, `title_seo_it`, `title_seo_en`, `title_it`, `title_en`, `ordering`, `id_template`, `type`, `menu`, `alias`, `url`, `target`, `jscript_init_code`, `filename`, `org_filename`, `filename1`, `org_filename1`, `access_read`, `access_write`, `created`, `active`) VALUES
(1, 1, 0, 'Privacy Policy', 'Privacy Policy', 'La nostra privacy policy', 'Own privacy policy', 'privacy, policy', 'privacy, policy', 'privacy-policy', 'privacy-policy', 'Privacy Policy', 'Privacy Policy ', 0, 1, 'default', 0, 'privacy-policy', '', '', '', '', '', NULL, NULL, 'none', 'none', '2015-09-16 13:42:30', 1),
(2, 1, 0, 'Cookies Policy', 'Cookies Policy', NULL, NULL, NULL, NULL, 'Cookies Policy', 'Cookies Policy', 'Cookies Policy', 'Cookies Policy', 1, 1, 'default', 0, 'cookies-policy', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, '2015-09-16 13:42:30', 0);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `tbl_pages`
--
ALTER TABLE `tbl_pages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ordering` (`ordering`),
  ADD KEY `parent` (`parent`),
  ADD KEY `id_template` (`id_template`),
  ADD KEY `active` (`active`),
  ADD KEY `menu` (`menu`),
  ADD KEY `id_user` (`id_user`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `tbl_pages`
--
ALTER TABLE `tbl_pages`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
