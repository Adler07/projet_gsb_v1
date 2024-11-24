-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2024 at 11:52 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gsb1`
--

-- --------------------------------------------------------

--
-- Table structure for table `fiche_frais`
--

CREATE TABLE `fiche_frais` (
  `n°fiche_frais` int(11) NOT NULL,
  `montant_repas` decimal(10,2) DEFAULT NULL,
  `nombre_repas` int(11) NOT NULL,
  `montant_hebergement` decimal(10,2) DEFAULT NULL,
  `nombre_hebergement` int(11) NOT NULL,
  `montant_deplacement` decimal(10,2) DEFAULT NULL,
  `nombre_deplacement` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `date_soumission` date NOT NULL,
  `justificatif` varchar(255) NOT NULL,
  `kilometres_voiture` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fiche_frais`
--

INSERT INTO `fiche_frais` (`n°fiche_frais`, `montant_repas`, `nombre_repas`, `montant_hebergement`, `nombre_hebergement`, `montant_deplacement`, `nombre_deplacement`, `total`, `date_soumission`, `justificatif`, `kilometres_voiture`) VALUES
(1, 120.00, 0, 250.00, 0, 720.00, 0, 1090.00, '2024-10-01', '', 0),
(2, 0.00, 0, 1250.00, 0, 2800.00, 0, 4050.00, '2024-09-11', '', 0),
(3, 20.00, 0, 50.00, 0, 0.00, 0, 70.00, '2024-08-01', '', 0),
(4, 400.00, 0, 250.00, 0, 75.00, 0, 725.00, '2024-07-27', '', 0),
(5, 86.00, 0, 37.00, 0, 991.00, 0, 1114.00, '2024-07-10', '', 0),
(9, 11.00, 0, 1250.00, 0, 993.00, 0, 2254.00, '2024-11-02', '', 0),
(10, 11.00, 0, 1250.00, 0, 993.00, 0, 2254.00, '2024-11-02', '', 0),
(11, 789.00, 0, 999.99, 0, 25.00, 0, 1813.99, '2024-03-09', '', 0),
(12, 40.00, 4, 250.00, 3, 100.00, 4, 390.00, '2024-11-06', 'assets\\uploadsEtonnement.pdf', 0),
(13, 120.00, 5, 250.00, 4, NULL, 0, 0.00, '2024-11-12', 'assets\\uploadsPreparation_Partie_Theorique.pdf', NULL),
(14, 120.00, 11, 350.00, 6, NULL, 0, 0.00, '2024-11-13', 'assets\\uploadsTutoriel d\'Intégration de Chart.js.pdf', 40);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fiche_frais`
--
ALTER TABLE `fiche_frais`
  ADD PRIMARY KEY (`n°fiche_frais`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fiche_frais`
--
ALTER TABLE `fiche_frais`
  MODIFY `n°fiche_frais` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
