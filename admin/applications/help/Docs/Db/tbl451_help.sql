-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Creato il: Mag 02, 2020 alle 16:22
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
-- Struttura della tabella `tbl451_help`
--

CREATE TABLE `tbl451_help` (
  `id` int(11) NOT NULL,
  `title_it` text,
  `content_it` mediumtext,
  `title_en` text,
  `content_en` mediumtext,
  `title_de` text,
  `content_de` mediumtext,
  `title_fr` text,
  `content_fr` mediumtext,
  `ordering` int(8) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `active` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `tbl451_help`
--

INSERT INTO `tbl451_help` (`id`, `title_it`, `content_it`, `title_en`, `content_en`, `title_de`, `content_de`, `title_fr`, `content_fr`, `ordering`, `created`, `active`) VALUES
(1, 'Variabili ambiente', '<p>In molti casi &egrave; possibile inserire nei contenuti (titoli, url, testi) delle speciali stringhe di caratteri che saranno sostituite (parsate) con un contenuto generato dinamicamente dal sistema, e che pu&ograve; essere gestito generalmente da un file di configurazione come una variabile.<br />L\'esempio pi&ugrave; semplice &egrave; la stringa <strong>%URLSITE%</strong>. Se si inserisce tale stringa in un campo url - per esempio - nelle pagine generate nel front-end tale stringa sar&agrave; sostituita con il link corrente del sito.<br /><strong>Ecco un elenco delle stringhe da poter utilizzare:</strong></p>\r\n<p><strong>%URLSITE% </strong>= URL corrente del sito es: http://www.miosito.it<strong><br /></strong></p>', 'Variabili ambiente', '<p>In molti casi &egrave; possibile inserire nei contenuti (titoli, url, testi) delle speciali stringhe di caratteri che saranno sostituite (parsate) con un contenuto generato dinamicamente dal sistema, e che pu&ograve; essere gestito generalmente da un file di configurazione come una variabile.<br />L\'esempio pi&ugrave; semplice &egrave; la stringa <strong>%URLSITE%</strong>. Se si inserisce tale stringa in un campo url - per esempio - nelle pagine generate nel front-end tale stringa sar&agrave; sostituita con il link corrente del sito.<br /><strong>Ecco un elenco delle stringhe da poter utilizzare:</strong></p>\r\n<p><strong>%URLSITE% </strong>= URL corrente del sito es: http://www.miosito.it</p>', NULL, NULL, NULL, NULL, 1, '2016-10-05 15:30:15', 1);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `tbl451_help`
--
ALTER TABLE `tbl451_help`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
