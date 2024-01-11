-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour au_paradis_d_eve_test
CREATE DATABASE IF NOT EXISTS `au_paradis_d_eve_test` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `au_paradis_d_eve_test`;

-- Listage de la structure de table au_paradis_d_eve_test. categorie
CREATE TABLE IF NOT EXISTS `categorie` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_categorie` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table au_paradis_d_eve_test.categorie : ~12 rows (environ)
INSERT INTO `categorie` (`id`, `nom_categorie`) VALUES
	(1, 'Chambre'),
	(2, 'Jardin'),
	(3, 'Terrasse'),
	(4, 'Piscine'),
	(5, 'Salle de bain'),
	(6, 'Salon'),
	(7, 'Aire de jeu'),
	(8, 'Cuisine'),
	(9, 'Garage'),
	(10, 'Parking'),
	(11, 'Logement entier'),
	(12, 'Salle à manger');

-- Listage de la structure de table au_paradis_d_eve_test. doctrine_migration_versions
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Listage des données de la table au_paradis_d_eve_test.doctrine_migration_versions : ~6 rows (environ)
INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
	('DoctrineMigrations\\Version20231219161738', '2023-12-19 16:19:29', 270),
	('DoctrineMigrations\\Version20231219173449', '2023-12-19 17:35:03', 40),
	('DoctrineMigrations\\Version20231219173624', '2023-12-19 17:36:31', 98),
	('DoctrineMigrations\\Version20240109201552', '2024-01-09 20:17:11', 70),
	('DoctrineMigrations\\Version20240110132505', '2024-01-10 13:25:21', 95),
	('DoctrineMigrations\\Version20240110145402', '2024-01-10 14:54:08', 77);

-- Listage de la structure de table au_paradis_d_eve_test. espace
CREATE TABLE IF NOT EXISTS `espace` (
  `id` int NOT NULL AUTO_INCREMENT,
  `categorie_id` int NOT NULL,
  `nom_espace` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `taille` double NOT NULL,
  `wifi` tinyint(1) NOT NULL,
  `nb_places` int DEFAULT NULL,
  `prix` double NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `IDX_6AB096DBCF5E72D` (`categorie_id`),
  CONSTRAINT `FK_6AB096DBCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table au_paradis_d_eve_test.espace : ~6 rows (environ)
