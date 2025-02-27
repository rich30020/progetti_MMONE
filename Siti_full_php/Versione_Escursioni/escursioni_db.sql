-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Feb 27, 2025 alle 10:19
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
-- Database: `escursioni_db`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `commenti`
--

CREATE TABLE `commenti` (
  `id` int(11) NOT NULL,
  `escursione_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `commento` text NOT NULL,
  `data` datetime DEFAULT current_timestamp(),
  `mi_piace` int(11) DEFAULT 0,
  `non_mi_piace` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `commenti`
--

INSERT INTO `commenti` (`id`, `escursione_id`, `user_id`, `commento`, `data`, `mi_piace`, `non_mi_piace`) VALUES
(15, 2, 3, 'Buonasera Riccardo bel articolo grazie ci andro sicuramente. Ma perchè solo 3 stelle?', '2025-02-21 10:38:26', 5, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `escursioni`
--

CREATE TABLE `escursioni` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `sentiero` varchar(100) NOT NULL,
  `durata` time NOT NULL,
  `difficolta` enum('Facile','Medio','Difficile') NOT NULL,
  `punti` int(11) DEFAULT 0,
  `foto` varchar(255) DEFAULT NULL,
  `bellezza` int(2) NOT NULL DEFAULT 5
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `escursioni`
--

INSERT INTO `escursioni` (`id`, `user_id`, `sentiero`, `durata`, `difficolta`, `punti`, `foto`, `bellezza`) VALUES
(2, NULL, 'Fossalta - San Donà di Piave', '00:00:01', 'Facile', 20, 'fossalta.jpg', 3),
(4, NULL, 'Meolo - Losson', '00:00:01', 'Facile', 10, 'meolo.jpg', 5),
(9, NULL, 'Fossalta di Piave - Croce ', '00:00:03', 'Medio', 30, 'croce.jpg', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `eta` int(11) DEFAULT NULL,
  `livello_esperienza` enum('Principiante','Intermedio','Esperto') NOT NULL,
  `punti_escursionistici` int(11) DEFAULT 0,
  `data_registrazione` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id`, `nome`, `email`, `password`, `eta`, `livello_esperienza`, `punti_escursionistici`, `data_registrazione`) VALUES
(3, 'Riccardo', 'riccardomestre17@gmail.com', '$2y$10$upXGU036GZJQLMYUznjWQOKR/xYQVfdZeqZKT1CgH3EHhSMWSFe.m', 18, '', 0, '2025-02-14 08:26:20'),
(5, 'Admin', 'r.mestre@donboscosandona.it', '$2y$10$MJXVvAWZmBtbDn73yJ363e6w6mai3JQM410vgKwoIJ68NcCgr/8WK', 19, '', 0, '2025-02-21 15:28:53');

-- --------------------------------------------------------

--
-- Struttura della tabella `voti`
--

CREATE TABLE `voti` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `escursione_id` int(11) DEFAULT NULL,
  `voto` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `commenti`
--
ALTER TABLE `commenti`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `escursioni`
--
ALTER TABLE `escursioni`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indici per le tabelle `voti`
--
ALTER TABLE `voti`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`escursione_id`),
  ADD KEY `escursione_id` (`escursione_id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `commenti`
--
ALTER TABLE `commenti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT per la tabella `escursioni`
--
ALTER TABLE `escursioni`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `voti`
--
ALTER TABLE `voti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `escursioni`
--
ALTER TABLE `escursioni`
  ADD CONSTRAINT `escursioni_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `utenti` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `voti`
--
ALTER TABLE `voti`
  ADD CONSTRAINT `voti_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `utenti` (`id`),
  ADD CONSTRAINT `voti_ibfk_2` FOREIGN KEY (`escursione_id`) REFERENCES `escursioni` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
