CREATE DATABASE `rest-watch` COLLATE 'utf8mb4_unicode_ci';

CREATE TABLE `watches` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `date_removed` datetime NULL,
  `title` varchar(256) COLLATE 'utf8mb4_unicode_ci' NOT NULL,
  `price` int NOT NULL,
  `description` text COLLATE 'utf8mb4_unicode_ci' NOT NULL
) COLLATE 'utf8mb4_unicode_ci';

CREATE TABLE `fountains` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `image_base64` longtext COLLATE 'utf8mb4_unicode_ci' NULL,
  `color` varchar(32) COLLATE 'utf8mb4_unicode_ci' NULL,
  `height` varchar(32) COLLATE 'utf8mb4_unicode_ci' NULL,
  `watch_id` int(11) NOT NULL,
  FOREIGN KEY (`watch_id`) REFERENCES `watches` (`id`) ON DELETE RESTRICT
) COLLATE 'utf8mb4_unicode_ci';
