-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
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


-- Dumping database structure for liberica_heritage_db
CREATE DATABASE IF NOT EXISTS `liberica_heritage_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
USE `liberica_heritage_db`;

-- Dumping structure for table liberica_heritage_db.articles
CREATE TABLE IF NOT EXISTS `articles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(160) NOT NULL,
  `slug` varchar(190) NOT NULL,
  `category` varchar(40) NOT NULL,
  `summary` varchar(400) NOT NULL,
  `body` longtext NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `articles_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table liberica_heritage_db.articles: ~5 rows (approximately)
INSERT INTO `articles` (`id`, `title`, `slug`, `category`, `summary`, `body`, `image_path`, `is_published`, `sort_order`, `created_at`, `updated_at`) VALUES
	(1, 'The coffee legacy of Lipa', 'the-coffee-legacy-of-lipa', 'heritage', 'Lipa grew into a major coffee-trading town in the nineteenth century.', 'Lipa’s coffee trade reached extraordinary prominence in the nineteenth century. The City Government of Lipa describes the town’s prosperity and its elevation to Villa de Lipa on October 21, 1887. Plant disease struck its coffee plantations soon afterward, changing the region’s economic story. This history helps explain why coffee remains so deeply tied to Lipa’s identity.', NULL, 1, 1, '2026-11-14 15:03:10', '2026-11-14 15:03:10'),
	(2, 'What makes Liberica distinctive?', 'what-makes-liberica-distinctive', 'botany', 'Large leaves, large beans, and an expressive character make Liberica memorable.', 'Coffea liberica is a coffee species native to parts of West and Central Africa and cultivated in the Philippines and other tropical countries. Compared with more familiar commercial coffees, Liberica is known for its relatively large beans, leaves, and tree stature. In the Philippines, it is strongly associated with Kapeng Barako. Exact flavors depend on cultivar, farm, processing, roast, and brewing.', NULL, 1, 2, '2026-11-14 15:03:10', '2026-11-14 15:03:10'),
	(3, 'From coffee tree to cup', 'from-coffee-tree-to-cup', 'cultivation', 'Explore the stages of growing, harvesting, processing, and roasting.', 'Coffee cultivation begins with healthy planting material and careful site management. Ripe coffee cherries are harvested, sorted, processed, dried, hulled, roasted, and finally brewed. Each stage contributes to quality and character. Responsible farming and post-harvest practices can support local coffee communities.', NULL, 1, 3, '2026-11-14 15:03:10', '2026-11-14 15:03:10'),
	(4, 'The Barako coffee ritual', 'the-barako-coffee-ritual', 'culture', 'More than a beverage, coffee is part of social connection.', 'Kapeng Barako is often remembered as a strong and aromatic coffee connected to Batangueño hospitality and daily life. Recipes and serving styles vary among households and cafés. This website celebrates the experience while encouraging visitors to explore real stories from local people.', NULL, 1, 4, '2026-11-14 15:03:10', '2026-11-14 15:03:10'),
	(5, 'Why this heritage matters', 'why-this-heritage-matters', 'preservation', 'Keep the stories, knowledge, and appreciation alive.', 'Promoting Kapeng Barako means more than describing a bold cup. It also means recognizing farming knowledge, supporting accurate public information, documenting local narratives with permission, and encouraging informed appreciation for Liberica and the communities connected to it.', NULL, 1, 5, '2026-11-14 15:03:10', '2026-11-14 15:03:10');

-- Dumping structure for table liberica_heritage_db.gallery_items
CREATE TABLE IF NOT EXISTS `gallery_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(160) NOT NULL,
  `caption` varchar(400) DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `image_credit` varchar(190) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table liberica_heritage_db.gallery_items: ~0 rows (approximately)

-- Dumping structure for table liberica_heritage_db.messages
CREATE TABLE IF NOT EXISTS `messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `email` varchar(190) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'new',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `messages_status_created_at_index` (`status`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table liberica_heritage_db.messages: ~0 rows (approximately)

-- Dumping structure for table liberica_heritage_db.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table liberica_heritage_db.migrations: ~1 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2026_11_14_000001_create_heritage_tables', 1);

-- Dumping structure for table liberica_heritage_db.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table liberica_heritage_db.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table liberica_heritage_db.references
CREATE TABLE IF NOT EXISTS `references` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(190) NOT NULL,
  `publisher` varchar(120) NOT NULL,
  `url` varchar(2048) NOT NULL,
  `notes` text DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table liberica_heritage_db.references: ~4 rows (approximately)
INSERT INTO `references` (`id`, `title`, `publisher`, `url`, `notes`, `is_published`, `created_at`, `updated_at`) VALUES
	(1, 'Local History — Lipa: Then and Now', 'City Government of Lipa', 'https://lipa.gov.ph/history/', 'Local coffee boom, elevation to Villa de Lipa, and later decline.', 1, '2026-11-14 15:03:10', '2026-11-14 15:03:10'),
	(2, 'Coffee Technoguide', 'Department of Agriculture — Agricultural Training Institute', 'https://ati2.da.gov.ph/ati-4b/content/sites/default/files/2022-11/Coffee%20Technoguide%20w%20page%20numbers.pdf', 'Government coffee guidance describing Liberica and Kapeng Barako.', 1, '2026-11-14 15:03:10', '2026-11-14 15:03:10'),
	(3, 'Liberica species profile', 'SICC', 'https://www.sicc.coffee/species/liberica/', 'Species characteristics and broader botanical context.', 1, '2026-11-14 15:03:10', '2026-11-14 15:03:10'),
	(4, 'Batangas to standardize Kapeng Barako production', 'Philippine News Agency', 'https://www.pna.gov.ph/index.php/articles/1234070', '2024 report about local quality and heritage initiatives.', 1, '2026-11-14 15:03:10', '2026-11-14 15:03:10');

-- Dumping structure for table liberica_heritage_db.settings
CREATE TABLE IF NOT EXISTS `settings` (
  `key` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table liberica_heritage_db.settings: ~6 rows (approximately)
INSERT INTO `settings` (`key`, `value`) VALUES
	('contact_email', ''),
	('hero_accent', 'Rooted in Batangas.'),
	('hero_description', 'Meet Kapeng Barako, the distinctive Liberica coffee woven into the stories, landscapes, and everyday rituals of Batangas.'),
	('hero_kicker', 'A HERITAGE WORTH KEEPING'),
	('hero_title', 'Bold by nature.'),
	('site_name', 'LIBÉRICA');

-- Dumping structure for table liberica_heritage_db.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table liberica_heritage_db.users: ~1 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `is_admin`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'LIBÉRICA Administrator', 'bellaleus16@gmail.com', NULL, '$2y$12$7kVEkDiudTzaACOypoojkOdg5KgrLxuiduffsY8nSQSjEIPNlAn.K', 1, NULL, '2026-11-14 15:03:11', '2026-11-14 16:27:01');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
