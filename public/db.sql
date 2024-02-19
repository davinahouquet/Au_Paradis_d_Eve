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

-- Listage de la structure de table au_paradis_d_eve_test. booking
CREATE TABLE IF NOT EXISTS `booking` (
  `id` int NOT NULL AUTO_INCREMENT,
  `begin_at` datetime NOT NULL,
  `end_at` datetime DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table au_paradis_d_eve_test.booking : ~0 rows (environ)

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

-- Listage des données de la table au_paradis_d_eve_test.doctrine_migration_versions : ~8 rows (environ)
INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
	('DoctrineMigrations\\Version20231219161738', '2023-12-19 16:19:29', 270),
	('DoctrineMigrations\\Version20231219173449', '2023-12-19 17:35:03', 40),
	('DoctrineMigrations\\Version20231219173624', '2023-12-19 17:36:31', 98),
	('DoctrineMigrations\\Version20240109201552', '2024-01-09 20:17:11', 70),
	('DoctrineMigrations\\Version20240110132505', '2024-01-10 13:25:21', 95),
	('DoctrineMigrations\\Version20240110145402', '2024-01-10 14:54:08', 77),
	('DoctrineMigrations\\Version20240118200531', '2024-01-18 20:08:19', 83),
	('DoctrineMigrations\\Version20240118201112', '2024-01-18 20:11:16', 40);

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
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table au_paradis_d_eve_test.espace : ~15 rows (environ)
INSERT INTO `espace` (`id`, `categorie_id`, `nom_espace`, `taille`, `wifi`, `nb_places`, `prix`, `description`) VALUES
	(31, 8, 'Cuisine', 23, 1, NULL, 0, 'Cette cuisine, bien que modeste en taille, respire la convivialité et la simplicité, offrant un havre de paix où les saveurs traditionnelles rencontrent une atmosphère intime.\r\nLes murs sont revêtus d\'un doux ton de crème, complété par des accents de bois vieilli qui confèrent une touche rustique. Des étagères ouvertes, ornées de vaisselle en céramique artisanale et de pots en verre remplis d\'herbes séchées, ajoutent une dimension visuelle, créant une ambiance décontractée et naturelle.'),
	(32, 6, 'Salon', 30, 1, 10, 0, 'Bienvenue dans ce salon chaleureux et lumineux. Le grand canapé moelleux invite à la détente, entouré de meubles en bois élégants. Des touches de verdure avec des plantes ajoutent une atmosphère naturelle, et une cheminée centrale crée une ambiance cosy. La lumière naturelle inonde la pièce, accentuant la sensation d\'espace lumineux. Un lieu parfait pour se détendre, lire un livre près de la cheminée, ou partager des moments chaleureux avec vos proches.'),
	(33, 12, 'Salle à manger', 25, 1, 14, 0, 'La grande salle à manger peut accueillir confortablement 12 personnes, avec une table spacieuse et des chaises assorties, offrant un espace généreux pour les repas et les rassemblements. Dominant l\'espace, une imposante armoire en bois foncé ajoute une touche majestueuse. Ses détails sculptés et sa finition riche captent l\'attention, créant une atmosphère sophistiquée.'),
	(34, 5, 'Salle de bain', 20, 1, NULL, 0, 'Bienvenue dans cette salle de bain lumineuse et fonctionnelle. Les murs carrelés créent une ambiance propre et moderne. Vous trouverez ici une douche séparée par des parois en verre transparent et une baignoire pour des moments de détente. Le carrelage coordonné donne une esthétique harmonieuse à l\'ensemble.'),
	(35, 3, 'Terrasse', 40, 1, NULL, 0, 'Bienvenue sur cette terrasse en bois, un oasis de détente entouré de nature. Une grande table à manger pour 10 personnes trône au centre, parfaite pour des repas conviviaux en plein air. Un salon extérieur en osier ajoute une touche de confort, invitant à la détente sous les branches des arbres qui offrent une intimité naturelle.\r\n\r\nAu coin de la terrasse, un four à pizza promet des moments gourmands et conviviaux. L\'espace est soigneusement aménagé pour offrir une expérience en plein air agréable, enveloppée par le calme et la verdure. C\'est l\'endroit idéal pour partager des repas en famille, se détendre avec des amis et profiter du charme de la nature tout en préservant une intimité paisible.'),
	(36, 4, 'Piscine', 100, 0, NULL, 0, 'Bienvenue dans ce jardin paisible, où trône une piscine de 11 mètres de long. Entourée de verdure, elle offre un havre de fraîcheur au cœur de la nature. Les dalles en pierre soigneusement disposées créent une zone élégante et fonctionnelle autour de la piscine, invitant à la détente et aux bains de soleil.\r\nLa piscine, nichée au milieu du jardin sans aucun vis-à-vis, offre une intimité totale. Son design simple et épuré s\'intègre harmonieusement à l\'environnement naturel, créant un espace tranquille pour se rafraîchir par une chaude journée.'),
	(37, 2, 'Jardin bas', 250, 0, NULL, 0, 'Bienvenue dans ce vaste jardin luxuriant. Une étendue d\'herbe verte s\'étend à perte de vue, créant un cadre apaisant. Au centre, une élégante fontaine murmure doucement, ajoutant une touche rafraîchissante à l\'ensemble. Les parterres de plantes variées ajoutent des touches de couleur et de vie, créant une atmosphère vivante et harmonieuse. Un endroit idéal pour se promener, se détendre et profiter de la nature luxuriante qui entoure chaque coin de ce jardin spacieux.'),
	(38, 3, 'Terrasse Piscine', 100, 0, 10, 0, 'Bienvenue sur la terrasse à côté de la piscine, un espace accueillant et détendu. Un abri ouvert ajoute une touche de confort, offrant ombre et protection. Une balancelle invite à la relaxation, tandis qu\'une table avec des chaises offre un endroit idéal pour les repas en plein air. Des chaises longues complètent l\'ensemble, créant un lieu parfait pour se détendre, se rafraîchir et profiter de la vie en extérieur.'),
	(39, 1, 'Chambre 1', 25, 1, 2, 60, 'Bienvenue dans cette chambre à l\'ambiance douce et chaleureuse, baignée de teintes boisées et rosées. Les tons naturels du bois créent une atmosphère apaisante, tandis que les nuances de rose ajoutent une touche délicate à l\'ensemble.\r\nLa chambre offre une vue magnifique sur la citadelle, capturant l\'essence de l\'extérieur et apportant une sensation d\'ouverture. Les meubles en bois soulignent l\'esthétique naturelle, créant un espace cosy et élégant.'),
	(40, 1, 'Chambre 2', 25, 1, 2, 60, 'Bienvenue dans cette chambre à l\'ambiance raffinée, dominée par des teintes bleues et dorées. Les nuances de bleu créent une atmosphère apaisante et sophistiquée, tandis que les touches dorées ajoutent une touche d\'élégance.\r\n\r\nLes meubles et les détails de la chambre sont soigneusement choisis pour compléter cette palette de couleurs. La chambre offre une sensation de sérénité, chaque élément contribuant à créer une esthétique cohérente et agréable.'),
	(41, 1, 'Chambre 3', 10, 1, 2, 50, 'Bienvenue dans cette petite chambre à l\'élégance épurée, parée de teintes blanches et noires. Les murs blancs créent une atmosphère lumineuse et aérée, tandis que les touches noires ajoutent une sophistication moderne à l\'espace. Le mobilier minimaliste et les accessoires bien choisis contribuent à l\'esthétique épurée de la chambre. Chaque détail est pensé pour maximiser l\'espace et créer une ambiance confortable malgré la petite taille de la pièce.'),
	(42, 11, 'Chambre des randonneurs', 50, 1, 3, 65, 'Bienvenue dans ce sous-sol aménagé, un espace polyvalent combinant cuisine et point d\'eau. Les murs aux teintes neutres créent une atmosphère accueillante, tandis que les zones définies pour la cuisine et l\'espace lavabo apportent une fonctionnalité pratique.\r\nLa cuisine est équipée pour répondre à vos besoins culinaires, tandis que le point d\'eau assure commodité et confort. Cet aménagement astucieux transforme le sous-sol en un lieu multifonctionnel, idéal pour la cuisine, les réunions informelles, ou même comme espace autonome pour les invités.'),
	(47, 1, 'Chambre Bohème', 25, 1, 2, 65, 'Une chambre bohème évoque un univers éclectique et artistique où les couleurs vibrantes se mélangent harmonieusement. Des coussins aux motifs ethniques ornent un lit orné de draps en lin froissé. Des tapis persans et des tissus suspendus créent une atmosphère chaleureuse, tandis que des étagères en bois accueillent des objets vintage et des plantes luxuriantes. Des lanternes en papier et des guirlandes lumineuses diffusent une lumière douce, accentuant l\'ambiance décontractée. L\'art abstrait et les œuvres artisanales parent les murs, reflétant un esprit libre et bohème.');

-- Listage de la structure de table au_paradis_d_eve_test. image
CREATE TABLE IF NOT EXISTS `image` (
  `id` int NOT NULL AUTO_INCREMENT,
  `espace_id` int DEFAULT NULL,
  `lien_image` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt_image` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `IDX_C53D045FB6885C6C` (`espace_id`),
  CONSTRAINT `FK_C53D045FB6885C6C` FOREIGN KEY (`espace_id`) REFERENCES `espace` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table au_paradis_d_eve_test.image : ~31 rows (environ)
INSERT INTO `image` (`id`, `espace_id`, `lien_image`, `alt_image`) VALUES
	(28, 31, 'f12727f9f4fad96df3e0f81e47fd54eb.jpg', 'Cuisine aux murs couleur beige. Avec un comptoir en chêne'),
	(30, 33, 'b829d3952cc4baf2618b75d98a7489d6.jpg', 'La grande salle à manger peut accueillir confortablement 12 personnes, avec une table spacieuse et des chaises assorties, offrant un espace généreux pour les repas et les rassemblements. Dominant l\'espace, une imposante armoire en bois foncé ajoute une touche majestueuse. Ses détails sculptés et sa finition riche captent l\'attention, créant une atmosphère sophistiquée.'),
	(31, 33, 'a3c41a35d5cd30a4bd435d38f32d676c.jpg', 'La grande salle à manger peut accueillir confortablement 12 personnes, avec une table spacieuse et des chaises assorties, offrant un espace généreux pour les repas et les rassemblements. Dominant l\'espace, une imposante armoire en bois foncé ajoute une touche majestueuse. Ses détails sculptés et sa finition riche captent l\'attention, créant une atmosphère sophistiquée.'),
	(33, 35, '8b5838fc2b11b42ec64443494f8c9760.jpg', 'Bienvenue sur cette terrasse en bois, un oasis de détente entouré de nature. Une grande table à manger pour 10 personnes trône au centre, parfaite pour des repas conviviaux en plein air. Un salon extérieur en osier ajoute une touche de confort, invitant à la détente sous les branches des arbres qui offrent une intimité naturelle.\r\n\r\nAu coin de la terrasse, un four à pizza promet des moments gourmands et conviviaux. L\'espace est soigneusement aménagé pour offrir une expérience en plein air agréable, enveloppée par le calme et la verdure. C\'est l\'endroit idéal pour partager des repas en famille, se détendre avec des amis et profiter du charme de la nature tout en préservant une intimité paisible.'),
	(34, 35, '660dcf0de6526f79e3ac74c77373f59f.jpg', 'Bienvenue sur cette terrasse en bois, un oasis de détente entouré de nature. Une grande table à manger pour 10 personnes trône au centre, parfaite pour des repas conviviaux en plein air. Un salon extérieur en osier ajoute une touche de confort, invitant à la détente sous les branches des arbres qui offrent une intimité naturelle.\r\n\r\nAu coin de la terrasse, un four à pizza promet des moments gourmands et conviviaux. L\'espace est soigneusement aménagé pour offrir une expérience en plein air agréable, enveloppée par le calme et la verdure. C\'est l\'endroit idéal pour partager des repas en famille, se détendre avec des amis et profiter du charme de la nature tout en préservant une intimité paisible.'),
	(35, 35, '75173c0b1c5eab69ad351b59759bf4c3.jpg', 'Bienvenue sur cette terrasse en bois, un oasis de détente entouré de nature. Une grande table à manger pour 10 personnes trône au centre, parfaite pour des repas conviviaux en plein air. Un salon extérieur en osier ajoute une touche de confort, invitant à la détente sous les branches des arbres qui offrent une intimité naturelle.\r\n\r\nAu coin de la terrasse, un four à pizza promet des moments gourmands et conviviaux. L\'espace est soigneusement aménagé pour offrir une expérience en plein air agréable, enveloppée par le calme et la verdure. C\'est l\'endroit idéal pour partager des repas en famille, se détendre avec des amis et profiter du charme de la nature tout en préservant une intimité paisible.'),
	(36, 35, 'bf694fab0934e3bd5575a44938119b4d.jpg', 'Bienvenue sur cette terrasse en bois, un oasis de détente entouré de nature. Une grande table à manger pour 10 personnes trône au centre, parfaite pour des repas conviviaux en plein air. Un salon extérieur en osier ajoute une touche de confort, invitant à la détente sous les branches des arbres qui offrent une intimité naturelle.\r\n\r\nAu coin de la terrasse, un four à pizza promet des moments gourmands et conviviaux. L\'espace est soigneusement aménagé pour offrir une expérience en plein air agréable, enveloppée par le calme et la verdure. C\'est l\'endroit idéal pour partager des repas en famille, se détendre avec des amis et profiter du charme de la nature tout en préservant une intimité paisible.'),
	(37, 36, '6f8bc4871a0be26e637997f60c5b51cf.jpg', 'Bienvenue dans ce jardin paisible, où trône une piscine de 11 mètres de long. Entourée de verdure, elle offre un havre de fraîcheur au cœur de la nature. Les dalles en pierre soigneusement disposées créent une zone élégante et fonctionnelle autour de la piscine, invitant à la détente et aux bains de soleil.\r\nLa piscine, nichée au milieu du jardin sans aucun vis-à-vis, offre une intimité totale. Son design simple et épuré s\'intègre harmonieusement à l\'environnement naturel, créant un espace tranquille pour se rafraîchir par une chaude journée.'),
	(38, 37, 'cc35996f2fff9043497ceda34173abd6.jpg', 'Bienvenue dans ce vaste jardin luxuriant. Une étendue d\'herbe verte s\'étend à perte de vue, créant un cadre apaisant. Au centre, une élégante fontaine murmure doucement, ajoutant une touche rafraîchissante à l\'ensemble. Les parterres de plantes variées ajoutent des touches de couleur et de vie, créant une atmosphère vivante et harmonieuse. Un endroit idéal pour se promener, se détendre et profiter de la nature luxuriante qui entoure chaque coin de ce jardin spacieux.'),
	(39, 37, 'd65f220bdfa1d79d7f2bf224fe5041f2.jpg', 'Bienvenue dans ce vaste jardin luxuriant. Une étendue d\'herbe verte s\'étend à perte de vue, créant un cadre apaisant. Au centre, une élégante fontaine murmure doucement, ajoutant une touche rafraîchissante à l\'ensemble. Les parterres de plantes variées ajoutent des touches de couleur et de vie, créant une atmosphère vivante et harmonieuse. Un endroit idéal pour se promener, se détendre et profiter de la nature luxuriante qui entoure chaque coin de ce jardin spacieux.'),
	(40, 38, '82625657ba646acf7885de7e1c48ac12.jpg', 'Bienvenue sur la terrasse à côté de la piscine, un espace accueillant et détendu. Un abri ouvert ajoute une touche de confort, offrant ombre et protection. Une balancelle invite à la relaxation, tandis qu\'une table avec des chaises offre un endroit idéal pour les repas en plein air. Des chaises longues complètent l\'ensemble, créant un lieu parfait pour se détendre, se rafraîchir et profiter de la vie en extérieur.'),
	(41, 38, 'f5d71b4de733fa1be88a58ae02b07ead.jpg', 'Bienvenue sur la terrasse à côté de la piscine, un espace accueillant et détendu. Un abri ouvert ajoute une touche de confort, offrant ombre et protection. Une balancelle invite à la relaxation, tandis qu\'une table avec des chaises offre un endroit idéal pour les repas en plein air. Des chaises longues complètent l\'ensemble, créant un lieu parfait pour se détendre, se rafraîchir et profiter de la vie en extérieur.'),
	(43, 39, 'f43cdbc1026088c17a5952bbaf5ce240.jpg', 'Bienvenue dans cette chambre à l\'ambiance douce et chaleureuse, baignée de teintes boisées et rosées. Les tons naturels du bois créent une atmosphère apaisante, tandis que les nuances de rose ajoutent une touche délicate à l\'ensemble.\r\nLa chambre offre une vue magnifique sur la citadelle, capturant l\'essence de l\'extérieur et apportant une sensation d\'ouverture. Les meubles en bois soulignent l\'esthétique naturelle, créant un espace cosy et élégant.'),
	(44, 39, '25af12cf7e1df444457998ef020f7af4.jpg', 'Bienvenue dans cette chambre à l\'ambiance douce et chaleureuse, baignée de teintes boisées et rosées. Les tons naturels du bois créent une atmosphère apaisante, tandis que les nuances de rose ajoutent une touche délicate à l\'ensemble.\r\nLa chambre offre une vue magnifique sur la citadelle, capturant l\'essence de l\'extérieur et apportant une sensation d\'ouverture. Les meubles en bois soulignent l\'esthétique naturelle, créant un espace cosy et élégant.'),
	(47, 42, 'ad2e8bf2bce7973b145f5283d223c3cd.jpg', 'Bienvenue dans ce sous-sol aménagé, un espace polyvalent combinant cuisine et point d\'eau. Les murs aux teintes neutres créent une atmosphère accueillante, tandis que les zones définies pour la cuisine et l\'espace lavabo apportent une fonctionnalité pratique.\r\nLa cuisine est équipée pour répondre à vos besoins culinaires, tandis que le point d\'eau assure commodité et confort. Cet aménagement astucieux transforme le sous-sol en un lieu multifonctionnel, idéal pour la cuisine, les réunions informelles, ou même comme espace autonome pour les invités.'),
	(48, 42, '11f461fd050ff152f135e7b9b1b2619b.jpg', 'Bienvenue dans ce sous-sol aménagé, un espace polyvalent combinant cuisine et point d\'eau. Les murs aux teintes neutres créent une atmosphère accueillante, tandis que les zones définies pour la cuisine et l\'espace lavabo apportent une fonctionnalité pratique.\r\nLa cuisine est équipée pour répondre à vos besoins culinaires, tandis que le point d\'eau assure commodité et confort. Cet aménagement astucieux transforme le sous-sol en un lieu multifonctionnel, idéal pour la cuisine, les réunions informelles, ou même comme espace autonome pour les invités.'),
	(54, 39, '93716d5c6c3896225aa30b7ecc13761c.jpg', 'Texte de remplacement indisponible'),
	(55, 40, '7794fa9795586fa1b7b92f08e961b32d.jpg', 'Texte de remplacement indisponible'),
	(56, 41, '67a7de702dd553db8bb08877ed68d4b2.jpg', 'Texte de remplacement indisponible'),
	(57, 41, '618c14393551b5d425c132b67a7d9587.jpg', 'Texte de remplacement indisponible'),
	(58, 41, '0b981b03fb4653b2533e913c2f06ab8d.jpg', 'Texte de remplacement indisponible'),
	(59, 41, '8ccbbaee7572d934572da97c3c282105.jpg', 'Texte de remplacement indisponible'),
	(60, 33, 'dc64ff4c95fd40abf76b41f06fdc1215.jpg', 'Texte de remplacement indisponible'),
	(62, 31, '1a46c3ac9221071629a62fa972dfabca.jpg', 'Texte de remplacement indisponible'),
	(63, 39, '1b5345ad745f95cfe3859a8c60700653.jpg', 'Texte de remplacement indisponible'),
	(64, 40, '85124e8c0607ded84febeed6a2347ffe.jpg', 'Texte de remplacement indisponible'),
	(65, 32, 'bcfb052c2690fd19e56333d2c045e4f9.jpg', 'Texte de remplacement indisponible'),
	(66, 47, '54dd839a46dca3516b93b02a61e7ee64.jpg', 'Texte de remplacement indisponible'),
	(67, 37, '0af1b06d0d4687984df5d90739878431.jpg', 'Texte de remplacement indisponible'),
	(68, 37, 'd114879c0c7fa510f9fc5dcdd73ab12c.jpg', 'Texte de remplacement indisponible'),
	(71, 34, 'bdbc07b293d2235a1dc2a0276a62350d.jpg', 'Texte de remplacement indisponible');

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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table au_paradis_d_eve_test.option : ~4 rows (environ)
INSERT INTO `option` (`id`, `nom`, `description`, `tarif`) VALUES
	(1, 'Dîner', 'test description', 11),
	(2, 'Brunch', 'test description', 10),
	(3, 'Option 3', 'test description', 6),
	(10, 'Test', 'Test', 40);

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
) ENGINE=InnoDB AUTO_INCREMENT=139 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table au_paradis_d_eve_test.reservation : ~13 rows (environ)
INSERT INTO `reservation` (`id`, `espace_id`, `user_id`, `prenom`, `nom`, `telephone`, `nb_personnes`, `date_debut`, `date_fin`, `prix_total`, `note`, `avis`, `email`, `adresse_facturation`, `facture`, `date_reservation`, `statut`) VALUES
	(108, 39, NULL, 'Hailey', 'Smith', 613325702, 2, '2024-02-12 15:00:00', '2024-02-14 11:00:00', 71, NULL, NULL, 'stewie@griffin', '31 Spooner Street 00093 Quahog Rhode Island', 'app_pdf_generator', '2024-02-11 22:13:39', 'CONFIRMEE'),
	(110, 40, 27, 'Davina', 'Houquet', 618208514, 2, '2025-02-12 15:00:00', '2025-02-15 11:00:00', 100, NULL, NULL, 'stewie@griffin', '31 Spooner Street 00093 Quahog Rhode Island', 'app_pdf_generator', '2024-02-12 00:07:49', 'A REMBOURSER'),
	(112, 39, 27, 'Davina', 'Houquet', 618208514, 2, '2023-05-12 15:00:00', '2023-05-14 11:00:00', 60, 5, '(NULL)', '', '31 Spooner Street 00093 Quahog Rhode Island', 'app_pdf_generator', '2024-02-12 00:10:23', 'CONFIRMEE'),
	(113, 40, 27, 'Hailey', 'Smith', 613325702, 2, '2023-02-19 15:00:00', '2023-02-21 11:00:00', 71, 5, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'hailey@griffin', '31 Spooner Street 00093 Quahog Rhode Island', 'app_pdf_generator', '2024-02-13 14:03:22', 'A REMBOURSER'),
	(114, 39, 27, 'eqg', 'Houquet', 618208514, 2, '2024-02-26 15:00:00', '2024-02-28 11:00:00', 60, NULL, NULL, 'stewie@griffin', '31 Spooner Street 00093 Quahog Rhode Island', 'app_pdf_generator', '2024-02-13 14:07:45', 'CONFIRMEE'),
	(116, 39, 27, 'Hailey', 'Smith', 444, 2, '2023-07-01 15:00:00', '2023-07-03 11:00:00', 131, 4, 'Test', 'hailey@griffin', '31 Spooner Street 00093 Quahog Rhode Island', 'app_pdf_generator', '2024-02-14 09:11:06', 'CONFIRMEE'),
	(118, 39, 27, 'Davina', 'Houquet', 618208514, 2, '2023-02-15 15:00:00', '2023-02-17 11:00:00', 147, NULL, NULL, 'stewie@griffin', '31 Spooner Street 00093 Quahog Rhode Island', 'app_pdf_generator', '2024-02-14 09:26:13', 'CONFIRMEE'),
	(129, 40, 27, 'Davina', 'Houquet', 618208514, 2, '2024-06-15 15:00:00', '2024-06-17 11:00:00', 121, NULL, NULL, 'haileysmith@americandad.us', '10 Rue Lucie Berger 67200 Strasbourg qrgq<g', 'app_pdf_generator', '2024-02-14 16:14:04', 'A REMBOURSER'),
	(130, 47, 27, 'Hailey', 'Smith', 444, 2, '2024-07-16 15:00:00', '2024-07-18 11:00:00', 151, NULL, NULL, 'haileysmith@americandad.us', '10 Rue Lucie Berger 67200 Strasbourg qrgqg', 'app_pdf_generator', '2024-02-15 16:36:07', 'A REMBOURSER'),
	(131, 39, NULL, 'Davina', 'Houquet', 618208514, 2, '2029-02-17 15:00:00', '2029-02-19 11:00:00', 170, NULL, NULL, 'admin@admin.admin', 'wytxy 6565 ysrsy rysu', 'app_pdf_generator', '2024-02-16 07:06:54', 'CONFIRMEE'),
	(132, 39, NULL, 'Davina', 'Houquet', 618208514, 2, '2027-02-17 15:00:00', '2027-02-19 11:00:00', 131, NULL, NULL, 'admin@admin.admin', 'wytxy 6565 ysrsy rysu', 'app_pdf_generator', '2024-02-16 07:07:41', 'CONFIRMEE'),
	(137, 41, 27, 'Davina', 'Houquet', 618208514, 2, '2024-02-17 15:00:00', '2024-02-19 11:00:00', 121, NULL, NULL, 'haileysmith@americandad.us', '10 Rue Lucie Berger 67200 Strasbourg qrgq<g', 'app_pdf_generator', '2024-02-16 13:25:57', 'A REMBOURSER'),
	(138, 41, 27, 'Davina', 'Houquet', 618208514, 2, '2024-02-26 15:00:00', '2024-02-28 11:00:00', 167, NULL, NULL, 'haileysmith@americandad.us', '10 Rue Lucie Berger 67200 Strasbourg qrgq<g', 'app_pdf_generator', '2024-02-16 17:53:56', 'A REMBOURSER');

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

-- Listage des données de la table au_paradis_d_eve_test.reservation_option : ~17 rows (environ)
INSERT INTO `reservation_option` (`reservation_id`, `option_id`) VALUES
	(108, 1),
	(113, 1),
	(113, 2),
	(116, 1),
	(118, 1),
	(118, 2),
	(118, 3),
	(129, 1),
	(129, 2),
	(130, 1),
	(130, 2),
	(132, 1),
	(137, 1),
	(137, 2),
	(138, 1),
	(138, 2),
	(138, 3),
	(138, 10);

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
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table au_paradis_d_eve_test.user : ~14 rows (environ)
INSERT INTO `user` (`id`, `email`, `roles`, `password`, `pseudo`, `adresse`, `cp`, `ville`, `pays`, `is_verified`) VALUES
	(2, 'user@user.user', '[]', '123', 'user', NULL, NULL, NULL, NULL, 0),
	(13, 'ricardo@psycho.fr', '["ROLE_USER"]', '$2y$13$Zmk5Vjtr59VSkwad1g/5m./fihnoCNT4Jh6AkjxLPpi4fU1j8dRJ2', 'ricardo', NULL, NULL, NULL, NULL, 0),
	(15, 'daz@daz.fr', '[]', '$2y$13$.nfg5WmdTrSi86YKzmZdGeJu.zXNARt/IqFQN3z33jMbU1FYp.39y', 'daz', NULL, NULL, NULL, NULL, 0),
	(16, 'zaz@zaz.fr', '[]', '$2y$13$UxvwX0Cnvo9N8G0qwoeBnebSRWu3e5nJcmsJZ9G.XHdHRVG6/RjQu', 'zaz', NULL, NULL, NULL, NULL, 0),
	(17, 'ad@ad.fr', '[]', '$2y$13$00HSHSdFbj22L6TfbaXov.s3yVWRJ.Uw12C6kQeRbI9Wt9lDsHFd.', 'admin', NULL, NULL, NULL, NULL, 0),
	(18, 'zae@fre.fr', '[]', '$2y$13$mju64FvmPvv.AfXdURalA.leHgbE03fgsx5Ix5PJAsjBDkypGD952', 'zae', NULL, NULL, NULL, NULL, 0),
	(19, 'tet@tet.fr', '[]', '$2y$13$cwHzd8t6enj3kH.pQJn9j.Eb4Z836.kD8y3OAH3hxR5yxEegZ/xk6', 'user', NULL, NULL, NULL, NULL, 0),
	(22, 'admin@admin.fr', '["ROLE_ADMIN"]', '$2y$13$h4WI9R7SS74SZNqdT..Obu/BSEDzT6WCvt7kYdBcO4uAAc0zcat9S', 'Admin', NULL, NULL, NULL, NULL, 0),
	(23, 'test@user.fr', '[]', '$2y$13$uj7wveDZhIvsKGHUAXO44OEKnmmOXjBWI7ewI0kE7oeLFa55stcWu', 'Test User', NULL, NULL, NULL, NULL, 0),
	(24, 'test2@user.fr', '[]', '$2y$13$U2J84UfPNTsZetqbIzVShufOzMpoRk5.aL45ey/T3nJgDrmqkxdoi', 'Test User2', NULL, NULL, NULL, NULL, 0),
	(25, 'josephine@gmail.com', '[]', '123', 'Joséphina', '31 Spooner Street', '00093', 'Quahog', 'Rhode Island', 0),
	(26, 'gregoire@greg.fr', '[]', '$2y$13$pmynHjS2sUSSV9zZww/nPOmkuaIGXYbtJCkUTEMWEr6R.mVtLF2UG', 'TestavecConfirmation', NULL, NULL, NULL, NULL, 0),
	(27, 'haileysmith@americandad.us', '["ROLE_ADMIN"]', '$2y$13$3GVXzufNnOKvxr4TLNhQTOlptinwVAOHGxlwb6OIPoe6/hRq3MQ1S', 'Hailey2', '10 Rue Lucie Berger', '67200', 'Strasbourg', 'qrgq<g', 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
