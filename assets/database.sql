-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le :  mer. 02 mai 2018 à 16:07
-- Version du serveur :  5.6.38
-- Version de PHP :  7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  `Twittr`
--

-- --------------------------------------------------------

--
-- Structure de la table `Follow`
--

CREATE TABLE `Follow` (
  `id` int(10) UNSIGNED NOT NULL,
  `follower_id` int(11) NOT NULL,
  `followed_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `Follow`
--

INSERT INTO `Follow` (`id`, `follower_id`, `followed_id`) VALUES
(1, 3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `twtts`
--

CREATE TABLE `twtts` (
  `twtt_id` int(11) NOT NULL,
  `type` set('twtt','retwtt','fav') NOT NULL,
  `author_id` int(11) NOT NULL,
  `rt/fav_author_id` int(11) NOT NULL,
  `creation` datetime NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `twtts`
--

INSERT INTO `twtts` (`twtt_id`, `type`, `author_id`, `rt/fav_author_id`, `creation`, `content`) VALUES
(14, 'twtt', 1, 1, '2018-04-25 16:29:00', 'dzdzzea'),
(15, 'twtt', 3, 3, '2018-04-26 15:36:23', 'rdgdz'),
(16, 'twtt', 1, 1, '2018-05-02 13:26:22', 'aeionfaznijfaz'),
(17, 'twtt', 1, 1, '2018-05-02 13:26:23', 'aeionfaznijfaz'),
(18, 'twtt', 1, 1, '2018-05-02 13:26:23', 'aeionfaznijfaz');

-- --------------------------------------------------------

--
-- Structure de la table `Users`
--

CREATE TABLE `Users` (
  `id` int(255) UNSIGNED NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `at_username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `Users`
--

INSERT INTO `Users` (`id`, `firstname`, `lastname`, `username`, `at_username`, `password`, `email`) VALUES
(1, 'Yanis', 'Yanis', 'Yanis', 'Yanis', '$2y$10$c/bwaRuGzE0DxpVfOJ1qfuYTnKUAk2T4/XeOEtwq7AxIwyMFvejAy', 'me@yanisbendahmane.fr'),
(3, 'Strikes', 'Strikes', 'Strikes', 'Strikes', '$2y$10$xT3r69iQF/q7zttaFwB3jOALPwu2UmfgWu4QuQiMxM0bD4rWPNb2e', 'yanis.bendahmane@supinternet.fr');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Follow`
--
ALTER TABLE `Follow`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `twtts`
--
ALTER TABLE `twtts`
  ADD PRIMARY KEY (`twtt_id`);

--
-- Index pour la table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Follow`
--
ALTER TABLE `Follow`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `twtts`
--
ALTER TABLE `twtts`
  MODIFY `twtt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `Users`
--
ALTER TABLE `Users`
  MODIFY `id` int(255) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
