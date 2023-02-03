-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Creato il: Feb 21, 2018 alle 10:31
-- Versione del server: 5.7.21-0ubuntu0.16.04.1
-- Versione PHP: 7.0.25-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `websync_framework_sito352`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_ec_customers`
--

CREATE TABLE `tbl_ec_customers` (
  `id` int(8) NOT NULL,
  `username` varchar(20) DEFAULT NULL,
  `password` varchar(40) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `surname` varchar(50) DEFAULT NULL,
  `street` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `zip_code` varchar(10) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `id_state` int(8) DEFAULT '0',
  `name_dest` varchar(255) DEFAULT NULL,
  `street_dest` varchar(100) DEFAULT NULL,
  `city_dest` varchar(100) DEFAULT NULL,
  `zip_code_dest` varchar(10) DEFAULT NULL,
  `province_dest` varchar(100) DEFAULT NULL,
  `id_state_dest` int(8) NOT NULL DEFAULT '0',
  `email` varchar(255) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `codice_fiscale` varchar(40) DEFAULT NULL,
  `partita_iva` varchar(40) DEFAULT NULL,
  `luogo_nascita` varchar(255) DEFAULT NULL,
  `data_nascita` date DEFAULT NULL,
  `banca` varchar(255) DEFAULT NULL,
  `filiale` varchar(255) DEFAULT NULL,
  `iban` varchar(50) DEFAULT NULL,
  `privacy` int(1) NOT NULL DEFAULT '0',
  `hash` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `active` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `tbl_ec_customers`
--
ALTER TABLE `tbl_ec_customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cognome` (`surname`),
  ADD KEY `user` (`username`),
  ADD KEY `hash` (`hash`),
  ADD KEY `password` (`password`),
  ADD KEY `created` (`created`),
  ADD KEY `email` (`email`(191));

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `tbl_ec_customers`
--
ALTER TABLE `tbl_ec_customers`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
