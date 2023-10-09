-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 09, 2023 at 04:20 AM
-- Server version: 8.0.31
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `refer`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(5, '2014_10_12_000000_create_users_table', 1),
(6, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(7, '2019_08_19_000000_create_failed_jobs_table', 1),
(8, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(9, '2023_09_30_111853_create_roles_table', 2),
(10, '2023_09_30_112559_add_role_id_in_users_table', 3),
(11, '2023_10_01_065317_user_referce_mapping_table', 4),
(12, '2023_10_01_070041_create_user_wallet_history', 5),
(13, '2023_10_01_154203_create_setting_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'admin', NULL, NULL),
(2, 'user', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

DROP TABLE IF EXISTS `setting`;
CREATE TABLE IF NOT EXISTS `setting` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'referralCode', '191', '2023-10-01 12:44:12', '2023-10-01 12:54:51');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` bigint UNSIGNED DEFAULT NULL,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `refer_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `first_name`, `last_name`, `email`, `mobile_no`, `email_verified_at`, `password`, `refer_code`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'mmm', 'mmm', 'admin@gmail.com', '7984843273', NULL, '$2y$10$3IyOzzzJkboWnkPljl0J8ej0NBhriegoMhQoW/G1/cs.Jwvo4q1.a', NULL, NULL, '2023-09-27 08:18:04', '2023-09-27 08:18:04'),
(33, NULL, 'm', 'm', 'm@gmail.com', '9898988976', NULL, '$2y$10$7nGbb0QV9cTbpXlN.uchaeUASHaZhQY18otCXSx8Xh5wtaxGlAxn2', '33m4562', NULL, '2023-10-06 03:58:21', '2023-10-06 03:58:21'),
(32, NULL, 'l', 'l', 'l@gmail.com', '3834834343', NULL, '$2y$10$GmWKzUacn1Ty0FvALeUxQeqAzYnHsIK3fHvRLWutzQqqqY7cxorVm', '32l1546', NULL, '2023-10-06 03:57:18', '2023-10-06 03:57:18'),
(31, NULL, 'q5', 'q5', 'Q5@gmail.com', '8989989891', NULL, '$2y$10$yCuVzZ8nLVPfwStDPRi59OPpOzkTJSpsW3DX6QSzojA1dX5w6WRba', '31q59559', NULL, '2023-10-06 03:56:34', '2023-10-06 03:56:34'),
(28, NULL, 'q2', 'q2', 'q2@gmail.com', '9454858545', NULL, '$2y$10$XhwTor/.r0PfRjJjgnb3y.u7w5v2xDA8qKVzOzSNc7Xa5/tKmMxme', '28q25981', NULL, '2023-10-06 03:27:51', '2023-10-06 03:27:51'),
(21, 2, 'mukesh', 'mahanto', 'mukesh@gmail.com', '9898986554', NULL, '$2a$12$OsVSPHm4vODvLq64RgSiOOglFXGMdbeVMLgNBmAJpO.nyAzsGVyya', 'MUKES02', NULL, NULL, NULL),
(22, NULL, 'jj', 'jj', 'jj@gmail.com', '6575675664', NULL, '$2y$10$0Tyj9.5SikREJvzOC8h7aOjz8L1T7IE5VMk6KkFMxwaT6l4X5gvr6', '22jj0390', NULL, '2023-10-06 01:37:26', '2023-10-06 01:37:26'),
(23, NULL, 'kp', 'kp', 'kp@gmail.com', '9845343223', NULL, '$2y$10$fUJL7WaCuhfWsSgnbZ1FdufJ61TQEgX45cuy9lb.rSyEDJHpQTf3m', '23kp8809', NULL, '2023-10-06 03:15:15', '2023-10-06 03:15:15'),
(24, NULL, 'pp', 'pp', 'pp@gmail.com', '9897455433', NULL, '$2y$10$0PWcDyRm03DKJhZripVZxehP6Zu4fTfs6HU.2TWGFxqCXJYBulcOm', '24pp2652', NULL, '2023-10-06 03:17:56', '2023-10-06 03:17:56'),
(25, NULL, 'lk', 'lk', 'lk@gmail.com', '9454535554', NULL, '$2y$10$67c4gBVB1JVvUEN3b2qAgudTNY8IAxpLoC5OCAOzT3ZvxKztv5/Gm', '25lk2775', NULL, '2023-10-06 03:21:36', '2023-10-06 03:21:36'),
(26, NULL, 'q', 'q', 'q@gmail.com', '9988797987', NULL, '$2y$10$9rcogbzDrHJk2WGZNVLWre9TF8dVPlPXIO7n8lsHqOvnifwdrT0i.', '26q9196', NULL, '2023-10-06 03:25:22', '2023-10-06 03:25:22'),
(27, NULL, 'q1', 'q1', 'q1@gmail.com', '9887877878', NULL, '$2y$10$RQ35eUDqKNNSr3yW.B46ieCC5m8RJtUgYzxt4c31vrczZTzwpYMW6', '27q11909', NULL, '2023-10-06 03:26:53', '2023-10-06 03:26:53'),
(29, NULL, 'q3', 'q3', 'q3@gmail.com', '9348348434', NULL, '$2y$10$tTUeMIcDdye9frAkGuyU3ejH4/ARv2bvXStFrHTFNlLY4sVgxzTda', '29q33639', NULL, '2023-10-06 03:28:38', '2023-10-06 03:28:38'),
(30, NULL, 'q4', 'q4', 'q4@gmail.com', '8989989898', NULL, '$2y$10$0PiPO7XfMF3k34NW73toIuq.Lw/qf/ZY85YATrPPY7Dc8vn5aUQfC', '30q40954', NULL, '2023-10-06 03:55:34', '2023-10-06 03:55:34'),
(34, NULL, 'z', 'z', 'z@gmail.com', '8787988778', NULL, '$2y$10$DgattuqSyeWpgiFZ8S05VuIfxsQxVosylxxBtyyoCS7NTAVAl87Ei', '34z8661', NULL, '2023-10-06 03:59:06', '2023-10-06 03:59:06'),
(35, NULL, 'z1', 'z1@gmail.com', 'z1@gmail.com', '9483484343', NULL, '$2y$10$amim7yhmYL.jU.c2rWHem.vOpW/V574BiU8JC.nnnmDjC.bub6TKy', '35z14364', NULL, '2023-10-06 04:04:18', '2023-10-06 04:04:18'),
(36, NULL, 'ram', 'ram', 'ram@gmail.com', '3849348433', NULL, '$2y$10$UCKR/XISqfqdZbfubXJVfOaTA1RS08qktxjfAqpSsCWvyt7JjZbqa', '36ram9979', NULL, '2023-10-06 04:06:36', '2023-10-06 04:06:36'),
(37, NULL, 'jh', 'jh', 'jh@gmail.com', '9878977878', NULL, '$2y$10$io2jMC9Ty1WBqs9aidwtyuynC1xOxd6OANc6km8Ei7k7FVWObHDgC', '37jh9234', NULL, '2023-10-06 04:07:56', '2023-10-06 04:07:56'),
(38, NULL, 'lp', 'lp', 'lp@gmail.com', '7868686677', NULL, '$2y$10$Alz.DYXbIlYvJsD.P3sygOnJzrFyEkAyyakPd6gs7dls4jqSAFiYy', '38lp3885', NULL, '2023-10-06 04:09:08', '2023-10-06 04:09:08'),
(39, NULL, 'oo', 'oo', 'oo@gmail.com', '8778877887', NULL, '$2y$10$ALtZ4R8L8qEfn5mNpYDYZefqRep62hZA/bqViWpMaabS1/FZBC6F2', '39oo3348', NULL, '2023-10-06 04:12:01', '2023-10-06 04:12:01'),
(40, 2, 'lund', 'lund', 'lund@gmail.com', '9843942434', NULL, '$2y$10$i/JUKuCUcLL79VW3ipfaWunKKtIQxXCROzG9GHcNwGrTLB03R9Lom', '40lund6391', NULL, '2023-10-06 04:49:23', '2023-10-06 04:49:23');

-- --------------------------------------------------------

--
-- Table structure for table `user_reference_mapping`
--

DROP TABLE IF EXISTS `user_reference_mapping`;
CREATE TABLE IF NOT EXISTS `user_reference_mapping` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `reference_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_reference_mapping`
--

INSERT INTO `user_reference_mapping` (`id`, `user_id`, `reference_id`, `created_at`, `updated_at`) VALUES
(1, 37, 21, '2023-10-06 04:07:56', '2023-10-06 04:07:56'),
(2, 38, 21, '2023-10-06 04:09:08', '2023-10-06 04:09:08'),
(3, 39, 21, '2023-10-06 04:12:01', '2023-10-06 04:12:01'),
(4, 40, 21, '2023-10-06 04:49:23', '2023-10-06 04:49:23');

-- --------------------------------------------------------

--
-- Table structure for table `user_wallet_history`
--

DROP TABLE IF EXISTS `user_wallet_history`;
CREATE TABLE IF NOT EXISTS `user_wallet_history` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `reference_id` int NOT NULL,
  `amount` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_wallet_history`
--

INSERT INTO `user_wallet_history` (`id`, `user_id`, `reference_id`, `amount`, `created_at`, `updated_at`) VALUES
(1, 39, 21, 191.00, '2023-10-06 04:12:01', '2023-10-06 04:12:01'),
(2, 40, 21, 191.00, '2023-10-06 04:49:23', '2023-10-06 04:49:23');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
