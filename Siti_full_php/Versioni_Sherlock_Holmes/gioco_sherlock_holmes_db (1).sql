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
-- Database: `gioco_sherlock_holmes_db`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `casi`
--

CREATE TABLE `casi` (
  `caso_id` int(11) NOT NULL,
  `titolo` varchar(255) NOT NULL,
  `dettagli` text NOT NULL,
  `sospetti` text NOT NULL,
  `risposta_corretta` varchar(255) NOT NULL,
  `descrizione` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `casi`
--

INSERT INTO `casi` (`caso_id`, `titolo`, `dettagli`, `sospetti`, `risposta_corretta`, `descrizione`) VALUES
(1, 'Il mistero della stanza chiusa', 'Una stanza è stata trovata chiusa dall\'interno. Chi può essere il colpevole?', 'La Governante|Il Maggiordomo', 'Il Maggiordomo', NULL),
(2, 'Il Mistero della Biblioteca', 'Un antico manoscritto, noto per il suo valore inestimabile, è scomparso dalla biblioteca di una prestigiosa università. La porta era chiusa a chiave, ma una finestra nel retro era aperta, e alcuni libri erano stati spostati dalla loro posizione. Nessuno ha visto entrare o uscire persone, eppure il libro è sparito.', 'Il bibliotecario|Un ricercatore|Un visitatore misterioso', 'Un Ricercatore', 'Un libro scomparso, ma chi ha avuto il coraggio di rubarlo in pieno giorno?'),
(3, 'Il Delitto in Strada', 'Un uomo è stato trovato morto lungo una strada trafficata. La causa della morte era un colpo alla testa, ma non ci sono segni di lotta. Vicino al corpo è stata trovata una bottiglia di champagne rotta, ma nessun testimone ha visto nulla. Un passante ha però notato un\'auto sospetta che si fermava poco prima che il crimine avvenisse.', 'Il vicino di casa|Un amico della vittima|Un automobilista misterioso', 'Un amico della vittima', 'Un delitto che si nasconde tra le ombre di un incontro sospetto.'),
(4, 'Il Mago Scomparso', 'Un famoso illusionista è scomparso durante un suo spettacolo in un teatro affollato. Si trovava in una grande gabbia chiusa, ma quando le luci sono state riaccese, la gabbia era vuota e lui non si trovava da nessuna parte. Nessuno è riuscito a capire come sia riuscito a scappare, visto che il teatro era sotto stretto controllo.', 'Il collega illusionista|Il direttore del teatro|La moglie del mago', 'Il collega illusionista', 'Un\'illusione così perfetta che nessuno è riuscito a scoprire la verità dietro la sparizione.'),
(5, 'Il Furto alla Galleria d\'Arte', 'Un famoso dipinto, considerato unico, è stato rubato da una galleria d\'arte di alto livello. Le telecamere di sicurezza non hanno registrato nulla di strano, ma un\'impronta di mano è stata trovata sulla cornice del dipinto. Il museo è stato evacuato durante la notte per lavori di manutenzione, ma chi ha avuto accesso a quel dipinto?', 'Il guardiano della galleria| Un curatore del museo|Un visitatore regolare della galleria', 'Un curatore del museo', 'Il furto di un\'opera d\'arte, ma quale segreto nasconde davvero quella mano sulla cornice?'),
(6, 'Il Caso della Lettera Anonima', 'Una lettera minatoria è arrivata alla casa di una famosa scrittrice. La lettera, scritta a mano, minacciava la sua vita se non avesse smesso di scrivere romanzi. La scrittrice è convinta che qualcuno a lei vicino sia responsabile. Ma chi potrebbe essere? La lettera è stata inviata senza un mittente.', 'Il marito|Un ex-amante|Un collega scrittore', 'Un collega scrittore', 'Una lettera minacciosa che porta a scoprire un cuore geloso nascosto nel mondo letterario.');

-- --------------------------------------------------------

--
-- Struttura della tabella `giocatori`
--

CREATE TABLE `giocatori` (
  `giocatore_id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `punti` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `giocatori`
--

INSERT INTO `giocatori` (`giocatore_id`, `nome`, `punti`) VALUES
(1, 'Giocatore1', 20);

-- --------------------------------------------------------

--
-- Struttura della tabella `risposte_giocatori`
--

CREATE TABLE `risposte_giocatori` (
  `risposta_id` int(11) NOT NULL,
  `caso_id` int(11) NOT NULL,
  `teoria_giocatore` text NOT NULL,
  `corretto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `risposte_giocatori`
--

INSERT INTO `risposte_giocatori` (`risposta_id`, `caso_id`, `teoria_giocatore`, `corretto`) VALUES
(8, 1, 'Secondo me il colpevole è il maggiordomo, magari in un momento di confusione si è dimenticato di chiudere una stanza', 1);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `casi`
--
ALTER TABLE `casi`
  ADD PRIMARY KEY (`caso_id`);

--
-- Indici per le tabelle `giocatori`
--
ALTER TABLE `giocatori`
  ADD PRIMARY KEY (`giocatore_id`);

--
-- Indici per le tabelle `risposte_giocatori`
--
ALTER TABLE `risposte_giocatori`
  ADD PRIMARY KEY (`risposta_id`),
  ADD KEY `caso_id` (`caso_id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `casi`
--
ALTER TABLE `casi`
  MODIFY `caso_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `giocatori`
--
ALTER TABLE `giocatori`
  MODIFY `giocatore_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `risposte_giocatori`
--
ALTER TABLE `risposte_giocatori`
  MODIFY `risposta_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `risposte_giocatori`
--
ALTER TABLE `risposte_giocatori`
  ADD CONSTRAINT `risposte_giocatori_ibfk_1` FOREIGN KEY (`caso_id`) REFERENCES `casi` (`caso_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
