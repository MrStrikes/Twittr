-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  sam. 12 mai 2018 à 14:33
-- Version du serveur :  5.7.19
-- Version de PHP :  7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `twttr`
--

-- --------------------------------------------------------

--
-- Structure de la table `follow`
--

DROP TABLE IF EXISTS `follow`;
CREATE TABLE IF NOT EXISTS `follow` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `follower_id` int(11) NOT NULL,
  `followed_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `follow`
--

INSERT INTO `follow` (`id`, `follower_id`, `followed_id`) VALUES
(28, 4, 6);

-- --------------------------------------------------------

--
-- Structure de la table `ratings`
--

DROP TABLE IF EXISTS `ratings`;
CREATE TABLE IF NOT EXISTS `ratings` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `twtt_id` int(11) NOT NULL,
  `rating` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `re_twtts`
--

DROP TABLE IF EXISTS `re_twtts`;
CREATE TABLE IF NOT EXISTS `re_twtts` (
  `re_twtt_id` int(11) NOT NULL,
  `twtt_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `creation` datetime NOT NULL,
  PRIMARY KEY (`re_twtt_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `twtts`
--

DROP TABLE IF EXISTS `twtts`;
CREATE TABLE IF NOT EXISTS `twtts` (
  `twtt_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `creation` datetime NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`twtt_id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `twtts`
--

INSERT INTO `twtts` (`twtt_id`, `user_id`, `creation`, `content`) VALUES
(39, 6, '2018-05-11 12:34:16', 'test 1'),
(40, 6, '2018-05-11 13:14:56', 'test2'),
(41, 6, '2018-05-11 13:14:59', 'test 3');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(255) UNSIGNED NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `at_username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `at_username`, `password`, `email`) VALUES
(4, 'Maxime', 'Marechal', 'seth', 'seth', '$2y$10$Q8uEP7uAZ46/I4f0QSrwBOt4E9/jqx/9D8ZGeHsjXhie4ypUFwFoC', 'a@a.a'),
(5, 'Maxime', 'Marechal', 'fastfire', 'fastfire', '$2y$10$4JuBF9SNksgyOcel.Xbhluiy7Vz.kZq8RUVLftED9OEHb4c7iU0Sa', 'a@a.b'),
(6, 'Maxime', 'Marechal', 'fastfire2', 'fastfire2', '$2y$10$abIWYJyGvEANRug72s7Y0ubdvcrLgmY8.KDTi3T8SBuciVCkugDAq', 'a@a.c');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
