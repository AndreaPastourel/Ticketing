-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 31 mars 2025 à 09:22
-- Version du serveur : 8.2.0
-- Version de PHP : 8.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion`
--

DELIMITER $$
--
-- Procédures
--
CREATE PROCEDURE `AjouterMaintenance` (IN `p_id_equipement` INT, IN `p_date` DATE)   BEGIN
    -- Ajouter un enregistrement dans la table gestion_maintenance
    INSERT INTO gestion_maintenance (id_equipement, date)
    VALUES (p_id_equipement, p_date);

    -- Mettre à jour le champ historiqueMaintenance de l'équipement
    UPDATE gestion_equipement
    SET historiqueMaintenance = historiqueMaintenance + 1
    WHERE id_equipement = p_id_equipement;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `equipements`
--

CREATE TABLE `equipements` (
  `id_equipement` int NOT NULL,
  `nom` varchar(255) NOT NULL,
  `type` int NOT NULL,
  `marque` varchar(255) NOT NULL,
  `numeroSerie` int NOT NULL,
  `garantie` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `equipements`
--

INSERT INTO `equipements` (`id_equipement`, `nom`, `type`, `marque`, `numeroSerie`, `garantie`) VALUES
(1, 'test', 1, 'test', 12, 'test');

-- --------------------------------------------------------

--
-- Structure de la table `maintenances`
--

CREATE TABLE `maintenances` (
  `id_maintenance` int NOT NULL,
  `id_equipement` int NOT NULL,
  `date` date NOT NULL,
  `status` enum('en cours','terminee') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tickets`
--

CREATE TABLE `tickets` (
  `id` int NOT NULL,
  `dateCreation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dateModification` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `objet` varchar(255) NOT NULL,
  `message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `etat` enum('attente','traité') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'attente',
  `reponse` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `id_users` int NOT NULL,
  `id_equipement` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `tickets`
--

INSERT INTO `tickets` (`id`, `dateCreation`, `dateModification`, `objet`, `message`, `etat`, `reponse`, `id_users`, `id_equipement`) VALUES
(1, '2024-12-10 16:02:36', '2024-12-10 16:02:36', 'test', 'test', 'traité', NULL, 1, 1),
(4, '2024-12-17 09:42:57', '2024-12-17 09:42:57', 'test', 'test', 'attente', NULL, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `types`
--

CREATE TABLE `types` (
  `id_type` int NOT NULL,
  `nom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `types`
--

INSERT INTO `types` (`id_type`, `nom`) VALUES
(1, 'test');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nom` varchar(100) NOT NULL,
  `role` enum('admin','utilisateur') NOT NULL,
  `username` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `role`, `username`, `prenom`, `mdp`, `email`) VALUES
(1, 'test', 'admin', 'test', 'testModif', '$2y$10$KQHkBThHjwdQeKtKScgQDeNwHJnU7eNhMvbChEZWyScLNpHD/XqFO', 'test@ui.com'),
(2, 'test', 'utilisateur', 'test', 'test', '$2y$10$ecmCmoA7PBjpQLwllnkVKuvGc93wpoVlY39BuhcqJx8cHIPv4G6ne', 'test@ui.com');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `equipements`
--
ALTER TABLE `equipements`
  ADD PRIMARY KEY (`id_equipement`),
  ADD KEY `type` (`type`);

--
-- Index pour la table `maintenances`
--
ALTER TABLE `maintenances`
  ADD PRIMARY KEY (`id_maintenance`);

--
-- Index pour la table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket` (`id_users`),
  ADD KEY `equipement` (`id_equipement`);

--
-- Index pour la table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id_type`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `equipements`
--
ALTER TABLE `equipements`
  MODIFY `id_equipement` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `maintenances`
--
ALTER TABLE `maintenances`
  MODIFY `id_maintenance` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `types`
--
ALTER TABLE `types`
  MODIFY `id_type` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `equipements`
--
ALTER TABLE `equipements`
  ADD CONSTRAINT `type` FOREIGN KEY (`type`) REFERENCES `types` (`id_type`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `equipement` FOREIGN KEY (`id_equipement`) REFERENCES `equipements` (`id_equipement`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `ticket` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
