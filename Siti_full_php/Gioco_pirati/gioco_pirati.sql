-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Feb 27, 2025 alle 10:17
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gioco_pirati`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `giocatori`
--

CREATE TABLE `giocatori` (
  `id` int(11) NOT NULL,
  `salute` int(11) NOT NULL,
  `saldo` int(11) NOT NULL,
  `palle` int(11) NOT NULL,
  `livello_nave` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `giocatori`
--

INSERT INTO `giocatori` (`id`, `salute`, `saldo`, `palle`, `livello_nave`) VALUES
(1, 300, 0, 10, 1),
(2, 300, 0, 10, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `navi_nemiche`
--

CREATE TABLE `navi_nemiche` (
  `id` int(11) NOT NULL,
  `livello` int(11) NOT NULL,
  `vita` int(11) NOT NULL,
  `danno` int(11) NOT NULL,
  `vincita` int(11) NOT NULL,
  `vive` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `navi_nemiche`
--

INSERT INTO `navi_nemiche` (`id`, `livello`, `vita`, `danno`, `vincita`, `vive`) VALUES
(1, 1, 50, 10, 5, 1),
(2, 2, 100, 20, 15, 1),
(3, 3, 150, 30, 30, 1),
(4, 4, 200, 40, 50, 1),
(5, 5, 250, 50, 100, 1),
(6, 6, 300, 60, 150, 1),
(7, 7, 350, 70, 200, 1),
(8, 8, 400, 80, 300, 1),
(9, 9, 450, 90, 400, 1),
(10, 10, 500, 100, 500, 1);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `giocatori`
--
ALTER TABLE `giocatori`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `navi_nemiche`
--
ALTER TABLE `navi_nemiche`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `giocatori`
--
ALTER TABLE `giocatori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `navi_nemiche`
--
ALTER TABLE `navi_nemiche`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
