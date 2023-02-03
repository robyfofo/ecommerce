-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Creato il: Mag 27, 2019 alle 10:07
-- Versione del server: 5.7.26-0ubuntu0.18.04.1
-- Versione PHP: 7.2.17-0ubuntu0.18.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `2019_pasasport`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_ec_subcategories`
--

CREATE TABLE `tbl_ec_subcategories` (
  `id` int(8) NOT NULL,
  `parent` int(8) NOT NULL DEFAULT '0',
  `title_meta_it` varchar(255) DEFAULT NULL,
  `title_meta_en` varchar(255) DEFAULT NULL,
  `title_seo_it` varchar(255) DEFAULT NULL,
  `title_seo_en` varchar(255) DEFAULT NULL,
  `title_it` varchar(255) DEFAULT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `ordering` int(8) NOT NULL DEFAULT '0',
  `filename` varchar(255) DEFAULT NULL,
  `org_filename` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `tbl_ec_subcategories`
--
ALTER TABLE `tbl_ec_subcategories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent` (`parent`),
  ADD KEY `active` (`active`),
  ADD KEY `ordering` (`ordering`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `tbl_ec_subcategories`
--
ALTER TABLE `tbl_ec_subcategories`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
