-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Hôte : sql303.infinityfree.com
-- Généré le :  mar. 24 mars 2026 à 19:00
-- Version du serveur :  11.4.10-MariaDB
-- Version de PHP :  7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `if0_38934862_bdfoot2benkrouidembelkhiri`
--

-- --------------------------------------------------------

--
-- Structure de la table `club`
--

CREATE TABLE `club` (
  `idclub` int(11) NOT NULL,
  `nomcourt` varchar(20) DEFAULT NULL,
  `nomlong` varchar(60) DEFAULT NULL,
  `logo` varchar(20) DEFAULT NULL,
  `fondation` int(11) DEFAULT NULL,
  `president` varchar(50) DEFAULT NULL,
  `entraineur` varchar(50) DEFAULT NULL,
  `site` varchar(40) DEFAULT NULL,
  `nbpoints` int(11) DEFAULT NULL,
  `butsmarques` int(11) DEFAULT NULL,
  `butsencaisses` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `club`
--

INSERT INTO `club` (`idclub`, `nomcourt`, `nomlong`, `logo`, `fondation`, `president`, `entraineur`, `site`, `nbpoints`, `butsmarques`, `butsencaisses`) VALUES
(1, 'Angers', 'Angers Sporting Club de l\'Ouest', 'angers.png', 1919, 'Romain Chabane', 'Alexandre Dujeux', 'www.angers-sco.fr', 16, 16, 26),
(2, 'Auxerre', 'Association de la Jeunesse Auxerroise', 'auxerre.png', 1905, 'Baptiste Malherbe', 'Christophe Pélissier', 'www.aja.fr', 21, 24, 26),
(3, 'Brest', 'Stade Brestois 29', 'brest.png', 1950, 'Denis Le Saint', 'Éric Roy', 'www.SB29.com', 19, 24, 29),
(4, 'Le Havre', 'Le Havre Athletic Club Football', 'lehavre.png', 1872, 'Jean-Michel Roussier', 'Didier Digard', 'www.hac-foot.com', 12, 12, 34),
(5, 'Lens', 'Racing Club de Lens', 'lens.png', 1906, 'Joseph Oughourlian', 'Will Still', 'www.rclens.fr', 24, 19, 15),
(6, 'Lille', 'Losc Lille', 'lille.png', 1944, 'Olivier Létang', 'Bruno Génésio', 'www.losc.fr', 28, 26, 16),
(7, 'Lyon', 'Olympique Lyonnais', 'lyon.png', 1950, 'John Textor', 'Pierre Sage', 'www.ol.fr', 28, 28, 20),
(8, 'Marseille', 'Olympique de Marseille', 'marseille.png', 1899, 'Pablo Longoria', 'Roberto De Zerbi', 'www.om.net', 33, 37, 19),
(9, 'Monaco', 'Association Sportive de Monaco Football Club', 'monaco.png', 1924, 'Dmitri Rybolovlev', 'Adi Hutter', 'www.asmonaco.com', 30, 26, 16),
(10, 'Montpellier', 'Montpellier Hérault Sport Club', 'montpellier.png', 1919, 'Laurent Nicollin', 'Jean-Louis Gasset', 'www.mhscfoot.com', 9, 15, 39),
(11, 'Nantes', 'Football Club de Nantes', 'nantes.png', 1943, 'Franck Kita', 'Antoine Kombouaré', 'www.fcnantes.com', 15, 18, 25),
(12, 'Nice', 'Olympique Gymnaste Club de Nice Côte d\'Azur', 'nice.png', 1904, 'Jean-Pierre Rivère', 'Franck Haise', 'www.ogcnice.com', 27, 31, 21),
(13, 'Paris', 'Paris-Saint-Germain Football Club', 'paris.png', 1970, 'Nasser Al-Khelaifi', 'Luis Enrique', 'www.psg.fr', 40, 44, 14),
(14, 'Reims', 'Stade de Reims', 'reims.png', 1931, 'Jean-Pierre Caillot', 'Luka Elsner', 'www.stade-de-reims.com', 20, 21, 21),
(15, 'Rennes', 'Stade Rennais Football Club', 'rennes.png', 1901, 'Arnaud Pouille', 'Jorge Sampaoli', 'www.staderennais.com', 17, 22, 23),
(16, 'Saint-Etienne', 'Association Sportive de Saint-Etienne', 'saintetienne.png', 1919, 'Ivan Gazidis', 'Laurent Huard', 'www.asse.fr', 16, 15, 35),
(17, 'Strasbourg', 'Racing Club de Strasbourg Alsace', 'strasbourg.png', 1906, 'Marc Keller', 'Liam Rosenior', 'www.rcstrasbourgalsace.fr', 20, 28, 28),
(18, 'Toulouse', 'Toulouse Football Club', 'toulouse.png', 1970, 'Damien Comolli', 'Carles Martinez Novell', 'www.toulousefc.com', 24, 18, 17);

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

CREATE TABLE `commentaire` (
  `idcom` int(11) NOT NULL,
  `datecom` date DEFAULT NULL,
  `libelle` text NOT NULL,
  `idjournee` int(2) NOT NULL DEFAULT 0,
  `numrenc` int(2) NOT NULL DEFAULT 0,
  `idutil` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `journee`
--

CREATE TABLE `journee` (
  `idjournee` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `journee`
--

INSERT INTO `journee` (`idjournee`) VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9),
(10),
(11),
(12),
(13),
(14),
(15),
(16),
(17),
(18),
(19),
(20),
(21),
(22),
(23),
(24),
(25),
(26),
(27),
(28),
(29),
(30),
(31),
(32),
(33),
(34);

-- --------------------------------------------------------

--
-- Structure de la table `rencontre`
--

CREATE TABLE `rencontre` (
  `idjournee` int(11) NOT NULL DEFAULT 0,
  `numrenc` int(11) NOT NULL DEFAULT 0,
  `daterenc` date DEFAULT NULL,
  `heurerenc` time DEFAULT NULL,
  `idclubdom` int(11) NOT NULL,
  `idclubext` int(11) NOT NULL,
  `scoreclubdom` int(11) DEFAULT NULL,
  `scoreclubext` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `rencontre`
--

INSERT INTO `rencontre` (`idjournee`, `numrenc`, `daterenc`, `heurerenc`, `idclubdom`, `idclubext`, `scoreclubdom`, `scoreclubext`) VALUES
(1, 1, '2024-08-16', '20:45:00', 4, 13, 1, 4),
(1, 2, '2024-08-17', '17:00:00', 3, 8, 1, 5),
(1, 3, '2024-08-17', '19:00:00', 14, 6, 0, 2),
(1, 4, '2024-08-17', '21:00:00', 9, 16, 1, 0),
(1, 5, '2024-08-18', '15:00:00', 2, 12, 2, 1),
(1, 6, '2024-08-18', '17:00:00', 1, 5, 0, 1),
(1, 7, '2024-08-18', '17:00:00', 18, 11, 0, 0),
(1, 8, '2024-08-18', '17:00:00', 10, 17, 1, 1),
(1, 9, '2024-08-18', '20:45:00', 15, 7, 3, 0),
(2, 1, '2024-08-23', '20:45:00', 13, 10, 6, 0),
(2, 2, '2024-08-24', '17:00:00', 7, 9, 0, 2),
(2, 3, '2024-08-24', '19:00:00', 6, 1, 2, 0),
(2, 4, '2024-08-24', '21:00:00', 16, 4, 0, 2),
(2, 5, '2024-08-25', '15:00:00', 5, 3, 2, 0),
(2, 6, '2024-08-25', '17:00:00', 17, 15, 3, 1),
(2, 7, '2024-08-25', '17:00:00', 12, 18, 1, 1),
(2, 8, '2024-08-25', '17:00:00', 11, 2, 2, 0),
(2, 9, '2024-08-25', '20:45:00', 8, 14, 2, 2),
(3, 1, '2024-08-30', '20:45:00', 7, 17, 4, 3),
(3, 2, '2024-08-31', '17:00:00', 3, 16, 4, 0),
(3, 3, '2024-08-31', '19:00:00', 10, 11, 1, 3),
(3, 4, '2024-08-31', '21:00:00', 18, 8, 1, 3),
(3, 5, '2024-09-01', '15:00:00', 9, 5, 1, 1),
(3, 6, '2024-09-01', '17:00:00', 1, 12, 1, 4),
(3, 7, '2024-09-01', '17:00:00', 14, 15, 2, 1),
(3, 8, '2024-09-01', '17:00:00', 4, 2, 3, 1),
(3, 9, '2024-09-01', '20:45:00', 6, 13, 1, 3),
(4, 1, '2024-09-13', '20:45:00', 16, 6, 1, 0),
(4, 2, '2024-09-14', '17:00:00', 8, 12, 2, 0),
(4, 3, '2024-09-14', '19:00:00', 2, 9, 0, 3),
(4, 4, '2024-09-14', '21:00:00', 13, 3, 3, 1),
(4, 5, '2024-09-15', '15:00:00', 15, 10, 3, 0),
(4, 6, '2024-09-15', '17:00:00', 18, 4, 2, 0),
(4, 7, '2024-09-15', '17:00:00', 11, 14, 1, 2),
(4, 8, '2024-09-15', '17:00:00', 17, 1, 1, 1),
(4, 9, '2024-09-15', '20:45:00', 5, 7, 0, 0),
(5, 1, '2024-09-20', '20:45:00', 12, 16, 8, 0),
(5, 2, '2024-09-21', '17:00:00', 6, 17, 3, 3),
(5, 3, '2024-09-21', '19:00:00', 15, 5, 1, 1),
(5, 4, '2024-09-21', '21:00:00', 14, 13, 1, 1),
(5, 5, '2024-09-22', '15:00:00', 9, 4, 3, 1),
(5, 6, '2024-09-22', '17:00:00', 10, 2, 3, 2),
(5, 7, '2024-09-22', '17:00:00', 1, 11, 1, 1),
(5, 8, '2024-09-22', '17:00:00', 3, 18, 2, 0),
(5, 9, '2024-09-22', '20:45:00', 7, 8, 2, 3),
(6, 1, '2024-09-27', '19:00:00', 2, 3, 3, 0),
(6, 2, '2024-09-27', '21:00:00', 13, 15, 3, 1),
(6, 3, '2024-09-28', '17:00:00', 5, 12, 0, 0),
(6, 4, '2024-09-28', '19:00:00', 4, 6, 0, 3),
(6, 5, '2024-09-28', '21:00:00', 9, 10, 2, 1),
(6, 6, '2024-09-29', '15:00:00', 18, 7, 1, 2),
(6, 7, '2024-09-29', '17:00:00', 11, 16, 2, 2),
(6, 8, '2024-09-29', '17:00:00', 1, 14, 1, 3),
(6, 9, '2024-09-29', '20:45:00', 17, 8, 1, 0),
(7, 1, '2024-10-04', '20:45:00', 8, 1, 1, 1),
(7, 2, '2024-10-05', '17:00:00', 16, 2, 3, 1),
(7, 3, '2024-10-05', '19:00:00', 6, 18, 2, 1),
(7, 4, '2024-10-05', '21:00:00', 15, 9, 1, 2),
(7, 5, '2024-10-06', '15:00:00', 7, 11, 2, 0),
(7, 6, '2024-10-06', '17:00:00', 14, 10, 4, 2),
(7, 7, '2024-10-06', '17:00:00', 3, 4, 2, 0),
(7, 8, '2024-10-06', '17:00:00', 17, 5, 2, 2),
(7, 9, '2024-10-06', '20:45:00', 12, 13, 1, 1),
(8, 1, '2024-10-18', '20:45:00', 9, 6, 0, 0),
(8, 2, '2024-10-19', '17:00:00', 3, 15, 1, 1),
(8, 3, '2024-10-19', '19:00:00', 16, 5, 0, 2),
(8, 4, '2024-10-19', '21:00:00', 13, 17, 4, 2),
(8, 5, '2024-10-20', '15:00:00', 4, 7, 0, 4),
(8, 6, '2024-10-20', '17:00:00', 18, 1, 1, 1),
(8, 7, '2024-10-20', '17:00:00', 2, 14, 2, 1),
(8, 8, '2024-10-20', '17:00:00', 11, 12, 1, 1),
(8, 9, '2024-10-20', '20:45:00', 10, 8, 0, 5),
(9, 1, '2024-10-25', '20:45:00', 15, 4, 1, 0),
(9, 2, '2024-10-26', '17:00:00', 1, 16, 4, 2),
(9, 3, '2024-10-26', '19:00:00', 14, 3, 1, 2),
(9, 4, '2024-10-26', '21:00:00', 5, 6, 0, 2),
(9, 5, '2024-10-27', '15:00:00', 7, 2, 2, 2),
(9, 6, '2024-10-27', '17:00:00', 17, 11, 3, 1),
(9, 7, '2024-10-27', '17:00:00', 12, 9, 2, 1),
(9, 8, '2024-10-27', '17:00:00', 10, 18, 0, 3),
(9, 9, '2024-10-27', '20:45:00', 8, 13, 0, 3),
(10, 1, '2024-11-01', '19:00:00', 9, 1, 0, 1),
(10, 2, '2024-11-01', '21:00:00', 6, 7, 1, 1),
(10, 3, '2024-11-02', '17:00:00', 13, 5, 1, 0),
(10, 4, '2024-11-02', '19:00:00', 3, 12, 0, 1),
(10, 5, '2024-11-02', '21:00:00', 16, 17, 2, 0),
(10, 6, '2024-11-03', '15:00:00', 18, 14, 1, 0),
(10, 7, '2024-11-03', '17:00:00', 4, 10, 1, 0),
(10, 8, '2024-11-03', '17:00:00', 2, 15, 4, 0),
(10, 9, '2024-11-03', '20:45:00', 11, 8, 1, 2),
(11, 1, '2024-11-08', '20:45:00', 8, 2, 1, 3),
(11, 2, '2024-11-09', '17:00:00', 17, 9, 1, 3),
(11, 3, '2024-11-09', '19:00:00', 5, 11, 3, 2),
(11, 4, '2024-11-09', '21:00:00', 1, 13, 2, 4),
(11, 5, '2024-11-10', '15:00:00', 12, 6, 2, 2),
(11, 6, '2024-11-10', '17:00:00', 4, 14, 0, 3),
(11, 7, '2024-11-10', '17:00:00', 10, 3, 3, 1),
(11, 8, '2024-11-10', '17:00:00', 15, 18, 0, 2),
(11, 9, '2024-11-10', '20:45:00', 7, 16, 1, 0),
(12, 1, '2024-11-22', '19:00:00', 9, 3, 3, 2),
(12, 2, '2024-11-22', '21:00:00', 13, 18, 3, 0),
(12, 3, '2024-11-23', '17:00:00', 5, 8, 1, 3),
(12, 4, '2024-11-23', '19:00:00', 16, 10, 1, 0),
(12, 5, '2024-11-23', '21:00:00', 14, 7, 1, 1),
(12, 6, '2024-11-24', '15:00:00', 6, 15, 1, 0),
(12, 7, '2024-11-24', '17:00:00', 11, 4, 0, 2),
(12, 8, '2024-11-24', '17:00:00', 2, 1, 1, 0),
(12, 9, '2024-11-24', '20:45:00', 12, 17, 2, 1),
(13, 1, '2024-11-29', '20:45:00', 14, 5, 0, 2),
(13, 2, '2024-11-30', '17:00:00', 15, 16, 5, 0),
(13, 3, '2024-11-30', '19:00:00', 3, 17, 3, 1),
(13, 4, '2024-11-30', '21:00:00', 13, 11, 1, 1),
(13, 5, '2024-12-01', '15:00:00', 10, 6, 2, 2),
(13, 6, '2024-12-01', '17:00:00', 18, 2, 2, 0),
(13, 7, '2024-12-01', '17:00:00', 7, 12, 4, 1),
(13, 8, '2024-12-01', '17:00:00', 4, 1, 0, 1),
(13, 9, '2024-12-01', '20:45:00', 8, 9, 2, 1),
(14, 1, '2024-12-06', '19:00:00', 6, 3, 3, 1),
(14, 2, '2024-12-06', '21:00:00', 2, 13, 0, 0),
(14, 3, '2024-12-07', '17:00:00', 9, 18, 2, 0),
(14, 4, '2024-12-07', '19:00:00', 12, 4, 2, 1),
(14, 5, '2024-12-07', '21:00:00', 1, 7, 0, 3),
(14, 6, '2024-12-08', '15:00:00', 5, 10, 2, 0),
(14, 7, '2024-12-08', '17:00:00', 17, 14, 0, 0),
(14, 8, '2024-12-08', '17:00:00', 11, 15, 1, 0),
(14, 9, '2024-12-08', '20:45:00', 16, 8, 0, 2),
(15, 1, '2024-12-13', '20:45:00', 18, 16, 2, 1),
(15, 2, '2024-12-14', '17:00:00', 8, 6, 1, 1),
(15, 3, '2024-12-14', '19:00:00', 2, 5, 2, 2),
(15, 4, '2024-12-14', '21:00:00', 14, 9, 0, 0),
(15, 5, '2024-12-15', '15:00:00', 10, 12, 2, 2),
(15, 6, '2024-12-15', '17:00:00', 15, 1, 2, 0),
(15, 7, '2024-12-15', '17:00:00', 4, 17, 0, 3),
(15, 8, '2024-12-15', '17:00:00', 3, 11, 4, 1),
(15, 9, '2024-12-15', '20:45:00', 13, 7, 3, 1),
(16, 1, '2024-12-18', '21:00:00', 9, 13, 2, 4),
(16, 2, '2025-01-03', '21:00:00', 12, 15, 3, 2),
(16, 3, '2025-01-04', '17:00:00', 16, 14, 3, 1),
(16, 4, '2025-01-04', '19:00:00', 6, 11, 1, 1),
(16, 5, '2025-01-04', '21:00:00', 7, 10, 1, 0),
(16, 6, '2025-01-05', '15:00:00', 1, 3, 2, 0),
(16, 7, '2025-01-05', '15:00:00', 17, 2, 3, 1),
(16, 8, '2025-01-05', '15:00:00', 5, 18, 0, 1),
(16, 9, '2025-01-05', '20:45:00', 8, 4, 5, 1),
(17, 1, '2025-01-10', '21:00:00', 2, 6, 0, 0),
(17, 2, '2025-01-12', '17:15:00', 10, 1, 0, 0),
(17, 3, '2025-01-11', '19:00:00', 14, 12, 0, 0),
(17, 4, '2025-01-12', '20:45:00', 13, 16, 0, 0),
(17, 5, '2025-01-12', '17:15:00', 18, 17, 0, 0),
(17, 6, '2025-01-12', '15:00:00', 4, 5, 0, 0),
(17, 7, '2025-01-11', '17:00:00', 3, 7, 0, 0),
(17, 8, '2025-01-10', '19:00:00', 11, 9, 0, 0),
(17, 9, '2025-01-11', '21:00:00', 15, 8, 0, 0),
(18, 1, '2025-01-17', '21:00:00', 6, 12, 0, 0),
(18, 2, '2025-01-19', '20:45:00', 8, 17, 0, 0),
(18, 3, '2025-01-17', '19:00:00', 10, 9, 0, 0),
(18, 4, '2025-01-18', '21:00:00', 7, 18, 0, 0),
(18, 5, '2025-01-18', '19:00:00', 15, 3, 0, 0),
(18, 6, '2025-01-19', '15:00:00', 16, 11, 0, 0),
(18, 7, '2025-01-18', '17:00:00', 5, 13, 0, 0),
(18, 8, '2025-01-19', '17:15:00', 14, 4, 0, 0),
(18, 9, '2025-01-19', '17:15:00', 1, 2, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `idutil` int(11) NOT NULL,
  `pseudoutil` varchar(30) NOT NULL DEFAULT '',
  `mdputil` varchar(30) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`idutil`, `pseudoutil`, `mdputil`) VALUES
(1, 'Yanis', 'jiwcyh-9Fuzte-vaztoq'),
(2, 'Waz', 'azerty'),
(3, 'aminecaca', 'pipi'),
(4, 'Lam', 'Lam'),
(5, 'aymen', '1'),
(6, 'peron', 'peronperon'),
(7, 'Belkhiri', 'eyoub213'),
(8, 'NATHANVIANDE', '1207'),
(9, 'yanis viande', 'yanis'),
(10, 'yanis viande2', 'yanisviande2motdepasse');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `club`
--
ALTER TABLE `club`
  ADD PRIMARY KEY (`idclub`);

--
-- Index pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`idcom`),
  ADD KEY `idjournee` (`idjournee`),
  ADD KEY `numrenc` (`numrenc`),
  ADD KEY `idutil` (`idutil`),
  ADD KEY `commentaire_ibfk_1` (`idjournee`,`numrenc`);

--
-- Index pour la table `journee`
--
ALTER TABLE `journee`
  ADD PRIMARY KEY (`idjournee`);

--
-- Index pour la table `rencontre`
--
ALTER TABLE `rencontre`
  ADD PRIMARY KEY (`idjournee`,`numrenc`),
  ADD KEY `idclubdom` (`idclubdom`),
  ADD KEY `idclubext` (`idclubext`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`idutil`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commentaire`
--
ALTER TABLE `commentaire`
  MODIFY `idcom` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `idutil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `commentaire_ibfk_1` FOREIGN KEY (`idjournee`,`numrenc`) REFERENCES `rencontre` (`idjournee`, `numrenc`),
  ADD CONSTRAINT `commentaire_ibfk_2` FOREIGN KEY (`idutil`) REFERENCES `utilisateur` (`idutil`);

--
-- Contraintes pour la table `rencontre`
--
ALTER TABLE `rencontre`
  ADD CONSTRAINT `rencontre_ibfk_1` FOREIGN KEY (`idjournee`) REFERENCES `journee` (`idjournee`) ON UPDATE CASCADE,
  ADD CONSTRAINT `rencontre_ibfk_2` FOREIGN KEY (`idclubdom`) REFERENCES `club` (`idclub`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rencontre_ibfk_3` FOREIGN KEY (`idclubext`) REFERENCES `club` (`idclub`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
