-- fleet_management.sql
-- Database dump for Fleet Management System

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS `fleet_management`;
USE `fleet_management`;

-- Disable foreign key checks during import
SET FOREIGN_KEY_CHECKS=0;

-- Table structure for table `users`
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `cities`
DROP TABLE IF EXISTS `cities`;
CREATE TABLE `cities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `buses`
DROP TABLE IF EXISTS `buses`;
CREATE TABLE `buses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `seats`
DROP TABLE IF EXISTS `seats`;
CREATE TABLE `seats` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `bus_id` bigint(20) unsigned NOT NULL,
  `seat_number` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seats_bus_id_seat_number_unique` (`bus_id`,`seat_number`),
  CONSTRAINT `seats_bus_id_foreign` FOREIGN KEY (`bus_id`) REFERENCES `buses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `trips`
DROP TABLE IF EXISTS `trips`;
CREATE TABLE `trips` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `bus_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trips_bus_id_foreign` (`bus_id`),
  CONSTRAINT `trips_bus_id_foreign` FOREIGN KEY (`bus_id`) REFERENCES `buses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `trip_stations`
DROP TABLE IF EXISTS `trip_stations`;
CREATE TABLE `trip_stations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `trip_id` bigint(20) unsigned NOT NULL,
  `city_id` bigint(20) unsigned NOT NULL,
  `order` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `trip_stations_trip_id_city_id_unique` (`trip_id`,`city_id`),
  UNIQUE KEY `trip_stations_trip_id_order_unique` (`trip_id`,`order`),
  KEY `trip_stations_city_id_foreign` (`city_id`),
  CONSTRAINT `trip_stations_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trip_stations_trip_id_foreign` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `bookings`
