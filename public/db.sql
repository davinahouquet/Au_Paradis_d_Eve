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


-- Listage de la structure de la base pour au_paradis_d_eve
CREATE DATABASE IF NOT EXISTS `au_paradis_d_eve` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `au_paradis_d_eve`;

-- Listage de la structure de table au_paradis_d_eve. categorie
CREATE TABLE IF NOT EXISTS `categorie` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_categorie` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table au_paradis_d_eve.categorie : ~12 rows (environ)
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

-- Listage de la structure de table au_paradis_d_eve. doctrine_migration_versions
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Listage des données de la table au_paradis_d_eve.doctrine_migration_versions : ~0 rows (environ)
INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
	('DoctrineMigrations\\Version20231013141710', '2023-10-13 14:17:27', 67);

-- Listage de la structure de table au_paradis_d_eve. espace
CREATE TABLE IF NOT EXISTS `espace` (
  `id` int NOT NULL AUTO_INCREMENT,
  `categorie_id` int NOT NULL,
  `nom_espace` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `taille` double NOT NULL,
  `wifi` tinyint(1) NOT NULL,
  `nb_places` int DEFAULT NULL,
  `prix` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6AB096DBCF5E72D` (`categorie_id`),
  CONSTRAINT `FK_6AB096DBCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table au_paradis_d_eve.espace : ~8 rows (environ)
INSERT INTO `espace` (`id`, `categorie_id`, `nom_espace`, `taille`, `wifi`, `nb_places`, `prix`) VALUES
	(1, 1, 'Chambre des Liilas', 23, 1, 2, 40),
	(2, 1, 'Chambre du Tournesol', 15, 1, 2, 40),
	(3, 1, 'Chambre du Temps', 30, 1, 4, 50),
	(4, 6, 'Salon principal', 45, 1, NULL, 0),
	(5, 11, 'Appartement Azure', 120, 1, 6, 120),
	(6, 1, 'Chambre Maeva', 19, 1, 3, 60),
	(7, 1, 'Chambre Magalie', 20, 1, 2, 60),
	(8, 1, 'Chambre Davina', 10, 1, 2, 50);

-- Listage de la structure de table au_paradis_d_eve. image
CREATE TABLE IF NOT EXISTS `image` (
  `id` int NOT NULL AUTO_INCREMENT,
  `espace_id` int DEFAULT NULL,
  `lien_image` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt_image` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C53D045FB6885C6C` (`espace_id`),
  CONSTRAINT `FK_C53D045FB6885C6C` FOREIGN KEY (`espace_id`) REFERENCES `espace` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table au_paradis_d_eve.image : ~0 rows (environ)
INSERT INTO `image` (`id`, `espace_id`, `lien_image`, `alt_image`) VALUES
	(1, 1, 'chambreLilas.jpg', 'chambre des lilas');

-- Listage de la structure de table au_paradis_d_eve. messenger_messages
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table au_paradis_d_eve.messenger_messages : ~8 rows (environ)
INSERT INTO `messenger_messages` (`id`, `body`, `headers`, `queue_name`, `created_at`, `available_at`, `delivered_at`) VALUES
	(1, 'O:36:\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\":2:{s:44:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\";a:1:{s:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\";a:1:{i:0;O:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\":1:{s:55:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\";s:21:\\"messenger.bus.default\\";}}}s:45:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\";O:51:\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\":2:{s:60:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\";O:28:\\"Symfony\\\\Component\\\\Mime\\\\Email\\":6:{i:0;s:0:\\"\\";i:1;s:5:\\"utf-8\\";i:2;s:5:\\"rgwrg\\";i:3;s:5:\\"utf-8\\";i:4;a:0:{}i:5;a:2:{i:0;O:37:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\":2:{s:46:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\";a:3:{s:4:\\"from\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:4:\\"From\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:18:\\"noreply@mysite.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:2:\\"to\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:2:\\"To\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:20:\\"siteadmin@hotmail.fr\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:7:\\"subject\\";a:1:{i:0;O:48:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:7:\\"Subject\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:55:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\";s:47:\\"Demande de contact sur votre site de dtjyuj@dhd\\";}}}s:49:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\";i:76;}i:1;N;}}s:61:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\";N;}}', '[]', 'default', '2023-10-11 10:02:58', '2023-10-11 10:02:58', NULL),
	(2, 'O:36:\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\":2:{s:44:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\";a:1:{s:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\";a:1:{i:0;O:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\":1:{s:55:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\";s:21:\\"messenger.bus.default\\";}}}s:45:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\";O:51:\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\":2:{s:60:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\";O:28:\\"Symfony\\\\Component\\\\Mime\\\\Email\\":6:{i:0;s:0:\\"\\";i:1;s:5:\\"utf-8\\";i:2;s:5:\\"rgwrg\\";i:3;s:5:\\"utf-8\\";i:4;a:0:{}i:5;a:2:{i:0;O:37:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\":2:{s:46:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\";a:3:{s:4:\\"from\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:4:\\"From\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:18:\\"noreply@mysite.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:2:\\"to\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:2:\\"To\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:20:\\"siteadmin@hotmail.fr\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:7:\\"subject\\";a:1:{i:0;O:48:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:7:\\"Subject\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:55:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\";s:47:\\"Demande de contact sur votre site de dtjyuj@dhd\\";}}}s:49:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\";i:76;}i:1;N;}}s:61:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\";N;}}', '[]', 'default', '2023-10-11 10:03:26', '2023-10-11 10:03:26', NULL),
	(3, 'O:36:\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\":2:{s:44:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\";a:1:{s:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\";a:1:{i:0;O:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\":1:{s:55:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\";s:21:\\"messenger.bus.default\\";}}}s:45:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\";O:51:\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\":2:{s:60:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\";O:28:\\"Symfony\\\\Component\\\\Mime\\\\Email\\":6:{i:0;s:0:\\"\\";i:1;s:5:\\"utf-8\\";i:2;s:8:\\"shhtrhsr\\";i:3;s:5:\\"utf-8\\";i:4;a:0:{}i:5;a:2:{i:0;O:37:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\":2:{s:46:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\";a:3:{s:4:\\"from\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:4:\\"From\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:18:\\"noreply@mysite.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:2:\\"to\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:2:\\"To\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:20:\\"siteadmin@hotmail.fr\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:7:\\"subject\\";a:1:{i:0;O:48:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:7:\\"Subject\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:55:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\";s:47:\\"Demande de contact sur votre site de dtjyuj@dhd\\";}}}s:49:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\";i:76;}i:1;N;}}s:61:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\";N;}}', '[]', 'default', '2023-10-11 10:03:38', '2023-10-11 10:03:38', NULL),
	(4, 'O:36:\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\":2:{s:44:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\";a:1:{s:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\";a:1:{i:0;O:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\":1:{s:55:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\";s:21:\\"messenger.bus.default\\";}}}s:45:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\";O:51:\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\":2:{s:60:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\";O:28:\\"Symfony\\\\Component\\\\Mime\\\\Email\\":6:{i:0;s:0:\\"\\";i:1;s:5:\\"utf-8\\";i:2;s:6:\\"tsysrt\\";i:3;s:5:\\"utf-8\\";i:4;a:0:{}i:5;a:2:{i:0;O:37:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\":2:{s:46:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\";a:3:{s:4:\\"from\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:4:\\"From\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:18:\\"noreply@mysite.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:2:\\"to\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:2:\\"To\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:20:\\"siteadmin@hotmail.fr\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:7:\\"subject\\";a:1:{i:0;O:48:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:7:\\"Subject\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:55:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\";s:47:\\"Demande de contact sur votre site de dtjyuj@dhd\\";}}}s:49:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\";i:76;}i:1;N;}}s:61:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\";N;}}', '[]', 'default', '2023-10-11 10:07:02', '2023-10-11 10:07:02', NULL),
	(5, 'O:36:\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\":2:{s:44:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\";a:1:{s:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\";a:1:{i:0;O:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\":1:{s:55:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\";s:21:\\"messenger.bus.default\\";}}}s:45:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\";O:51:\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\":2:{s:60:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\";O:28:\\"Symfony\\\\Component\\\\Mime\\\\Email\\":6:{i:0;s:0:\\"\\";i:1;s:5:\\"utf-8\\";i:2;s:6:\\"rtsyrt\\";i:3;s:5:\\"utf-8\\";i:4;a:0:{}i:5;a:2:{i:0;O:37:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\":2:{s:46:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\";a:3:{s:4:\\"from\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:4:\\"From\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:18:\\"noreply@mysite.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:2:\\"to\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:2:\\"To\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:20:\\"siteadmin@hotmail.fr\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:7:\\"subject\\";a:1:{i:0;O:48:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:7:\\"Subject\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:55:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\";s:47:\\"Demande de contact sur votre site de dtjyuj@dhd\\";}}}s:49:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\";i:76;}i:1;N;}}s:61:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\";N;}}', '[]', 'default', '2023-10-11 11:45:59', '2023-10-11 11:45:59', NULL),
	(6, 'O:36:\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\":2:{s:44:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\";a:1:{s:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\";a:1:{i:0;O:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\":1:{s:55:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\";s:21:\\"messenger.bus.default\\";}}}s:45:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\";O:51:\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\":2:{s:60:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\";O:28:\\"Symfony\\\\Component\\\\Mime\\\\Email\\":6:{i:0;s:0:\\"\\";i:1;s:5:\\"utf-8\\";i:2;s:7:\\"jjrgjrg\\";i:3;s:5:\\"utf-8\\";i:4;a:0:{}i:5;a:2:{i:0;O:37:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\":2:{s:46:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\";a:3:{s:4:\\"from\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:4:\\"From\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:18:\\"noreply@mysite.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:2:\\"to\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:2:\\"To\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:20:\\"siteadmin@hotmail.fr\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:7:\\"subject\\";a:1:{i:0;O:48:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:7:\\"Subject\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:55:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\";s:48:\\"Demande de contact sur votre site de hhehehh@grq\\";}}}s:49:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\";i:76;}i:1;N;}}s:61:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\";N;}}', '[]', 'default', '2023-10-11 11:48:15', '2023-10-11 11:48:15', NULL),
	(7, 'O:36:\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\":2:{s:44:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\";a:1:{s:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\";a:1:{i:0;O:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\":1:{s:55:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\";s:21:\\"messenger.bus.default\\";}}}s:45:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\";O:51:\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\":2:{s:60:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\";O:28:\\"Symfony\\\\Component\\\\Mime\\\\Email\\":6:{i:0;s:0:\\"\\";i:1;s:5:\\"utf-8\\";i:2;s:7:\\"jjrgjrg\\";i:3;s:5:\\"utf-8\\";i:4;a:0:{}i:5;a:2:{i:0;O:37:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\":2:{s:46:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\";a:3:{s:4:\\"from\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:4:\\"From\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:18:\\"noreply@mysite.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:2:\\"to\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:2:\\"To\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:20:\\"siteadmin@hotmail.fr\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:7:\\"subject\\";a:1:{i:0;O:48:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:7:\\"Subject\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:55:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\";s:48:\\"Demande de contact sur votre site de hhehehh@grq\\";}}}s:49:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\";i:76;}i:1;N;}}s:61:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\";N;}}', '[]', 'default', '2023-10-11 11:48:18', '2023-10-11 11:48:18', NULL),
	(8, 'O:36:\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\":2:{s:44:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\";a:1:{s:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\";a:1:{i:0;O:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\":1:{s:55:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\";s:21:\\"messenger.bus.default\\";}}}s:45:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\";O:51:\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\":2:{s:60:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\";O:28:\\"Symfony\\\\Component\\\\Mime\\\\Email\\":6:{i:0;s:0:\\"\\";i:1;s:5:\\"utf-8\\";i:2;s:9:\\"rtsyhsrty\\";i:3;s:5:\\"utf-8\\";i:4;a:0:{}i:5;a:2:{i:0;O:37:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\":2:{s:46:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\";a:3:{s:4:\\"from\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:4:\\"From\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:18:\\"noreply@mysite.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:2:\\"to\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:2:\\"To\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:20:\\"siteadmin@hotmail.fr\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:7:\\"subject\\";a:1:{i:0;O:48:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:7:\\"Subject\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:55:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\";s:48:\\"Demande de contact sur votre site de hhehehh@grq\\";}}}s:49:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\";i:76;}i:1;N;}}s:61:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\";N;}}', '[]', 'default', '2023-10-11 11:59:13', '2023-10-11 11:59:13', NULL);

-- Listage de la structure de table au_paradis_d_eve. reservation
CREATE TABLE IF NOT EXISTS `reservation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `espace_id` int NOT NULL,
  `prenom` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` int NOT NULL,
  `nb_personnes` int NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `prix_total` double NOT NULL,
  `options` json DEFAULT NULL,
  `note` double DEFAULT NULL,
  `avis` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse_facturation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_42C84955B6885C6C` (`espace_id`),
  CONSTRAINT `FK_42C84955B6885C6C` FOREIGN KEY (`espace_id`) REFERENCES `espace` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table au_paradis_d_eve.reservation : ~17 rows (environ)
