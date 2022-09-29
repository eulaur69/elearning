-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 29, 2022 at 08:46 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `elearning`
--

DELIMITER $$
--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `calculMedie` (`idElev` INT, `idMaterie` INT) RETURNS FLOAT begin
	set @avg = (select avg(nota) from note where elev = idElev and materie = idMaterie and nota != 0);
    return @avg;
end$$

CREATE DEFINER=`root`@`localhost` FUNCTION `numarAbsente` (`idElev` INT, `idMaterie` INT) RETURNS INT(11) begin
	set @nrabs = (select count(elev) from absente where elev = idElev and materie = idMaterie and motivata = 0);
    return @nrabs;
end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `absente`
--

CREATE TABLE `absente` (
  `id` int(11) NOT NULL,
  `data` date NOT NULL,
  `elev` int(11) NOT NULL,
  `materie` int(11) NOT NULL,
  `motivata` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `absente`
--

INSERT INTO `absente` (`id`, `data`, `elev`, `materie`, `motivata`) VALUES
(1, '2022-03-01', 1, 1, 1),
(2, '2022-03-01', 2, 1, 0),
(3, '2022-03-09', 2, 1, 1),
(4, '2022-03-07', 2, 1, 0),
(5, '2022-03-09', 1, 2, 0),
(6, '2022-03-02', 1, 1, 1),
(7, '2022-03-04', 2, 2, 0),
(8, '2022-03-02', 1, 2, 1),
(9, '2022-03-17', 1, 1, 1),
(11, '2022-03-23', 1, 3, 1),
(13, '2022-04-06', 1, 4, 0),
(14, '2022-04-06', 4, 4, 0),
(15, '2022-04-06', 5, 4, 0),
(16, '2022-04-06', 4, 4, 0),
(17, '2022-04-06', 4, 2, 0),
(18, '2022-04-06', 5, 2, 0),
(19, '2022-04-06', 4, 1, 0),
(20, '2022-04-06', 5, 1, 0),
(23, '2022-04-26', 4, 4, 0),
(26, '2022-04-26', 1, 2, 0),
(27, '2022-04-26', 2, 2, 0),
(28, '2022-04-26', 10, 2, 0),
(29, '2022-04-26', 15, 2, 0),
(31, '2022-04-27', 15, 2, 0),
(32, '2022-04-28', 1, 3, 0),
(33, '2022-05-29', 13, 2, 0),
(34, '2022-05-29', 11, 2, 0),
(35, '2022-06-15', 25, 1, 0),
(36, '2022-06-13', 21, 2, 1),
(37, '2022-07-10', 11, 2, 0),
(38, '2022-07-10', 1, 1, 0),
(39, '2022-07-10', 10, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `administratori`
--

CREATE TABLE `administratori` (
  `id` int(11) NOT NULL,
  `nume` varchar(50) NOT NULL,
  `prenume` varchar(50) NOT NULL,
  `prenume2` varchar(50) NOT NULL,
  `cnp` varchar(13) NOT NULL,
  `judet` varchar(50) NOT NULL,
  `localitate` varchar(50) NOT NULL,
  `strada` varchar(50) NOT NULL,
  `numar` int(4) UNSIGNED NOT NULL,
  `bloc` varchar(5) DEFAULT NULL,
  `scara` varchar(5) DEFAULT NULL,
  `etaj` int(2) UNSIGNED DEFAULT NULL,
  `apartament` int(4) UNSIGNED DEFAULT NULL,
  `cod_postal` int(6) UNSIGNED ZEROFILL NOT NULL,
  `cetatenie` varchar(50) NOT NULL,
  `nationalitate` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `nrtelefon` int(10) UNSIGNED ZEROFILL NOT NULL,
  `parola` varchar(255) NOT NULL,
  `tip_cont` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `administratori`
--

INSERT INTO `administratori` (`id`, `nume`, `prenume`, `prenume2`, `cnp`, `judet`, `localitate`, `strada`, `numar`, `bloc`, `scara`, `etaj`, `apartament`, `cod_postal`, `cetatenie`, `nationalitate`, `email`, `nrtelefon`, `parola`, `tip_cont`) VALUES
(1, 'admin', 'admin', 'admin', '1234567890123', 'a', 'a', 'a', 1, NULL, NULL, NULL, NULL, 123456, 'a', 'a', 'admin@gmail.com', 1234567890, '$2y$10$WX4LZ9bATUTUhKj7csWl0uv3wB9ZVw906JmIY6TgMEdC3oMhVtsPS', 3);

-- --------------------------------------------------------

--
-- Table structure for table `clase`
--

CREATE TABLE `clase` (
  `id` int(11) NOT NULL,
  `clasa` int(2) NOT NULL,
  `litera` varchar(1) NOT NULL,
  `diriginte` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `clase`
--

INSERT INTO `clase` (`id`, `clasa`, `litera`, `diriginte`) VALUES
(1, 9, 'A', 3),
(2, 9, 'B', 9),
(3, 10, 'A', 5),
(4, 10, 'B', 6);

-- --------------------------------------------------------

--
-- Table structure for table `elevi`
--

CREATE TABLE `elevi` (
  `id` int(11) NOT NULL,
  `nume` varchar(30) NOT NULL,
  `prenume` varchar(30) NOT NULL,
  `prenume2` varchar(30) NOT NULL,
  `cnp` varchar(13) NOT NULL,
  `judet` varchar(50) NOT NULL,
  `localitate` varchar(50) NOT NULL,
  `strada` varchar(50) NOT NULL,
  `numar` int(4) UNSIGNED NOT NULL,
  `bloc` varchar(5) DEFAULT NULL,
  `scara` varchar(5) DEFAULT NULL,
  `etaj` int(2) UNSIGNED DEFAULT NULL,
  `apartament` int(4) UNSIGNED DEFAULT NULL,
  `cod_postal` int(6) UNSIGNED ZEROFILL NOT NULL,
  `cetatenie` varchar(50) NOT NULL,
  `nationalitate` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `nrtelefon` int(10) UNSIGNED ZEROFILL NOT NULL,
  `parola` varchar(255) NOT NULL,
  `clasa` int(11) NOT NULL,
  `tip_cont` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `elevi`
--

INSERT INTO `elevi` (`id`, `nume`, `prenume`, `prenume2`, `cnp`, `judet`, `localitate`, `strada`, `numar`, `bloc`, `scara`, `etaj`, `apartament`, `cod_postal`, `cetatenie`, `nationalitate`, `email`, `nrtelefon`, `parola`, `clasa`, `tip_cont`) VALUES
(1, 'Popescu', 'Cosmin', '', '1234567890123', 'Bucuresti', 'Bucuresti', 'Militari', 69, NULL, NULL, NULL, NULL, 069699, 'Romana', 'Romana', 'elev1@gmail.com', 1234567890, '$2y$10$6eRMi95keT8V79kQrDGmN.pkZZvkUkCA9uEZ7YVR.g9b20TnEWqJC', 1, 4),
(2, 'Popa', 'Ovidiu', '', '1234567890123', 'Brasov', 'Brasov', 'Strada Iuliu Maniu', 49, NULL, NULL, NULL, NULL, 500091, 'Romana', 'Romana', 'popicachiarel69@gmail.com', 1234567890, '$2y$10$fsgW/FH/KT1esLZ9ddIwYe57ayzL7M6VQgtPBG6ifU7iMpLc.iOdC', 1, 4),
(4, 'Slăboiu', 'Petra', '', '1234567890123', 'Vrancea', 'Focsani', 'Piata Victoriei', 2, NULL, NULL, NULL, NULL, 620115, 'Romana', 'Romana', 'patrasche78@gmail.com', 0253656003, '', 3, 4),
(5, 'Tomulescu', 'Oana', '', '1234567890123', 'Iasi', 'Iasi', 'Calea Chisinaului', 56, NULL, NULL, NULL, NULL, 700173, 'Romana', 'Romana', 'oanatomu@gmail.com', 0725680334, '', 3, 4),
(10, 'Gherban', 'Olivia', '', '1234567890123', 'Bacau', 'Bacau', 'Strada Erou Ciprian Pintea', 9, NULL, NULL, NULL, NULL, 600122, 'Romana', 'Romana', 'gheoli54@gmail.com', 0722270203, '', 1, 4),
(11, 'Bratosin', 'Angelica', '', '1234567890123', 'Mureș', 'Tarnaveni', 'Strada Armatei', 121, '\'A2\'', '\'1\'', 0, 0, 545600, 'Romana', 'Romana', 'liana55@yahoo.com', 0216039372, '', 1, 4),
(13, 'Stoica', 'Alexandru', '', '1234567890123', 'Dolj', 'Craiova', 'Bulevadrul Decebal', 111, NULL, NULL, NULL, NULL, 200696, 'Romana', 'Romana', 'alexuzu69@gmail.com', 1234567890, '', 1, 4),
(15, 'Constantinescu', 'Paul', '', '1234567890123', 'Mureș', 'Targu Mures', 'Strada Geo', 24, NULL, NULL, NULL, NULL, 540228, 'Romana', 'Romana', 'cpavelandru@gmail.com', 0265588200, '', 1, 4),
(20, 'Tomescu', 'Iulian', ' ', '1234567890123', 'Bucuresti', 'Bucuresti', 'Strada Micrea Vulcanescu', 88, NULL, NULL, NULL, NULL, 010526, 'Romana', 'Romana', 'tomatomitza56@gmail.com', 1234567890, '$2y$10$ZeHnQ1isoy0/CTmOeNUjMudPrMg8gkIuJoS9vywmC.Px9T.NlnUrC', 1, 4),
(21, 'Găbureanu', 'Lidia', '', '1234567890123', 'Mures', 'Sangeorgiu De Mures', 'Strada Bailor', 1122, NULL, NULL, NULL, NULL, 547820, 'Romana', 'Romana', 'lidiutza69420@gmail.com', 0265319159, '', 1, 4),
(25, 'Ghiță', 'Anton', '', '1234567890123', 'Iasi', 'Iasi', 'Strada Nicorita', 26, NULL, NULL, NULL, NULL, 700899, 'Romana', 'Romana', 'antonantonache78@yahoo.com', 0265779868, '$2y$10$5gWQnlpUHPOGFfEuAO.IQuKWM0Dlqnntd0CpEmDTCr1lkwL1Atzm6', 1, 4),
(26, 'Popescu', 'Pavel', ' ', '1234567890123', 'Maramureș', 'Sighetu Marmatiei', 'Strada Iuliu Maniu', 69, NULL, NULL, NULL, NULL, 435500, 'Romana', 'Romana', 'pavelescup70@gmail.com', 1234567890, '$2y$10$0em6gbJvKQlTMcIZoO2Qv.4jC0tYwTcdTZ2jPmR9.tr.ZxNUfeUlu', 1, 4),
(27, 'Diaconu', 'Cristi', '', '1234567890123', 'Bucuresti', 'Bucuresti', 'Aleea Botorani', 69, NULL, NULL, NULL, NULL, 050811, 'Romana', 'Romana', 'ciocan.mirabela@hotmail.com', 0721791186, '$2y$10$/18LejtF4dpiY/EETHVQpuIRC5V8hYbLXlmOtHvuJsJ7e0mYV21Qy', 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `materii`
--

CREATE TABLE `materii` (
  `id` int(11) NOT NULL,
  `materie` varchar(30) NOT NULL,
  `cod` varchar(10) NOT NULL,
  `descriere` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `materii`
--

INSERT INTO `materii` (`id`, `materie`, `cod`, `descriere`) VALUES
(1, 'Informatica', 'INFO1', 'Informatica'),
(2, 'Matematica', 'MAT1', 'Matematica'),
(3, 'Fizica', 'FIZ1', 'Fizica1'),
(4, 'Istorie', 'IST1', 'Istorie'),
(5, 'Lb. Romana', 'LBRO1', 'Lb. Romana'),
(7, 'Informatica', 'INFO2', 'Informatica Intensiv1');

-- --------------------------------------------------------

--
-- Table structure for table `note`
--

CREATE TABLE `note` (
  `id` int(11) NOT NULL,
  `nota` int(2) NOT NULL,
  `data` date NOT NULL,
  `elev` int(11) NOT NULL,
  `materie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `note`
--

INSERT INTO `note` (`id`, `nota`, `data`, `elev`, `materie`) VALUES
(1, 5, '2022-02-09', 1, 1),
(2, 7, '2022-02-28', 1, 1),
(3, 10, '2022-02-28', 2, 1),
(5, 10, '2022-02-28', 1, 2),
(6, 10, '2022-03-02', 2, 1),
(7, 5, '2022-03-02', 2, 1),
(8, 2, '2022-03-02', 1, 1),
(9, 10, '2022-03-17', 1, 1),
(11, 10, '2022-03-23', 1, 3),
(12, 9, '2022-03-24', 1, 2),
(13, 9, '2022-03-24', 1, 3),
(14, 10, '2022-04-04', 4, 1),
(15, 10, '2022-04-04', 5, 1),
(16, 10, '2022-04-04', 5, 1),
(17, 9, '2022-04-04', 4, 1),
(18, 9, '2022-04-04', 2, 2),
(19, 10, '2022-04-04', 2, 2),
(20, 10, '2022-04-04', 2, 2),
(21, 10, '2022-04-04', 2, 1),
(22, 10, '2022-04-06', 1, 4),
(23, 10, '2022-04-26', 1, 2),
(24, 10, '2022-04-26', 1, 2),
(26, 10, '2022-04-28', 4, 2),
(27, 10, '2022-05-29', 26, 2),
(28, 10, '2022-06-12', 25, 2),
(30, 9, '2022-06-19', 21, 1),
(31, 9, '2022-06-15', 10, 2),
(32, 10, '2022-07-10', 10, 1),
(35, 10, '2022-07-10', 25, 1),
(36, 9, '2022-07-13', 27, 1);

-- --------------------------------------------------------

--
-- Table structure for table `profesori`
--

CREATE TABLE `profesori` (
  `id` int(11) NOT NULL,
  `nume` varchar(50) NOT NULL,
  `prenume` varchar(50) NOT NULL,
  `prenume2` varchar(50) NOT NULL,
  `cnp` varchar(13) NOT NULL,
  `judet` varchar(50) NOT NULL,
  `localitate` varchar(50) NOT NULL,
  `strada` varchar(50) NOT NULL,
  `numar` int(4) UNSIGNED NOT NULL,
  `bloc` varchar(5) DEFAULT NULL,
  `scara` varchar(5) DEFAULT NULL,
  `etaj` int(2) UNSIGNED DEFAULT NULL,
  `apartament` int(4) UNSIGNED DEFAULT NULL,
  `cod_postal` int(6) UNSIGNED ZEROFILL NOT NULL,
  `cetatenie` varchar(50) NOT NULL,
  `nationalitate` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `nrtelefon` int(10) UNSIGNED ZEROFILL NOT NULL,
  `parola` varchar(255) NOT NULL,
  `tip_cont` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `profesori`
--

INSERT INTO `profesori` (`id`, `nume`, `prenume`, `prenume2`, `cnp`, `judet`, `localitate`, `strada`, `numar`, `bloc`, `scara`, `etaj`, `apartament`, `cod_postal`, `cetatenie`, `nationalitate`, `email`, `nrtelefon`, `parola`, `tip_cont`) VALUES
(3, 'profesor0', 'profesor0', 'profesor0', '1234567890123', 'asd', 'a', 'a', 1, NULL, NULL, NULL, NULL, 123456, 'a', 'a', 'profesor@gmail.com', 1234567890, '$2y$10$p.EVhvxjLwGhxFon7RYnKumc1NMmwuIUf/jvfoy4Sj4EQ7CtKDQWC', 3),
(4, 'profesor1', 'profesor1', '', '', '', '', '', 0, NULL, NULL, NULL, NULL, 000000, '', '', '', 0000000000, '$2y$10$QNlBKAFs3jwndTssu25M.OYD1CslwyMkWwiYHD/ym1ojHIE/m0MNa', 3),
(5, 'profesor2', 'profesor2', '', '', '', '', '', 0, NULL, NULL, NULL, NULL, 000000, '', '', '', 0000000000, '$2y$10$2HRVvtcNotFbwdkuBx/cP.GqDAF4lJD6fhKyNJnWYM4Aul8jATmX6', 3),
(6, 'profesor3', 'profesor3', '', '', '', '', '', 0, NULL, NULL, NULL, NULL, 000000, '', '', '', 0000000000, '$2y$10$PHtDouSj83fG5nXVLxV2Ou5cS2flTMss5iBB7h/RT3NdLtJs8bonW', 3),
(7, 'profesor4', 'profesor4', '', '1234567890123', 'a', 'a', 'a', 1, NULL, NULL, NULL, NULL, 123456, 'a', 'a', 'profesor4@gmail.com', 1234567890, '$2y$10$ZMGu9iZcsQ2raoMu0Q0GOevqGk4UzCEjmIsOAhNKrrcXBnmH9V3G2', 3),
(8, 'profesor5', 'profesor5', '', '', '', '', '', 0, NULL, NULL, NULL, NULL, 000000, '', '', '', 0000000000, '$2y$10$qeF2/6oxI8zzwO5M4WKv9eu8DEjDcubDFfWNuAi5JBBY0fLJlph3q', 3),
(9, 'profesor6', 'profesor6', '', '1234567890123', 'sdfsf', 'sdfsdf', 'dfhdfh', 1, NULL, NULL, NULL, NULL, 123456, 'sfgagag', 'sdgdfgdgs', '', 0000000000, '$2y$10$8dWoU1piClIE9c.wnsQ9qO6FxBsA9UOGgWykGTo3t6pwToav8E0P6', 3),
(10, 'profesor7', 'profesor7', 'ggggggggg', '1234567890123', 'sdfsf', 'sdfsdf', '', 2, NULL, NULL, NULL, NULL, 123456, 'sfgagag', 'sdgdfgdgs', '', 0000000000, '$2y$10$HrIb/beBieKCVIHVw4HMxuLfMpwrW0szxOY4jRjDzQXMzDddsL5zO', 3);

-- --------------------------------------------------------

--
-- Table structure for table `profesori_clase`
--

CREATE TABLE `profesori_clase` (
  `id` int(11) NOT NULL,
  `profesor` int(11) NOT NULL,
  `clasa` int(11) NOT NULL,
  `materie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `profesori_clase`
--

INSERT INTO `profesori_clase` (`id`, `profesor`, `clasa`, `materie`) VALUES
(168, 3, 3, 1),
(169, 3, 3, 2),
(170, 6, 3, 4),
(171, 5, 3, 3),
(176, 4, 1, 1),
(177, 3, 1, 2),
(179, 5, 1, 3),
(188, 6, 1, 4),
(196, 9, 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `profesori_materii`
--

CREATE TABLE `profesori_materii` (
  `id` int(11) NOT NULL,
  `profesor` int(11) NOT NULL,
  `materie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `profesori_materii`
--

INSERT INTO `profesori_materii` (`id`, `profesor`, `materie`) VALUES
(3, 4, 1),
(4, 5, 3),
(20, 6, 4),
(22, 8, 1),
(24, 9, 5),
(25, 10, 1),
(50, 7, 1),
(54, 3, 2),
(55, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `secretari`
--

CREATE TABLE `secretari` (
  `id` int(11) NOT NULL,
  `nume` varchar(50) NOT NULL,
  `prenume` varchar(50) NOT NULL,
  `prenume2` varchar(50) NOT NULL,
  `cnp` varchar(13) NOT NULL,
  `judet` varchar(50) NOT NULL,
  `localitate` varchar(50) NOT NULL,
  `strada` varchar(50) NOT NULL,
  `numar` int(4) UNSIGNED NOT NULL,
  `bloc` varchar(5) DEFAULT NULL,
  `scara` varchar(5) DEFAULT NULL,
  `etaj` int(2) UNSIGNED DEFAULT NULL,
  `apartament` int(4) UNSIGNED DEFAULT NULL,
  `cod_postal` int(6) UNSIGNED ZEROFILL NOT NULL,
  `cetatenie` varchar(50) NOT NULL,
  `nationalitate` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `nrtelefon` int(10) UNSIGNED ZEROFILL NOT NULL,
  `parola` varchar(255) NOT NULL,
  `tip_cont` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `secretari`
--

INSERT INTO `secretari` (`id`, `nume`, `prenume`, `prenume2`, `cnp`, `judet`, `localitate`, `strada`, `numar`, `bloc`, `scara`, `etaj`, `apartament`, `cod_postal`, `cetatenie`, `nationalitate`, `email`, `nrtelefon`, `parola`, `tip_cont`) VALUES
(1, 'secretar', 'secretar', 'secretar', '1234567890123', 'a', 'a', 'a', 1, NULL, NULL, NULL, NULL, 123456, 'a', 'a', 'secretar@gmail.com', 1234567890, '$2y$10$6wSxqgACxm7juib8nlnf0.IwTDEeTMxw8iZAkwDIlVjc9.bMrTW7K', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tipuri_utilizatori`
--

CREATE TABLE `tipuri_utilizatori` (
  `id` int(11) NOT NULL,
  `nume` varchar(20) NOT NULL,
  `descriere` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tipuri_utilizatori`
--

INSERT INTO `tipuri_utilizatori` (`id`, `nume`, `descriere`) VALUES
(1, 'administrator', ''),
(2, 'secretar', ''),
(3, 'profesor', ''),
(4, 'elev', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absente`
--
ALTER TABLE `absente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_elevi_absente` (`elev`);

--
-- Indexes for table `administratori`
--
ALTER TABLE `administratori`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tipuri_admini` (`tip_cont`);

--
-- Indexes for table `clase`
--
ALTER TABLE `clase`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_grupe_clase` (`litera`);

--
-- Indexes for table `elevi`
--
ALTER TABLE `elevi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_clase_elevi` (`clasa`),
  ADD KEY `fk_tipuri_elevi` (`tip_cont`);

--
-- Indexes for table `materii`
--
ALTER TABLE `materii`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `note`
--
ALTER TABLE `note`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_elevi_note` (`elev`);

--
-- Indexes for table `profesori`
--
ALTER TABLE `profesori`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tipuri_profesori` (`tip_cont`);

--
-- Indexes for table `profesori_clase`
--
ALTER TABLE `profesori_clase`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_profesori2` (`profesor`),
  ADD KEY `fk_clase` (`clasa`);

--
-- Indexes for table `profesori_materii`
--
ALTER TABLE `profesori_materii`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_profesori` (`profesor`),
  ADD KEY `fk_materii` (`materie`);

--
-- Indexes for table `secretari`
--
ALTER TABLE `secretari`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tipuri_secretari` (`tip_cont`);

--
-- Indexes for table `tipuri_utilizatori`
--
ALTER TABLE `tipuri_utilizatori`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absente`
--
ALTER TABLE `absente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `administratori`
--
ALTER TABLE `administratori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `clase`
--
ALTER TABLE `clase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `elevi`
--
ALTER TABLE `elevi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `materii`
--
ALTER TABLE `materii`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `note`
--
ALTER TABLE `note`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `profesori`
--
ALTER TABLE `profesori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `profesori_clase`
--
ALTER TABLE `profesori_clase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=197;

--
-- AUTO_INCREMENT for table `profesori_materii`
--
ALTER TABLE `profesori_materii`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `secretari`
--
ALTER TABLE `secretari`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tipuri_utilizatori`
--
ALTER TABLE `tipuri_utilizatori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absente`
--
ALTER TABLE `absente`
  ADD CONSTRAINT `fk_elevi_absente` FOREIGN KEY (`elev`) REFERENCES `elevi` (`id`);

--
-- Constraints for table `administratori`
--
ALTER TABLE `administratori`
  ADD CONSTRAINT `fk_tipuri_admini` FOREIGN KEY (`tip_cont`) REFERENCES `tipuri_utilizatori` (`id`);

--
-- Constraints for table `elevi`
--
ALTER TABLE `elevi`
  ADD CONSTRAINT `fk_tipuri_elevi` FOREIGN KEY (`tip_cont`) REFERENCES `tipuri_utilizatori` (`id`);

--
-- Constraints for table `note`
--
ALTER TABLE `note`
  ADD CONSTRAINT `fk_elevi_note` FOREIGN KEY (`elev`) REFERENCES `elevi` (`id`);

--
-- Constraints for table `profesori`
--
ALTER TABLE `profesori`
  ADD CONSTRAINT `fk_tipuri_profesori` FOREIGN KEY (`tip_cont`) REFERENCES `tipuri_utilizatori` (`id`);

--
-- Constraints for table `profesori_clase`
--
ALTER TABLE `profesori_clase`
  ADD CONSTRAINT `fk_clase` FOREIGN KEY (`clasa`) REFERENCES `clase` (`id`),
  ADD CONSTRAINT `fk_profesori2` FOREIGN KEY (`profesor`) REFERENCES `profesori` (`id`);

--
-- Constraints for table `profesori_materii`
--
ALTER TABLE `profesori_materii`
  ADD CONSTRAINT `fk_materii` FOREIGN KEY (`materie`) REFERENCES `materii` (`id`),
  ADD CONSTRAINT `fk_profesori` FOREIGN KEY (`profesor`) REFERENCES `profesori` (`id`);

--
-- Constraints for table `secretari`
--
ALTER TABLE `secretari`
  ADD CONSTRAINT `fk_tipuri_secretari` FOREIGN KEY (`tip_cont`) REFERENCES `tipuri_utilizatori` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