DROP TABLE IF EXISTS `bookings`;
CREATE TABLE `bookings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `trip_id` bigint(20) unsigned NOT NULL,
  `seat_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `from_station_id` bigint(20) unsigned NOT NULL,
  `to_station_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bookings_trip_id_foreign` (`trip_id`),
  KEY `bookings_seat_id_foreign` (`seat_id`),
  KEY `bookings_user_id_foreign` (`user_id`),
  KEY `bookings_from_station_id_foreign` (`from_station_id`),
  KEY `bookings_to_station_id_foreign` (`to_station_id`),
  CONSTRAINT `bookings_from_station_id_foreign` FOREIGN KEY (`from_station_id`) REFERENCES `trip_stations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_seat_id_foreign` FOREIGN KEY (`seat_id`) REFERENCES `seats` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_to_station_id_foreign` FOREIGN KEY (`to_station_id`) REFERENCES `trip_stations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_trip_id_foreign` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dummy data for users
INSERT INTO `users` VALUES 
(1,'Admin User','admin@example.com','2025-03-16 12:00:00','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','QIHQnKTuVm','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(2,'User 1','user1@example.com','2025-03-16 12:00:00','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','H2m0B0mxAV','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(3,'User 2','user2@example.com','2025-03-16 12:00:00','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','cDpwGw1Qv2','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(4,'User 3','user3@example.com','2025-03-16 12:00:00','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','t5TwrOLXc6','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(5,'User 4','user4@example.com','2025-03-16 12:00:00','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','GcqvX02aUm','2025-03-16 12:00:00','2025-03-16 12:00:00');

-- Dummy data for cities
INSERT INTO `cities` VALUES 
(1,'Cairo','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(2,'Giza','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(3,'AlFayyum','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(4,'AlMinya','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(5,'Asyut','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(6,'Luxor','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(7,'Aswan','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(8,'Alexandria','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(9,'Port Said','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(10,'Suez','2025-03-16 12:00:00','2025-03-16 12:00:00');

-- Dummy data for buses
INSERT INTO `buses` VALUES 
(1,'Bus 1','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(2,'Bus 2','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(3,'Bus 3','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(4,'Bus 4','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(5,'Bus 5','2025-03-16 12:00:00','2025-03-16 12:00:00');

-- Dummy data for seats (12 seats per bus)
INSERT INTO `seats` VALUES 
(1,1,'S1','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(2,1,'S2','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(3,1,'S3','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(4,1,'S4','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(5,1,'S5','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(6,1,'S6','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(7,1,'S7','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(8,1,'S8','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(9,1,'S9','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(10,1,'S10','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(11,1,'S11','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(12,1,'S12','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(13,2,'S1','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(14,2,'S2','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(15,2,'S3','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(16,2,'S4','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(17,2,'S5','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(18,2,'S6','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(19,2,'S7','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(20,2,'S8','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(21,2,'S9','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(22,2,'S10','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(23,2,'S11','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(24,2,'S12','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(25,3,'S1','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(26,3,'S2','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(27,3,'S3','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(28,3,'S4','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(29,3,'S5','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(30,3,'S6','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(31,3,'S7','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(32,3,'S8','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(33,3,'S9','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(34,3,'S10','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(35,3,'S11','2025-03-16 12:00:00','2025-03-16 12:00:00'),
(36,3,'S12','2025-03-16 12:00:00','2025-03-16 12:00:00');

-- Dummy data for trips
INSERT INTO `trips` VALUES 
(1,'Cairo to Aswan Express',1,'2025-03-16 12:00:00','2025-03-16 12:00:00'),
(2,'Alexandria to Port Said',2,'2025-03-16 12:00:00','2025-03-16 12:00:00'),
(3,'Asyut to Cairo',3,'2025-03-16 12:00:00','2025-03-16 12:00:00');

-- Dummy data for trip stations
-- Trip 1: Cairo to Aswan Express
INSERT INTO `trip_stations` VALUES 
(1,1,1,1,'2025-03-16 12:00:00','2025-03-16 12:00:00'), -- Cairo
(2,1,2,2,'2025-03-16 12:00:00','2025-03-16 12:00:00'), -- Giza
(3,1,3,3,'2025-03-16 12:00:00','2025-03-16 12:00:00'), -- AlFayyum
(4,1,4,4,'2025-03-16 12:00:00','2025-03-16 12:00:00'), -- AlMinya
(5,1,5,5,'2025-03-16 12:00:00','2025-03-16 12:00:00'), -- Asyut
(6,1,6,6,'2025-03-16 12:00:00','2025-03-16 12:00:00'), -- Luxor
(7,1,7,7,'2025-03-16 12:00:00','2025-03-16 12:00:00'); -- Aswan

-- Trip 2: Alexandria to Port Said
INSERT INTO `trip_stations` VALUES 
(8,2,8,1,'2025-03-16 12:00:00','2025-03-16 12:00:00'),  -- Alexandria
(9,2,1,2,'2025-03-16 12:00:00','2025-03-16 12:00:00'),  -- Cairo
(10,2,9,3,'2025-03-16 12:00:00','2025-03-16 12:00:00'); -- Port Said

-- Trip 3: Asyut to Cairo
INSERT INTO `trip_stations` VALUES 
(11,3,5,1,'2025-03-16 12:00:00','2025-03-16 12:00:00'), -- Asyut
(12,3,4,2,'2025-03-16 12:00:00','2025-03-16 12:00:00'), -- AlMinya
(13,3,3,3,'2025-03-16 12:00:00','2025-03-16 12:00:00'), -- AlFayyum
(14,3,2,4,'2025-03-16 12:00:00','2025-03-16 12:00:00'), -- Giza
(15,3,1,5,'2025-03-16 12:00:00','2025-03-16 12:00:00'); -- Cairo

-- Sample bookings
INSERT INTO `bookings` VALUES 
(1,1,1,2,1,5,'2025-03-16 12:00:00','2025-03-16 12:00:00'), -- User 1 books Seat 1 from Cairo to Asyut
(2,1,2,3,2,7,'2025-03-16 12:00:00','2025-03-16 12:00:00'), -- User 2 books Seat 2 from Giza to Aswan
(3,1,3,4,1,3,'2025-03-16 12:00:00','2025-03-16 12:00:00'), -- User 3 books Seat 3 from Cairo to AlFayyum
(4,2,13,2,8,10,'2025-03-16 12:00:00','2025-03-16 12:00:00'), -- User 1 books Seat 1 (Bus 2) from Alexandria to Port Said
(5,3,25,5,11,15,'2025-03-16 12:00:00','2025-03-16 12:00:00'); -- User 4 books Seat 1 (Bus 3) from Asyut to Cairo

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS=1;