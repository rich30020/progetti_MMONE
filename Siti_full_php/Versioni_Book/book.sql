-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Feb 27, 2025 alle 10:20
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
-- Database: `book`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `genre` varchar(50) NOT NULL,
  `NAME` varchar(255) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `prezzo` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `books`
--

INSERT INTO `books` (`id`, `genre`, `NAME`, `image_url`, `prezzo`) VALUES
(1, 'historical_novel', 'La Caduta di Troia', 'https://edicola.shop/media/catalog/product/cache/1/thumbnail/400x/17f82f742ffe127f42dca9de82fb58b1/d/c/dc57953757.jpg', 10.00),
(2, 'historical_novel', 'L\'Impero Romano', 'https://www.laterza.it/immagini/copertine-big/9788858155301.jpg', 10.00),
(3, 'historical_novel', 'Guerra e Pace', 'https://www.ibs.it/images/9788811686200_0_536_0_75.jpg', 10.00),
(4, 'historical_novel', 'I Miserabili', 'https://www.ibs.it/images/9788817129107_0_536_0_75.jpg', 10.00),
(5, 'historical_novel', 'Promessi Sposi', 'https://m.media-amazon.com/images/I/91npmGWLGeL._AC_UF1000,1000_QL80_.jpg', 10.00),
(6, 'historical_novel', 'Il Nome della Rosa', 'https://m.media-amazon.com/images/I/61Aa9Yic8AL._AC_UF1000,1000_QL80_.jpg', 10.00),
(7, 'historical_novel', 'I Pilastri della Terra', 'https://www.ibs.it/images/9788804666929_0_536_0_75.jpg', 10.00),
(8, 'historical_novel', 'Memorie di Adriano', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRRsJwCreCdCVgjgWmABGTN-CnkTK_4JeUjlw&s', 10.00),
(9, 'adventure_action', 'Il Tesoro dell\'Isola', 'https://www.orecchioacerbo.it/wp-content/uploads/2021/08/isola-del-tesoro.jpg', 10.00),
(10, 'adventure_action', 'Indiana Jones', 'https://m.media-amazon.com/images/I/91s3m--k3BL._AC_UF1000,1000_QL80_.jpg', 10.00),
(11, 'adventure_action', 'Lara Croft', 'https://www.ibs.it/images/9788863554618_0_536_0_75.jpg', 10.00),
(12, 'adventure_action', 'Il Gladiatore', 'https://www.ibs.it/images/9788809885394_0_536_0_75.jpg', 10.00),
(13, 'adventure_action', 'Il Nome della Rosa', 'https://m.media-amazon.com/images/I/61Aa9Yic8AL._AC_UF1000,1000_QL80_.jpg', 10.00),
(14, 'adventure_action', 'Il Codice da Vinci', 'https://www.ibs.it/images/9788804667223_0_536_0_75.jpg', 10.00),
(15, 'adventure_action', 'Il Trono di Spade', 'https://www.ibs.it/images/9788804711957_0_536_0_75.jpg', 10.00),
(16, 'adventure_action', 'La Ragazza con il Tatuaggio del Drago', 'https://m.media-amazon.com/images/I/61DR4grQBRL._AC_UF1000,1000_QL80_.jpg', 10.00),
(17, 'fantasy', 'Il Signore degli Anelli', 'https://m.media-amazon.com/images/I/81HXjSgmsvL._AC_UF1000,1000_QL80_.jpg', 10.00),
(18, 'fantasy', 'Harry Potter', 'https://www.ibs.it/images/9788867155958_0_424_0_75.jpg', 10.00),
(19, 'fantasy', 'Le Cronache di Narnia', 'https://m.media-amazon.com/images/I/A1yPOdvMgJL._AC_UF1000,1000_QL80_.jpg', 10.00),
(20, 'fantasy', 'Il Trono di Spade', 'https://www.ibs.it/images/9788804711957_0_536_0_75.jpg', 10.00),
(21, 'fantasy', 'La Ruota del Tempo', 'https://www.lafeltrinelli.it/images/9788834739525_0_536_0_75.jpg', 10.00),
(22, 'fantasy', 'Il Nome del Vento', 'https://m.media-amazon.com/images/I/91SKJUew85L._UF1000,1000_QL80_.jpg', 10.00),
(23, 'fantasy', 'La Bussola d\'Oro', 'https://m.media-amazon.com/images/I/71FRPjGCKzL._AC_UF1000,1000_QL80_.jpg', 10.00),
(24, 'science_fiction', 'Dune', 'https://www.lafeltrinelli.it/images/9788834739679_0_536_0_75.jpg', 10.00),
(25, 'science_fiction', 'Blade Runner', 'https://m.media-amazon.com/images/I/81YMbFJ6wpL.jpg', 10.00),
(26, 'science_fiction', '1984', 'https://www.ibs.it/images/9788804668237_0_536_0_75.jpg', 10.00),
(27, 'science_fiction', 'Il Mondo Nuovo', 'https://www.ibs.it/images/9788804735823_0_536_0_75.jpg', 10.00),
(28, 'science_fiction', 'I Figli di Andromeda', 'https://www.amantideilibri.it/wp-content/uploads/2022/10/il-gioco-di-andromeda.jpg', 10.00),
(29, 'science_fiction', 'La Guerra dei Mondi', 'https://m.media-amazon.com/images/I/81hNSE-OwLL._AC_UF1000,1000_QL80_.jpg', 10.00),
(30, 'science_fiction', 'Neuromante', 'https://m.media-amazon.com/images/I/71jvNwlSt0L._AC_UF1000,1000_QL80_.jpg', 10.00),
(31, 'science_fiction', 'Il Marziano', 'https://m.media-amazon.com/images/I/81S34bHUE-L._AC_UF1000,1000_QL80_.jpg', 10.00),
(32, 'horror', 'Dracula', 'https://m.media-amazon.com/images/I/61uwJXxPwuL._AC_UF1000,1000_QL80_.jpg', 10.00),
(33, 'horror', 'Frankenstein', 'https://m.media-amazon.com/images/I/61oPJXXqXGL._AC_UF1000,1000_QL80_.jpg', 10.00),
(34, 'horror', 'Il Ritratto di Dorian Gray', 'https://m.media-amazon.com/images/I/71c5s0ykyIL._AC_UF1000,1000_QL80_.jpg', 10.00),
(35, 'horror', 'L\'Incubo di Hill House', 'https://www.adelphi.it/spool/i__id6551_mw600__1x.jpg', 10.00),
(36, 'horror', 'Il Castello di Otranto', 'https://www.ibs.it/images/9788807902161_0_536_0_75.jpg', 10.00),
(37, 'horror', 'Jane Eyre', 'https://www.ibs.it/images/9788807900778_0_536_0_75.jpg', 10.00),
(38, 'horror', 'Il Fantasma di Canterville', 'https://www.ibs.it/images/9788807900570_0_536_0_75.jpg', 10.00),
(39, 'horror', 'Carmilla', 'https://m.media-amazon.com/images/I/711Wx9cjUrL._AC_UF1000,1000_QL80_.jpg', 10.00),
(41, 'fantasy', 'Anathem', 'https://cdn11.bigcommerce.com/s-65f8qukrjx/products/6831/images/17237/Stepehnson_Anathem_covrr__93013.1687451124.386.513.jpg?c=1', 10.00);

-- --------------------------------------------------------

--
-- Struttura della tabella `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `utenti_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `prezzo` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `cart`
--

INSERT INTO `cart` (`id`, `utenti_id`, `book_id`, `prezzo`) VALUES
(12, 6, 4, NULL),
(13, 6, 23, NULL),
(17, 1, 4, 10.00),
(18, 1, 3, 10.00),
(19, 1, 25, 10.00);

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id`, `email`, `username`, `password`) VALUES
(1, 'gianni@libero.it', 'GIANNI', '$2y$10$EhJyIy/36GhQOljsJGqObuLdzHfsI74GT8ei70CEt7x7uKRqVbUkK'),
(6, 'riccardomestre@gmail.com', 'riccardo', '$2y$10$Pu1CxMT8bMI1BMrSRkw4deUqWQ2cBHJHOjAgXfg3j/pbp8hQDYEcu');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utenti_id` (`utenti_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT per la tabella `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`utenti_id`) REFERENCES `utenti` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