INSERT INTO `espace` (`id`, `categorie_id`, `nom_espace`, `taille`, `wifi`, `nb_places`, `prix`, `description`) VALUES
	(15, 1, 'Test Espace33', 14, 1, 56, 59, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'),
	(16, 1, 'Chambre du Soleil', 27, 0, 2, 45, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'),
	(17, 2, 'Jardin Sud', 50, 0, 9, 0, 'Jardin Test'),
	(21, 1, 'Test remplacement txt avec plusieurs img', 45, 0, 3, 50, NULL);

-- Listage de la structure de table au_paradis_d_eve_test. image
CREATE TABLE IF NOT EXISTS `image` (
  `id` int NOT NULL AUTO_INCREMENT,
  `espace_id` int DEFAULT NULL,
  `lien_image` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt_image` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `IDX_C53D045FB6885C6C` (`espace_id`),
  CONSTRAINT `FK_C53D045FB6885C6C` FOREIGN KEY (`espace_id`) REFERENCES `espace` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table au_paradis_d_eve_test.image : ~8 rows (environ)
INSERT INTO `image` (`id`, `espace_id`, `lien_image`, `alt_image`) VALUES
	(5, 15, 'cc96f6acf4a0a16d23ef15e44af4423e.jpg', NULL),
	(6, 16, '897de879b10db0904e7e4503e9c42358.jpg', NULL),
	(7, 17, 'a743ceb4381d118445fe645be27455f4.jpg', NULL),
	(17, 21, 'a2ce8cea0a226c7c2a24b7ff26c78399.jpg', 'Texte de remplacement avec 2 img'),
	(18, 21, 'ffb71606587ec2c2ba7e08b0162b990a.jpg', 'Texte de remplacement avec 2 img');

-- Listage de la structure de table au_paradis_d_eve_test. messenger_messages
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table au_paradis_d_eve_test.messenger_messages : ~0 rows (environ)

-- Listage de la structure de table au_paradis_d_eve_test. option
CREATE TABLE IF NOT EXISTS `option` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `tarif` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table au_paradis_d_eve_test.option : ~3 rows (environ)
INSERT INTO `option` (`id`, `nom`, `description`, `tarif`) VALUES
	(1, 'Dîner', 'test description', 11),
	(2, 'Brunch', 'test description', 6),
	(3, 'Option 3', 'test description', 6);

-- Listage de la structure de table au_paradis_d_eve_test. reservation
CREATE TABLE IF NOT EXISTS `reservation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `espace_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` int NOT NULL,
  `nb_personnes` int NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `prix_total` double DEFAULT NULL,
  `note` double DEFAULT NULL,
  `avis` longtext COLLATE utf8mb4_unicode_ci,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse_facturation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_reservation` datetime DEFAULT CURRENT_TIMESTAMP,
  `statut` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_42C84955B6885C6C` (`espace_id`),
  KEY `IDX_42C84955A76ED395` (`user_id`),
  CONSTRAINT `FK_42C84955A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_42C84955B6885C6C` FOREIGN KEY (`espace_id`) REFERENCES `espace` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table au_paradis_d_eve_test.reservation : ~2 rows (environ)
INSERT INTO `reservation` (`id`, `espace_id`, `user_id`, `prenom`, `nom`, `telephone`, `nb_personnes`, `date_debut`, `date_fin`, `prix_total`, `note`, `avis`, `email`, `adresse_facturation`, `facture`, `date_reservation`, `statut`) VALUES
	(13, 15, 22, 'Stewie', 'Griffin', 444719, 1, '2024-01-22 15:00:00', '2024-01-28 11:00:00', 306, NULL, NULL, 'stewie@griffin', '31 Spooner Street 00093 Quahog Rhode Island', 'lien.pdf', '2024-01-11 14:17:42', 'CONFIRMEE'),
	(14, 15, 22, 'Stewie', 'Griffin', 444719, 1, '2024-02-19 15:00:00', '2024-03-16 11:00:00', 1486, NULL, NULL, 'stewie@griffin', '31 Spooner Street 00093 Quahog Rhode Island', 'lien.pdf', '2024-01-11 14:19:48', 'CONFIRMEE');

-- Listage de la structure de table au_paradis_d_eve_test. reservation_option
CREATE TABLE IF NOT EXISTS `reservation_option` (
  `reservation_id` int NOT NULL,
  `option_id` int NOT NULL,
  PRIMARY KEY (`reservation_id`,`option_id`),
  KEY `IDX_1277492BB83297E7` (`reservation_id`),
  KEY `IDX_1277492BA7C41D6F` (`option_id`),
  CONSTRAINT `FK_1277492BA7C41D6F` FOREIGN KEY (`option_id`) REFERENCES `option` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_1277492BB83297E7` FOREIGN KEY (`reservation_id`) REFERENCES `reservation` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table au_paradis_d_eve_test.reservation_option : ~2 rows (environ)
INSERT INTO `reservation_option` (`reservation_id`, `option_id`) VALUES
	(13, 1),
	(14, 1);

-- Listage de la structure de table au_paradis_d_eve_test. user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pseudo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cp` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ville` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pays` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table au_paradis_d_eve_test.user : ~12 rows (environ)
INSERT INTO `user` (`id`, `email`, `roles`, `password`, `pseudo`, `adresse`, `cp`, `ville`, `pays`, `is_verified`) VALUES
	(1, 'davina@deglingo.fr', '["ROLE_ADMIN"]', '$2y$13$gs0DAuuP3JGP3WSkkmIBh.w6c87RuYXMDkOY.m0e/2TmuoNqQL2ym', 'davina', NULL, NULL, NULL, NULL, 0),
	(13, 'ricardo@psycho.fr', '["ROLE_USER"]', '$2y$13$Zmk5Vjtr59VSkwad1g/5m./fihnoCNT4Jh6AkjxLPpi4fU1j8dRJ2', 'ricardo', NULL, NULL, NULL, NULL, 0),
	(14, 'granit@granit.granit', '[]', '$2y$13$Beekfg7D.ZQD3.JNOFHkkObRSpMFjEtatj1XPwkY/0leW9whF70vW', 'granit', '31 Spooner Street', '00093', 'Quahog', 'Rhode Island', 1),
	(15, 'daz@daz.fr', '[]', '$2y$13$.nfg5WmdTrSi86YKzmZdGeJu.zXNARt/IqFQN3z33jMbU1FYp.39y', 'daz', NULL, NULL, NULL, NULL, 0),
	(16, 'zaz@zaz.fr', '[]', '$2y$13$UxvwX0Cnvo9N8G0qwoeBnebSRWu3e5nJcmsJZ9G.XHdHRVG6/RjQu', 'zaz', NULL, NULL, NULL, NULL, 0),
	(17, 'ad@ad.fr', '[]', '$2y$13$00HSHSdFbj22L6TfbaXov.s3yVWRJ.Uw12C6kQeRbI9Wt9lDsHFd.', 'admin', NULL, NULL, NULL, NULL, 0),
	(18, 'zae@fre.fr', '[]', '$2y$13$mju64FvmPvv.AfXdURalA.leHgbE03fgsx5Ix5PJAsjBDkypGD952', 'zae', NULL, NULL, NULL, NULL, 0),
	(19, 'tet@tet.fr', '[]', '$2y$13$cwHzd8t6enj3kH.pQJn9j.Eb4Z836.kD8y3OAH3hxR5yxEegZ/xk6', 'user', NULL, NULL, NULL, NULL, 0),
	(22, 'admin@admin.fr', '["ROLE_ADMIN"]', '$2y$13$7TBrqeAM7no3O2jw1tbo2eaVvTQuehAnQaIofJdnqaZt8fEFiCy/m', 'admin', NULL, NULL, NULL, NULL, 0),
	(23, 'test@user.fr', '[]', '$2y$13$uj7wveDZhIvsKGHUAXO44OEKnmmOXjBWI7ewI0kE7oeLFa55stcWu', 'Test User', NULL, NULL, NULL, NULL, 0),
	(24, 'test2@user.fr', '[]', '$2y$13$U2J84UfPNTsZetqbIzVShufOzMpoRk5.aL45ey/T3nJgDrmqkxdoi', 'Test User2', NULL, NULL, NULL, NULL, 0),
	(25, 'josephine@gmail.fr', '[]', '$2y$13$LSu8fZ8/oJ6oZ0crMT/75.GRyGDytFu.elBNDzyAo8kWQm2F/KYgq', 'Joséphine', '31 Spooner Street', '00093', 'Quahog', 'Rhode Island', 0),
	(26, 'gregoire@greg.fr', '[]', '$2y$13$pmynHjS2sUSSV9zZww/nPOmkuaIGXYbtJCkUTEMWEr6R.mVtLF2UG', 'TestavecConfirmation', NULL, NULL, NULL, NULL, 0);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
