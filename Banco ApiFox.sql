-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           8.4.3 - MySQL Community Server - GPL
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para apifox
CREATE DATABASE IF NOT EXISTS `apifox` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `apifox`;

-- Copiando estrutura para tabela apifox.cart
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status_id` int DEFAULT '1',
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela apifox.cart: ~14 rows (aproximadamente)
INSERT INTO `cart` (`id`, `created_at`, `status_id`, `updated_at`) VALUES
	(1, '2025-03-24 22:51:58', 3, NULL),
	(2, '2025-03-24 22:51:58', 3, NULL),
	(3, '2025-03-24 22:51:58', 3, '2025-04-09 12:41:56'),
	(4, '2025-03-25 16:08:20', 3, NULL),
	(5, '2025-03-25 17:20:20', 3, '2025-04-09 12:42:15'),
	(6, '2025-03-25 17:32:38', 3, NULL),
	(7, '2025-03-25 17:46:19', 3, NULL),
	(8, '2025-03-25 17:46:28', 3, NULL),
	(9, '2025-04-07 21:42:13', 3, '2025-04-09 12:44:47'),
	(10, '2025-04-09 15:44:59', 3, '2025-04-09 13:01:30'),
	(11, '2025-04-09 16:03:06', 3, '2025-04-09 13:03:40'),
	(12, '2025-04-09 16:07:40', 3, '2025-04-09 13:08:14'),
	(13, '2025-04-09 16:14:49', 3, '2025-04-09 13:14:57'),
	(14, '2025-04-09 16:15:49', 3, '2025-04-09 13:16:08');

-- Copiando estrutura para tabela apifox.cart_items
CREATE TABLE IF NOT EXISTS `cart_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `cart_id` (`cart_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela apifox.cart_items: ~25 rows (aproximadamente)
INSERT INTO `cart_items` (`id`, `cart_id`, `product_id`, `quantity`, `price`, `created_at`) VALUES
	(2, 1, 2, 1, 20.00, '2025-03-24 22:53:48'),
	(3, 2, 3, 3, 30.00, '2025-03-24 22:53:48'),
	(6, 1, 3, 3, 30.00, '2025-03-25 15:41:47'),
	(15, 4, 1, 3, 30.00, '2025-04-08 02:16:12'),
	(18, 4, 2, 3, 30.00, '2025-04-08 02:25:10'),
	(19, 4, 2, 3, 30.00, '2025-04-08 02:37:46'),
	(20, 3, 1, 100, 10.00, '2025-04-09 15:41:54'),
	(21, 5, 2, 2, 20.00, '2025-04-09 15:42:13'),
	(22, 9, 1, 100, 10.00, '2025-04-09 15:44:45'),
	(23, 9, 3, 2, 30.00, '2025-04-09 15:44:46'),
	(24, 10, 2, 1, 20.00, '2025-04-09 15:44:59'),
	(25, 11, 1, 1, 10.00, '2025-04-09 16:03:06'),
	(26, 11, 2, 2, 20.00, '2025-04-09 16:03:06'),
	(27, 11, 3, 3, 30.00, '2025-04-09 16:03:06'),
	(28, 11, 1, 3, 10.00, '2025-04-09 16:03:27'),
	(29, 12, 1, 1, 10.00, '2025-04-09 16:07:40'),
	(30, 12, 2, 2, 20.00, '2025-04-09 16:07:40'),
	(31, 12, 3, 3, 30.00, '2025-04-09 16:07:40'),
	(32, 12, 3, 5, 30.00, '2025-04-09 16:07:53'),
	(33, 13, 1, 1, 10.00, '2025-04-09 16:14:49'),
	(34, 13, 2, 1, 20.00, '2025-04-09 16:14:50'),
	(35, 13, 3, 1, 30.00, '2025-04-09 16:14:50'),
	(36, 14, 1, 1, 10.00, '2025-04-09 16:15:49'),
	(37, 14, 2, 2, 20.00, '2025-04-09 16:15:49'),
	(38, 14, 3, 3, 30.00, '2025-04-09 16:15:49'),
	(39, 14, 2, 100, 20.00, '2025-04-09 16:16:02');

-- Copiando estrutura para tabela apifox.cart_statuses
CREATE TABLE IF NOT EXISTS `cart_statuses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela apifox.cart_statuses: ~4 rows (aproximadamente)
INSERT INTO `cart_statuses` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'PENDING', 'Carrinho recém-criado, aguardando itens ou finalização.', '2025-04-09 12:37:26', '2025-04-09 12:37:26'),
	(2, 'PROCESSING', 'Carrinho em processo de finalização (pagamento, etc.).', '2025-04-09 12:37:26', '2025-04-09 12:37:26'),
	(3, 'COMPLETED', 'Compra finalizada com sucesso.', '2025-04-09 12:37:26', '2025-04-09 12:37:26'),
	(4, 'CANCELLED', 'Compra cancelada.', '2025-04-09 12:37:26', '2025-04-09 12:37:26');

-- Copiando estrutura para tabela apifox.phinxlog
CREATE TABLE IF NOT EXISTS `phinxlog` (
  `version` bigint NOT NULL,
  `migration_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `breakpoint` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela apifox.phinxlog: ~0 rows (aproximadamente)
INSERT INTO `phinxlog` (`version`, `migration_name`, `start_time`, `end_time`, `breakpoint`) VALUES
	(20250323224035, 'CreateUsersTable', '2025-03-24 22:49:35', '2025-03-24 22:49:35', 0);

-- Copiando estrutura para tabela apifox.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela apifox.products: ~3 rows (aproximadamente)
INSERT INTO `products` (`id`, `name`, `description`, `price`, `created_at`) VALUES
	(1, 'Product 1', 'Description for product 1', 10.00, '2025-03-24 22:52:28'),
	(2, 'Product 2', 'Description for product 2', 20.00, '2025-03-24 22:52:28'),
	(3, 'Product 3', 'Description for product 3', 30.00, '2025-03-24 22:52:28');

-- Copiando estrutura para tabela apifox.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela apifox.users: ~0 rows (aproximadamente)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
