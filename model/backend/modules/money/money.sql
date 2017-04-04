-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Gegenereerd op: 24 nov 2016 om 12:25
-- Serverversie: 10.1.10-MariaDB
-- PHP-versie: 7.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `money`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `categorie`
--

CREATE TABLE `categorie` (
  `id` int(11) NOT NULL,
  `cat_naam` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `categorie`
--

INSERT INTO `categorie` (`id`, `cat_naam`) VALUES
(1, 'Woonlasten'),
(2, 'Telefoon'),
(3, 'Internet/tv'),
(4, 'Verzekeringen'),
(5, 'School'),
(6, 'Vervoer'),
(7, 'Abonnementen'),
(8, 'Boodschappen'),
(9, 'Kleding'),
(10, 'Uitgaan'),
(11, 'Vrije_tijd'),
(12, 'Overig'),
(13, 'Auto onderhoud'),
(14, 'Inkomsten');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `seq_question` varchar(255) NOT NULL,
  `seq_answer` varchar(255) NOT NULL,
  `Budget` decimal(9,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `login`
--

INSERT INTO `login` (`id`, `username`, `hash`, `salt`, `seq_question`, `seq_answer`, `Budget`) VALUES
(3, 'Elon', '1b84b05f67262a4cb659a5efbc562ccef8ddf69d06ea716eae156eaf92f0d0e2', '3b00a338dd63229aabc46885ea64b1d181dd61be4de99716394e2f3b56c6a1ac', '', '', '489.00'),
(4, 'test_user_2', '27cb4b677689bc1f597623e0b7577c9d778cce69c27d30928225fed36acf235d', '0f20d3a4ddc0f8010d1f6a75959bc3e310e854f8adfbe6eeaaf90e611aca0c1a', 'test', 'test', '500.00'),
(5, 'test_user_3', '3793fcd0702f844762745ef742e44e153d946792818cf0b135fd396ada7357e4', '64c320b05a3678ab9402b97a3def1d2a2ea6de160d5ba77acbd9e3e584ae2c20', 'test', '0cf275ac59da927a3b86e97f7f56df9b3d261bfab1f1be9cd887480779bd2842', '500.00');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `overzicht`
--

CREATE TABLE `overzicht` (
  `id` int(11) NOT NULL,
  `categorie_id` int(11) NOT NULL,
  `maand` date NOT NULL,
  `totaal_bedrag` decimal(6,2) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `overzicht`
--

INSERT INTO `overzicht` (`id`, `categorie_id`, `maand`, `totaal_bedrag`, `user_id`) VALUES
(1, 8, '2016-10-01', '25.50', 3),
(2, 7, '2016-10-01', '501.00', 3);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `uitgaven`
--

CREATE TABLE `uitgaven` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `bedrag` decimal(6,2) NOT NULL,
  `categorie` int(11) NOT NULL,
  `datum` date NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `uitgaven`
--

INSERT INTO `uitgaven` (`id`, `naam`, `bedrag`, `categorie`, `datum`, `user_id`, `type`) VALUES
(1, 'test', '10.00', 8, '2016-10-10', 3, 'Uitgave'),
(2, 'test_2', '5.50', 8, '2016-10-14', 3, 'Uitgave'),
(3, 'test', '500.00', 7, '2016-10-11', 3, 'Uitgave'),
(4, 'test', '1.00', 7, '2016-10-12', 3, 'Uitgave'),
(5, 'Appel', '10.00', 8, '2016-10-11', 3, 'Uitgave'),
(6, 'Loon', '500.00', 7, '2016-10-24', 3, 'Inkomst');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `overzicht`
--
ALTER TABLE `overzicht`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `uitgaven`
--
ALTER TABLE `uitgaven`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT voor een tabel `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT voor een tabel `overzicht`
--
ALTER TABLE `overzicht`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT voor een tabel `uitgaven`
--
ALTER TABLE `uitgaven`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
