-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 30, 2024 at 07:21 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `liikuntasuorituksia`
--

-- --------------------------------------------------------

--
-- Table structure for table `aikayksikko`
--

CREATE TABLE `aikayksikko` (
  `yksikko_id` int(11) NOT NULL,
  `yksikko_kuvaus` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `aikayksikko`
--

INSERT INTO `aikayksikko` (`yksikko_id`, `yksikko_kuvaus`) VALUES
(1, 'minuuttia'),
(2, 'askelta');

-- --------------------------------------------------------

--
-- Stand-in structure for view `kaikkitiedot`
-- (See below for the actual view)
--
CREATE TABLE `kaikkitiedot` (
`id_suoritus` int(11)
,`laji` int(11)
,`laji_kuvaus` varchar(50)
,`kayttaja` int(11)
,`username` varchar(50)
,`aikamaara` int(11)
,`aikayksikko` int(11)
,`yksikko_kuvaus` varchar(255)
,`pvmklo` datetime
,`kommentti` varchar(255)
);

-- --------------------------------------------------------

--
-- Table structure for table `laji`
--

CREATE TABLE `laji` (
  `laji_id` int(11) NOT NULL,
  `laji_kuvaus` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `laji`
--

INSERT INTO `laji` (`laji_id`, `laji_kuvaus`) VALUES
(1, 'pyöräily'),
(2, 'hyötyliikunta'),
(3, 'kävely'),
(4, 'hölkkä'),
(5, 'jalkapallo'),
(6, 'askeltaminen');

-- --------------------------------------------------------

--
-- Table structure for table `suoritus`
--

CREATE TABLE `suoritus` (
  `id_suoritus` int(11) NOT NULL,
  `laji` int(11) NOT NULL,
  `kayttaja` int(11) NOT NULL,
  `pvmklo` datetime NOT NULL,
  `aikamaara` int(11) NOT NULL,
  `aikayksikko` int(11) NOT NULL,
  `kommentti` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `suoritus`
--

INSERT INTO `suoritus` (`id_suoritus`, `laji`, `kayttaja`, `pvmklo`, `aikamaara`, `aikayksikko`, `kommentti`) VALUES
(8, 2, 4, '2024-01-12 10:40:00', 45, 1, 'dfdgddggd'),
(9, 1, 5, '2024-01-10 10:46:00', 10, 1, 'yyyyy'),
(10, 2, 6, '2024-01-11 10:48:00', 23, 1, 'cxxbxb'),
(17, 6, 2, '2024-01-07 13:18:00', 60, 2, 'yy'),
(18, 4, 2, '2024-01-09 13:23:00', 40, 1, 'ppppp'),
(19, 4, 2, '2024-01-15 13:36:00', 10, 1, '11'),
(20, 5, 2, '2024-01-16 13:36:00', 15, 1, '22'),
(21, 6, 2, '2024-01-01 13:37:00', 200, 2, '33'),
(22, 2, 2, '2024-01-08 13:38:00', 32, 1, '44'),
(23, 3, 2, '2024-01-16 13:39:00', 24, 1, '55'),
(25, 6, 2, '2024-01-18 22:21:00', 22, 2, 'ккк'),
(26, 6, 2, '2024-01-18 23:04:00', 50, 2, 'new2'),
(27, 6, 2, '2024-01-18 23:14:00', 22, 2, 'new3'),
(28, 4, 2, '2024-01-18 23:22:00', 1, 1, 'new 4'),
(29, 3, 2, '2024-01-18 23:33:00', 1, 1, 'new4'),
(30, 1, 2, '2024-01-07 23:43:00', 33, 1, 'new7'),
(31, 6, 2, '2024-01-03 00:04:00', 1, 2, 't');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(2, 'Katja', '$2y$10$YxJ31l97aAys1j0VhoqBQu40SUb/WSjMZbPfga4z0ki6nii2tktkW', '2024-01-07 19:00:00'),
(3, 'admin', '$2y$10$.3a5V91y3IMA3thMkcMgWeFRzcksM5r9Ct4CwkUp3QJq7C39crnAC', '2024-01-08 19:06:38'),
(4, ' Aada', '$2y$10$k/6M1w9PnaMIsRp4KrWLdO./BEb2Vi/Vv.DNI255xM.EkAZOBZoF.', '2024-01-09 19:07:01'),
(5, ' Aino', '$2y$10$mPqzymsVeQ87YD5R7YU7H.MH4s/9qjWTb8CbG.NN5t0KWW435Lal.', '2024-01-10 19:00:00'),
(6, 'Roippa', '$2y$10$tmXJiGBsZiEUDXqIcgz4tuH5gSvgPJyZjBPQ/eH8ZWL1Q5/DYy.6.', '2024-01-11 19:00:00');

-- --------------------------------------------------------

--
-- Structure for view `kaikkitiedot`
--
DROP TABLE IF EXISTS `kaikkitiedot`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `kaikkitiedot`  AS SELECT `suoritus`.`id_suoritus` AS `id_suoritus`, `suoritus`.`laji` AS `laji`, `laji`.`laji_kuvaus` AS `laji_kuvaus`, `suoritus`.`kayttaja` AS `kayttaja`, `users`.`username` AS `username`, `suoritus`.`aikamaara` AS `aikamaara`, `suoritus`.`aikayksikko` AS `aikayksikko`, `aikayksikko`.`yksikko_kuvaus` AS `yksikko_kuvaus`, `suoritus`.`pvmklo` AS `pvmklo`, `suoritus`.`kommentti` AS `kommentti` FROM (((`suoritus` join `laji` on(`laji`.`laji_id` = `suoritus`.`laji`)) join `users` on(`users`.`id` = `suoritus`.`kayttaja`)) join `aikayksikko` on(`aikayksikko`.`yksikko_id` = `suoritus`.`aikayksikko`))  ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aikayksikko`
--
ALTER TABLE `aikayksikko`
  ADD PRIMARY KEY (`yksikko_id`),
  ADD KEY `yksikko_id` (`yksikko_id`);

--
-- Indexes for table `laji`
--
ALTER TABLE `laji`
  ADD PRIMARY KEY (`laji_id`),
  ADD KEY `laji_id` (`laji_id`);

--
-- Indexes for table `suoritus`
--
ALTER TABLE `suoritus`
  ADD PRIMARY KEY (`id_suoritus`),
  ADD KEY `kayttaya` (`kayttaja`),
  ADD KEY `laji` (`laji`),
  ADD KEY `aikayksikko` (`aikayksikko`),
  ADD KEY `kayttaja` (`kayttaja`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aikayksikko`
--
ALTER TABLE `aikayksikko`
  MODIFY `yksikko_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `laji`
--
ALTER TABLE `laji`
  MODIFY `laji_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `suoritus`
--
ALTER TABLE `suoritus`
  MODIFY `id_suoritus` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `suoritus`
--
ALTER TABLE `suoritus`
  ADD CONSTRAINT `suoritus_ibfk_2` FOREIGN KEY (`laji`) REFERENCES `laji` (`laji_id`),
  ADD CONSTRAINT `suoritus_ibfk_3` FOREIGN KEY (`aikayksikko`) REFERENCES `aikayksikko` (`yksikko_id`),
  ADD CONSTRAINT `suoritus_ibfk_4` FOREIGN KEY (`kayttaja`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