INSERT INTO `reservation` (`id`, `espace_id`, `prenom`, `nom`, `telephone`, `nb_personnes`, `date_debut`, `date_fin`, `prix_total`, `options`, `note`, `avis`, `email`, `adresse_facturation`, `facture`) VALUES
	(1, 1, 'UserPrenom', 'UserNom', 623365416, 2, '2023-10-10 00:00:00', '2023-12-10 00:00:00', 2440, NULL, NULL, NULL, 'test@test', 'test', 'test'),
	(2, 2, 'Test', 'Test', 613325715, 2, '2023-10-13 00:00:00', '2023-11-16 00:00:00', 1360, NULL, NULL, NULL, 'test@test', 'test', 'test'),
	(3, 3, '"trg', '"t(', 618208514, 2, '2018-01-01 00:00:00', '2019-01-01 00:00:00', 18250, NULL, NULL, NULL, 'test@test', 'test', 'test'),
	(4, 4, 'Test 1', 'test 1', 365980102, 5, '2024-02-02 00:00:00', '2024-03-02 00:00:00', 0, NULL, NULL, NULL, 'test@test', 'test', 'test'),
	(5, 3, '"trg', '"t(', 618208514, 2, '2018-01-01 00:00:00', '2019-01-01 00:00:00', 18250, NULL, NULL, NULL, '', NULL, ''),
	(7, 1, 'User', 'User', 61332502, 3, '2018-01-03 00:00:00', '2018-01-05 00:00:00', 80, NULL, NULL, NULL, '', NULL, ''),
	(8, 8, 'testuser', 'testuser', 613325702, 3, '2018-01-01 00:00:00', '2018-01-04 00:00:00', 120, NULL, NULL, NULL, 'test@test', 'test', 'test'),
	(9, 2, 'Test', 'Test', 613325715, 2, '2018-01-01 00:00:00', '2018-01-03 00:00:00', 80, NULL, NULL, NULL, '', NULL, ''),
	(10, 2, 'Test', 'Test', 613325715, 2, '2018-01-01 00:00:00', '2018-01-03 00:00:00', 80, NULL, NULL, NULL, '', NULL, ''),
	(11, 2, 'Test', 'Test', 613325715, 2, '2018-01-01 00:00:00', '2018-01-03 00:00:00', 80, NULL, NULL, NULL, '', NULL, ''),
	(12, 2, 'Test', 'Test', 613325715, 2, '2018-01-01 00:00:00', '2018-01-03 00:00:00', 80, NULL, NULL, NULL, '', NULL, ''),
	(13, 2, 'Test', 'Test', 613325715, 2, '2018-01-01 00:00:00', '2018-01-03 00:00:00', 80, NULL, NULL, NULL, '', NULL, ''),
	(14, 2, 'Test', 'Test', 613325715, 2, '2018-01-01 00:00:00', '2018-01-03 00:00:00', 80, NULL, NULL, NULL, '', NULL, ''),
	(15, 2, 'Test', 'Test', 613325715, 2, '2018-01-01 00:00:00', '2018-01-03 00:00:00', 80, NULL, NULL, NULL, '', NULL, ''),
	(16, 2, 'Test', 'Test', 613325715, 2, '2018-01-01 00:00:00', '2018-01-02 00:00:00', 40, NULL, NULL, NULL, '', NULL, ''),
	(17, 2, 'Test', 'Test', 613325715, 2, '2018-01-01 00:00:00', '2018-01-02 00:00:00', 40, NULL, NULL, NULL, '', NULL, ''),
	(18, 2, 'Test', 'Test', 613325715, 2, '2024-01-01 00:00:00', '2024-01-03 00:00:00', 80, NULL, NULL, NULL, 'test@test', NULL, '');

-- Listage de la structure de table au_paradis_d_eve. user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pseudo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cp` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ville` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pays` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table au_paradis_d_eve.user : ~2 rows (environ)
INSERT INTO `user` (`id`, `email`, `pseudo`, `roles`, `password`, `adresse`, `cp`, `ville`, `pays`, `is_verified`) VALUES
	(1, 'user@user.fr', 'user', '[]', '$2y$13$2CQb7SxND7L8jdFnk0QwN.X/RVmQtghHr91GnWJgXaoWBzajl8ZXS', NULL, NULL, NULL, NULL, 1),
	(2, 'stephane@hotmail.fr', 'Stéphane', '[]', '$2y$13$i.aQNTF/34ulqVQRHFVnhOeqHLlP1fbZQ/No/xmk3NTFtiJasHquS', 'nkln', 'bhk', 'kj', 'kdfv', 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
