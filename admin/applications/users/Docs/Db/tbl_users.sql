-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Creato il: Mar 26, 2019 alle 10:14
-- Versione del server: 5.7.25-0ubuntu0.18.04.2
-- Versione PHP: 7.2.15-0ubuntu0.18.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `2019_sangiuseppe`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(8) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `surname` varchar(50) DEFAULT NULL,
  `street` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `zip_code` varchar(10) NOT NULL,
  `province` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telephone` varchar(20) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `fax` varchar(20) NOT NULL,
  `skype` varchar(100) NOT NULL,
  `avatar` blob NOT NULL,
  `avatar_info` text NOT NULL,
  `template` varchar(100) NOT NULL,
  `active` int(1) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `is_root` int(1) NOT NULL,
  `id_level` int(8) NOT NULL,
  `hash` varchar(50) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `name`, `surname`, `street`, `city`, `zip_code`, `province`, `state`, `email`, `telephone`, `mobile`, `fax`, `skype`, `avatar`, `avatar_info`, `template`, `active`, `username`, `password`, `is_root`, `id_level`, `hash`, `created`) VALUES
(1, 'Websync', 'Programmazione', 'Via Maiella, 8', 'Verona ', '37132', 'VR', 'Italia', 'programmazione@websync.it', '045.973604', '045.973604', '045.8937434', 'Skype account', 0x89504e470d0a1a0a0000000d49484452000000c4000000ba0803000000c65012e800000033504c5445000000b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b273a196720000001074524e530010203040506070808f9fafbfcfdfef231a828a000003354944415478daeddd5d12db200c0460f1631b630c7bffd3f6a533ea43a734098ed896ef063b5820634864596884f8931346f1c805bfaa256d5e78f8a3e0f76ade4812dcf8a3f973c48cbe7ab8992314fc9d96668de10bfe5e3b64422ee1357794d9848a979d4ea692f08e3bc83cdc85f7b45d66116ebc2dcd92a1e103993883cac419549e22037b0a776380434c150cb189a11363342f66368c728b15d7304c122317060a622262a422262a86da09baefaee678ab5a25de8150cd590c04fd501c18af114f4d6a376c38589b8f8c4778f92287679cf2453b9e51cd5a3fd636104f4906fd2bf3fc74e2318e607ba02bf296844abc25a10a6ff3a71a715d2bc75bd72a1ab4e1b4ed389e94785b5895796758555608835753c31630e1512bc40ab142ac102bc463d68abd428c53562b4ef05244f37a7aac8d02d3cf44ca0bff6a87b58df9024f3cc3aac63b39a9cb607262aa6c11feca2eebebe96b0a6f49a883f73b910a78c6c57bdc49ed0487c4bb9cf03f4fd73a3ef7ba04f09f29f5c41dacba78573ab5119f755295b7acd5c1db7228d7781b589546cfaffc439144e887a2b975456d8ad3d655ecdc00ffe5d3487cc94e9dbcf76795abbc55ad2241e7d7770e7998e82ff8ef622f34ebdd01fb9ebc3a99c2657b8bc87e9e3d641681b820d4415c10aa106cd274f986379c329783f86152d562a9b61f8a26d3f104fbc77d8d779d5385777e5595bfaec559bc94daef41dd329ddb64b3c9fe70630b0419b852b88cf7b428b388158af3876fdd894fd428f68e860f9520b6f68a01b21733eea818244731e153c34065b3fe3dfabef97ebddeed379ed072902f09b9e131f7ee0c06816f38626710e6af0e7f540c64f1571bdb85efaac98f5e132a0c5c9b0c132f58a9c999b7477334562e5598bbe267111aa650de8fa111ec9560530bf6b5110a66d3d280f7667b350ed8beb0773a836b2a5683116e4c2d49dfd630b9cb4947c2fceed0db1866d04227037d8a0cd0a7c8007d8a13043a67387770b95de7502585dcf9fac961e35be4fab75f7c03a1b333bb72f022ca8353ee0c04dd50b8065289779d5355435ca0a52d5403e89fa7085e458fb4f26ada8313737a1894575c21568871d20ab142ac102bc40ab142fc2f214224e6e5dff0032f4e78546f32bbc00000000049454e44ae426082, 'a:3:{s:4:\"type\";s:9:\"image/png\";s:4:\"nome\";s:16:\"user-profile.png\";s:4:\"size\";i:969;}', 'default', 1, 'websync', '$2y$10$VwagI/wedmplmDYwuYmp9eOYF3wvAAvPgV7JjImUTQqsh.iwqz/qa', 1, 0, '', '2016-10-18 00:00:00');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `password` (`password`),
  ADD KEY `email` (`email`),
  ADD KEY `nome` (`name`),
  ADD KEY `cognome` (`surname`),
  ADD KEY `active` (`active`),
  ADD KEY `user` (`username`),
  ADD KEY `is_root` (`is_root`),
  ADD KEY `hash` (`hash`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
