-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Creato il: Apr 23, 2020 alle 09:57
-- Versione del server: 5.7.29-0ubuntu0.18.04.1
-- Versione PHP: 7.2.24-0ubuntu0.18.04.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `phprojekt.altervista_framework451`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl451_slides_home_rev_layers`
--

CREATE TABLE `tbl451_slides_home_rev_layers` (
  `id` int(8) NOT NULL,
  `slide_id` int(8) NOT NULL DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `content_it` text,
  `content_en` text,
  `content_de` text,
  `content_fr` text,
  `template_it` text,
  `template_en` text,
  `template_de` text,
  `template_fr` text,
  `content_es` text,
  `template_es` text,
  `filename` varchar(255) DEFAULT NULL,
  `org_filename` varchar(255) DEFAULT NULL,
  `ordering` int(8) NOT NULL DEFAULT '0',
  `url` varchar(255) DEFAULT NULL,
  `target` varchar(20) DEFAULT NULL,
  `type` int(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `tbl451_slides_home_rev_layers`
--
ALTER TABLE `tbl451_slides_home_rev_layers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `active` (`active`),
  ADD KEY `ordering` (`ordering`),
  ADD KEY `slide_id` (`slide_id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `tbl451_slides_home_rev_layers`
--
ALTER TABLE `tbl451_slides_home_rev_layers`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
