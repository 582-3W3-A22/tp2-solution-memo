-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 28, 2022 at 05:26 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `memo`
--
CREATE DATABASE IF NOT EXISTS `memo` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `memo`;

-- --------------------------------------------------------

--
-- Table structure for table `tache`
--

CREATE TABLE `tache` (
  `id` int(11) NOT NULL,
  `texte` varchar(200) CHARACTER SET latin1 NOT NULL COMMENT 'Texte de la tâche.',
  `accomplie` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Valeur 0 pour non-accomplie, et 1 pour accomplie.',
  `date_ajout` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'La date à laquelle la tâche est ajoutée',
  `utilisateur_id` int(11) DEFAULT NULL COMMENT 'Ce champ n''est pas utilisé dans le TP, ignorez-le!'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `tache`
--

INSERT INTO `tache` (`id`, `texte`, `accomplie`, `date_ajout`, `utilisateur_id`) VALUES
(1, 'Appeler papa', 0, '2022-12-25 09:42:25', NULL),
(2, 'Installer Git', 1, '2022-10-05 05:12:13', NULL),
(3, 'Relire le solutionnaire de l\'exercice #2', 1, '2022-11-27 18:05:50', NULL),
(4, 'Travailler sur le TP #2', 1, '2022-11-27 18:12:04', NULL),
(31, 'Regarder pour la 5ème fois la meilleure série TV jamais produite (The Wire)', 0, '2022-12-28 11:25:33', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tache`
--
ALTER TABLE `tache`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tache`
--
ALTER TABLE `tache`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
COMMIT;
