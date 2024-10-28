-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 18 oct. 2024 à 20:17
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gab`
--

-- --------------------------------------------------------

--
-- Structure de la table `banque`
--

CREATE TABLE `banque` (
  `id_banque` int(11) NOT NULL,
  `nom_banque` varchar(50) NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `banque`
--

INSERT INTO `banque` (`id_banque`, `nom_banque`, `date_creation`) VALUES
(1, 'BCB', '2024-09-30 12:33:42'),
(2, 'Interbank', '2024-09-30 12:34:53'),
(3, 'BCAB', '2024-09-30 12:35:35'),
(4, 'KCB', '2024-09-30 12:36:59'),
(5, 'FinBank', '2024-09-30 12:38:21'),
(6, 'CRDB', '2024-10-09 16:45:44'),
(7, 'Internationnal', '2024-10-10 16:56:16');

-- --------------------------------------------------------

--
-- Structure de la table `commune`
--

CREATE TABLE `commune` (
  `id_commune` int(11) NOT NULL,
  `nom_commune` varchar(100) NOT NULL,
  `id_province` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commune`
--

INSERT INTO `commune` (`id_commune`, `nom_commune`, `id_province`) VALUES
(2, 'Ntahangwa', 1),
(3, 'Mukaza', 1),
(5, 'Muha', 1);

-- --------------------------------------------------------

--
-- Structure de la table `contacts`
--

CREATE TABLE `contacts` (
  `id_contact` int(11) NOT NULL,
  `numer_tel` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `contacts`
--

INSERT INTO `contacts` (`id_contact`, `numer_tel`, `email`, `message`) VALUES
(4, '666', 'donstephane@gmail.com', 'i made an update with prepare statement'),
(7, '12365478', 'martinjunior@gmail.com', 'Form verification'),
(8, '76946604', 'heritierzlatan2@gmail.com', 'bonjour');

-- --------------------------------------------------------

--
-- Structure de la table `guichet`
--

CREATE TABLE `guichet` (
  `id_guichet` int(11) NOT NULL,
  `nom_guichet` varchar(100) NOT NULL,
  `id_banque` int(11) DEFAULT NULL,
  `id_quartier` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `guichet`
--

INSERT INTO `guichet` (`id_guichet`, `nom_guichet`, `id_banque`, `id_quartier`) VALUES
(3, 'Gard du nord', 1, 1),
(7, 'Kamenge 1er avenue', 2, 4),
(9, 'kigobe Market', 1, 7),
(10, 'Gasenyi', 6, 7),
(11, 'Bwiza 1er avenue', 2, 8),
(12, 'Ruganigwa 1er avenue', 1, 11);

-- --------------------------------------------------------

--
-- Structure de la table `province`
--

CREATE TABLE `province` (
  `id_province` int(11) NOT NULL,
  `nom_province` varchar(50) NOT NULL,
  `id_guichet` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `province`
--

INSERT INTO `province` (`id_province`, `nom_province`, `id_guichet`) VALUES
(1, 'Bujumbura', NULL),
(2, 'Muyinga', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `quartier`
--

CREATE TABLE `quartier` (
  `id_quartier` int(11) NOT NULL,
  `nom_quartier` varchar(50) NOT NULL,
  `id_zone` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `quartier`
--

INSERT INTO `quartier` (`id_quartier`, `nom_quartier`, `id_zone`) VALUES
(1, 'Heha', 1),
(4, 'Kinama', 1),
(6, 'Gitega', 3),
(7, 'Kigobe', 4),
(8, 'Bwiza', 9),
(9, 'Buyenzi', 10),
(11, 'Ruganigwa', 12),
(12, 'Ruhongore', 13);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `image` varchar(1024) DEFAULT NULL,
  `email` varchar(225) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(1024) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id_user`, `image`, `email`, `username`, `password`, `role`) VALUES
(1, NULL, 'heir@gmail.com', 'heir', '$2y$10$6Qrm10njrDrgAnmSMwDg8ulVGq.AhWlYzY34YYWSBGGEkjlMlnbc6', 'admin'),
(2, NULL, 'junior@gmail.com', 'junior', '$2y$10$h0Q4zY1kacRrx1ETOG3ahOC8GRJaPSWvASqLnYX10J77AEXmbar2i', 'admin');

-- --------------------------------------------------------

--
-- Structure de la table `zone`
--

CREATE TABLE `zone` (
  `id_zone` int(11) NOT NULL,
  `nom_zone` varchar(50) NOT NULL,
  `id_commune` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `zone`
--

INSERT INTO `zone` (`id_zone`, `nom_zone`, `id_commune`) VALUES
(1, 'Kamenge', 2),
(3, 'Kinama', 2),
(4, 'Gihosha', 2),
(5, 'Kigobe', 2),
(6, 'Rohero', 3),
(8, 'Nyakabiga', 3),
(9, 'Bwiza', 3),
(10, 'Buyenzi', 3);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `banque`
--
ALTER TABLE `banque`
  ADD PRIMARY KEY (`id_banque`);

--
-- Index pour la table `commune`
--
ALTER TABLE `commune`
  ADD PRIMARY KEY (`id_commune`),
  ADD KEY `fk_province` (`id_province`);

--
-- Index pour la table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id_contact`);

--
-- Index pour la table `guichet`
--
ALTER TABLE `guichet`
  ADD PRIMARY KEY (`id_guichet`),
  ADD KEY `id_quartier` (`id_quartier`),
  ADD KEY `fk_guichet_banque` (`id_banque`);

--
-- Index pour la table `province`
--
ALTER TABLE `province`
  ADD PRIMARY KEY (`id_province`),
  ADD KEY `id_guichet` (`id_guichet`);

--
-- Index pour la table `quartier`
--
ALTER TABLE `quartier`
  ADD PRIMARY KEY (`id_quartier`),
  ADD KEY `id_commune` (`id_zone`),
  ADD KEY `id_zone` (`id_zone`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- Index pour la table `zone`
--
ALTER TABLE `zone`
  ADD PRIMARY KEY (`id_zone`),
  ADD KEY `fk_commune` (`id_commune`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `banque`
--
ALTER TABLE `banque`
  MODIFY `id_banque` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `commune`
--
ALTER TABLE `commune`
  MODIFY `id_commune` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id_contact` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `guichet`
--
ALTER TABLE `guichet`
  MODIFY `id_guichet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `province`
--
ALTER TABLE `province`
  MODIFY `id_province` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `quartier`
--
ALTER TABLE `quartier`
  MODIFY `id_quartier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `zone`
--
ALTER TABLE `zone`
  MODIFY `id_zone` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commune`
--
ALTER TABLE `commune`
  ADD CONSTRAINT `fk_province` FOREIGN KEY (`id_province`) REFERENCES `province` (`id_province`) ON DELETE CASCADE;

--
-- Contraintes pour la table `guichet`
--
ALTER TABLE `guichet`
  ADD CONSTRAINT `fk_guichet_banque` FOREIGN KEY (`id_banque`) REFERENCES `banque` (`id_banque`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_guichet_quartier` FOREIGN KEY (`id_quartier`) REFERENCES `quartier` (`id_quartier`) ON DELETE CASCADE;

--
-- Contraintes pour la table `zone`
--
ALTER TABLE `zone`
  ADD CONSTRAINT `fk_commune` FOREIGN KEY (`id_commune`) REFERENCES `commune` (`id_commune`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
