-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le :  mar. 16 juin 2020 à 13:04
-- Version du serveur :  5.7.26
-- Version de PHP :  7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `seabnb`
--

-- --------------------------------------------------------

--
-- Structure de la table `adverts`
--

CREATE TABLE `adverts` (
  `id_ad` int(11) NOT NULL,
  `name_ad` varchar(255) NOT NULL,
  `main_img_ad` text,
  `description_ad` text NOT NULL,
  `price_ad` int(11) NOT NULL,
  `secondary_img_ad` text,
  `position_ad` text NOT NULL,
  `id_users` int(11) NOT NULL,
  `country` varchar(3) DEFAULT NULL,
  `date_ad` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `adverts`
--

INSERT INTO `adverts` (`id_ad`, `name_ad`, `main_img_ad`, `description_ad`, `price_ad`, `secondary_img_ad`, `position_ad`, `id_users`, `country`, `date_ad`) VALUES
(1, 'Yoat Maritime', 'img/a/1/main_img.jpg', 'Bonjour, \r\nC\'est marseille.\r\n\r\nadadada', 100, 'img/a/1/secondary_img.jpg', 'Marseille', 1, 'fr', '2020-06-08 00:00:00'),
(2, 'Jet-Ski', 'img/a/2/main_img.jpeg', 'Bonjour, \r\nC\'est Faro.\r\n\r\nadadada', 20, 'img/a/2/secondary_img.jpeg', 'Barcelone', 1, 'es', '2020-04-06 16:49:12'),
(23, 'Bateau de Pêche', 'img/a/23/main_img.jpg', 'Bonjour,\r\nJe loue mon bateau de pêche ', 89, 'img/a/23/secondary_img.jpg', 'Rotterdam', 1, 'nl', '2020-06-12 14:24:54'),
(24, 'Peniche', 'img/a/24/main_img.jpg', 'Bonjour,\r\n\r\nJe loue ma péniche pas sérieux s’abstenir ', 78, 'img/a/24/secondary_img.jpg', 'Paris', 1, 'fr', '2020-06-12 16:57:11');

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

CREATE TABLE `reservation` (
  `id_reservation` int(11) NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `nb_person` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `id_advert` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `confirmation_token` varchar(60) DEFAULT NULL,
  `confirmed_at` datetime DEFAULT NULL,
  `reset_token` varchar(60) DEFAULT NULL,
  `reset_at` datetime DEFAULT NULL,
  `admin` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `profil_pic` text,
  `wallet` float DEFAULT '500',
  `bio` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `confirmation_token`, `confirmed_at`, `reset_token`, `reset_at`, `admin`, `name`, `first_name`, `profil_pic`, `wallet`, `bio`) VALUES
(1, 'Gomerinho', 'marvin.goliath95@gmail.com', '$2y$10$M1BM64F2KTi7ayhTkhwRe.unYBSJ1AEeklbpWQlQahF6/zC4CYCEK', NULL, '2020-06-07 14:35:36', NULL, NULL, 1, 'GOMES VITORINO', 'Marvin', 'img/users/1/pp.jpg', 78, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `adverts`
--
ALTER TABLE `adverts`
  ADD PRIMARY KEY (`id_ad`),
  ADD KEY `id_user` (`id_users`);

--
-- Index pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id_reservation`),
  ADD KEY `id_users` (`id_users`),
  ADD KEY `id_advert` (`id_advert`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `adverts`
--
ALTER TABLE `adverts`
  MODIFY `id_ad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id_reservation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `adverts`
--
ALTER TABLE `adverts`
  ADD CONSTRAINT `id_user` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `id_advert` FOREIGN KEY (`id_advert`) REFERENCES `adverts` (`id_ad`),
  ADD CONSTRAINT `id_users` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
