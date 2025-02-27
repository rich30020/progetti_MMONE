-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Feb 27, 2025 alle 10:14
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
-- Database: `ristorante`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `prenotazioni`
--

CREATE TABLE `prenotazioni` (
  `id` int(11) NOT NULL,
  `utente_id` int(11) DEFAULT NULL,
  `tavolo_id` int(11) DEFAULT NULL,
  `data_ora` datetime DEFAULT NULL,
  `numero_persone` int(11) DEFAULT NULL,
  `status` enum('confermata','annullata','completata') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `prenotazioni`
--

INSERT INTO `prenotazioni` (`id`, `utente_id`, `tavolo_id`, `data_ora`, `numero_persone`, `status`) VALUES
(24, 3, 2, '2025-01-25 13:30:00', 3, 'confermata');

-- --------------------------------------------------------

--
-- Struttura della tabella `tavoli`
--

CREATE TABLE `tavoli` (
  `id` int(11) NOT NULL,
  `numero_tavolo` int(11) DEFAULT NULL,
  `posti` int(11) DEFAULT NULL,
  `stato` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `tavoli`
--

INSERT INTO `tavoli` (`id`, `numero_tavolo`, `posti`, `stato`) VALUES
(1, 1, 2, 'Disponibile'),
(2, 2, 3, 'Disponibile'),
(3, 3, 4, 'Disponibile'),
(4, 4, 5, 'Disponibile'),
(5, 5, 6, 'Disponibile'),
(6, 6, 7, 'Disponibile');

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id`, `nome`, `email`, `telefono`, `password`) VALUES
(3, 'GIANNI', 'riccardomestre17@gmail.com', '3339881482', '$2y$10$bstDbNyQNzEcv2ffMiSspeTahngPrd98kZccNAM423kz4dakrwamm'),
(4, 'riccardo', 'alessandromestre@libereo.it', '3395242844', '$2y$10$Qt42HjRrvARtrIpDsBKKgeM7G8aBEeO63hrOVs6PSN33WF5DiVkaG');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `prenotazioni`
--
ALTER TABLE `prenotazioni`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utente_id` (`utente_id`),
  ADD KEY `tavolo_id` (`tavolo_id`);

--
-- Indici per le tabelle `tavoli`
--
ALTER TABLE `tavoli`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `prenotazioni`
--
ALTER TABLE `prenotazioni`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT per la tabella `tavoli`
--
ALTER TABLE `tavoli`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `prenotazioni`
--
ALTER TABLE `prenotazioni`
  ADD CONSTRAINT `prenotazioni_ibfk_1` FOREIGN KEY (`utente_id`) REFERENCES `utenti` (`id`),
  ADD CONSTRAINT `prenotazioni_ibfk_2` FOREIGN KEY (`tavolo_id`) REFERENCES `tavoli` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
