-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Oct 17, 2023 at 05:37 AM
-- Server version: 10.10.2-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `growfresh`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `f_name` varchar(100) DEFAULT NULL,
  `l_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `identity_image` varchar(255) DEFAULT NULL,
  `identity_type` varchar(255) DEFAULT NULL,
  `identity_number` varchar(255) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `fcm_token` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `admin_role_id` bigint(20) NOT NULL DEFAULT 2,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `f_name`, `l_name`, `phone`, `email`, `identity_image`, `identity_type`, `identity_number`, `image`, `password`, `remember_token`, `created_at`, `updated_at`, `fcm_token`, `status`, `admin_role_id`) VALUES
(1, 'John', 'Doe', '917984843285', 'admin@gmail.com', NULL, NULL, NULL, NULL, '$2a$12$qZD96.3umtjeHOfzZ1e1oe/mYh38c54buKV5z7cD7F16rZvb6pbLq', 'JwmrFTX5oZ5yPGb9Ym2C3NK7DJsjhQrpsMMp69MYpwWLEE5VmWJyFE4fiF8F', '2023-10-03 12:56:42', '2023-10-03 12:56:42', NULL, 1, 1),
(2, 'JJ Srivastv', NULL, '7856231245', 'jj@gmail.com', '[\"2023-10-10-6526216fafd27.png\"]', 'driving_license', 'DH-34567-JA', '2023-10-10-6526216fae283.png', '$2y$10$qBa0fLYVSSUd.ynqCU9VnORC1I/W3W.dgFpw7JoIkeFOekceweIya', NULL, '2023-10-10 11:45:43', '2023-10-10 11:45:43', NULL, 1, 2),
(3, 'SS Varma', NULL, '7845784512', 'ss@gmail.com', '[\"2023-10-10-652621d0d3148.png\"]', 'passport', 'JJ-45454-HJ', '2023-10-10-652621d0d1f70.png', '$2y$10$7E7Fz0A1khQClFmNURlBG.QWiAqasBlhVGbcUaecYxHo8dVM5YRLC', NULL, '2023-10-10 11:47:20', '2023-10-10 11:47:20', NULL, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `admin_roles`
--

DROP TABLE IF EXISTS `admin_roles`;
CREATE TABLE IF NOT EXISTS `admin_roles` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `module_access` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_roles`
--

INSERT INTO `admin_roles` (`id`, `name`, `module_access`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Master Admin', NULL, 1, '2022-06-17 16:45:59', '2022-06-17 16:45:59'),
(2, 'Manager', '[\"dashboard_management\",\"pos_management\",\"order_management\",\"product_management\",\"promotion_management\",\"support_management\",\"report_management\",\"user_management\",\"system_management\"]', 1, '2023-10-10 11:43:56', '2023-10-10 11:43:56'),
(3, 'Sr Employee', '[\"dashboard_management\",\"pos_management\",\"product_management\"]', 1, '2023-10-10 11:44:39', '2023-10-10 11:44:39');

-- --------------------------------------------------------

--
-- Table structure for table `attributes`
--

DROP TABLE IF EXISTS `attributes`;
CREATE TABLE IF NOT EXISTS `attributes` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attributes`
--

INSERT INTO `attributes` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'high anti-oxidant capacity', '2023-10-09 12:55:09', '2023-10-09 12:55:09'),
(2, 'cold hardy and cancer fighting', '2023-10-09 12:55:34', '2023-10-09 12:55:34'),
(3, 'a soft, fatty, vascular tissue in the interior cavities of bones that is a major site of blood cell production', '2023-10-09 12:56:36', '2023-10-09 12:56:36');

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
CREATE TABLE IF NOT EXISTS `banners` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `link` text DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `banner_order` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `title`, `image`, `product_id`, `link`, `type`, `banner_order`, `status`, `created_at`, `updated_at`, `category_id`) VALUES
(1, 'Banner1', '2023-10-09-6524f3192e807.png', NULL, NULL, NULL, NULL, 1, '2023-10-09 14:15:45', '2023-10-09 14:15:45', 1),
(2, 'banner2', '2023-10-09-6524f32f2c3dc.png', 14, NULL, NULL, NULL, 1, '2023-10-09 14:16:07', '2023-10-09 14:16:07', NULL),
(3, 'banner3', '2023-10-09-6524f343ef8f4.png', NULL, NULL, NULL, NULL, 1, '2023-10-09 14:16:27', '2023-10-09 14:16:27', 3),
(7, 'Testwb nbhdd', '2023-10-13-6528ecd249309.png', NULL, 'https://www.google.com/', 'home_banner', 1, 1, '2023-10-13 07:08:02', '2023-10-13 07:21:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

DROP TABLE IF EXISTS `branches`;
CREATE TABLE IF NOT EXISTS `branches` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `restaurant_id` bigint(20) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `latitude` varchar(255) DEFAULT NULL,
  `longitude` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `coverage` int(11) NOT NULL DEFAULT 1,
  `remember_token` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `restaurant_id`, `name`, `email`, `phone`, `password`, `latitude`, `longitude`, `address`, `status`, `created_at`, `updated_at`, `coverage`, `remember_token`, `image`) VALUES
(1, NULL, 'Dinajpur', 'newb@gmail.com', NULL, '$2y$10$HJW/gFTjbpX8VIDp4I.EjO0QhlFTek7ydjRMkIiDjSuCHEpWZDafK', '22.848823', '91.390306', 'Hazi osman gani lane', 1, '2021-02-24 09:45:49', '2021-07-06 03:09:00', 500, NULL, NULL),
(10, NULL, 'HDS Ahmdabad', 'hds@gmail.com', '9988776655', '$2y$10$V.jIFXCyFgmMIsNhzrndo.a3g2y3Lf1kcmsyRB87XiE.N/mQOsqgu', '23.017077541781703', '72.5673287193085', '1109, Satyamev Eminence', 1, '2023-10-11 04:22:03', '2023-10-11 04:22:03', 13, NULL, '2023-10-11-652622eb50093.png');

-- --------------------------------------------------------

--
-- Table structure for table `business_settings`
--

DROP TABLE IF EXISTS `business_settings`;
CREATE TABLE IF NOT EXISTS `business_settings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(255) DEFAULT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `business_settings`
--

INSERT INTO `business_settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'self_pickup', '1', '2021-01-06 05:55:51', '2021-01-06 05:55:51'),
(3, 'restaurant_name', 'GroFresh', NULL, NULL),
(4, 'currency', 'ZAR', NULL, NULL),
(5, 'logo', '2021-06-21-60d0370804378.png', NULL, NULL),
(6, 'mail_config', '{\"status\":0,\"name\":\"Delivery APP\",\"host\":\"mail.demo.com\",\"driver\":\"smtp\",\"port\":\"587\",\"username\":\"info@demo.com\",\"email_id\":\"info@demo.com\",\"encryption\":\"tls\",\"password\":\"demo\"}', NULL, '2023-05-17 12:59:45'),
(7, 'delivery_charge', '100', NULL, NULL),
(8, 'ssl_commerz_payment', '{\"status\":\"0\",\"store_id\":null,\"store_password\":null}', NULL, '2021-07-25 09:32:22'),
(9, 'paypal', '{\"status\":\"0\",\"paypal_client_id\":null,\"paypal_secret\":null}', NULL, '2021-07-25 09:32:42'),
(10, 'stripe', '{\"status\":\"0\",\"api_key\":null,\"published_key\":null}', NULL, '2021-07-25 09:32:50'),
(11, 'phone', '+01747413273', NULL, NULL),
(13, 'footer_text', 'copyright', NULL, NULL),
(14, 'address', 'Hazi osman gani lane', NULL, NULL),
(15, 'email_address', 'abcd@gmail.com', NULL, NULL),
(16, 'cash_on_delivery', '{\"status\":\"1\"}', NULL, '2021-02-11 18:39:36'),
(17, 'email_verification', '0', NULL, NULL),
(18, 'digital_payment', '{\"status\":\"1\"}', '2021-01-30 19:38:54', '2021-01-30 19:38:58'),
(19, 'terms_and_conditions', '<p></p>', NULL, '2021-05-30 08:51:56'),
(20, 'fcm_topic', '', NULL, NULL),
(21, 'fcm_project_id', '3f34f34', NULL, NULL),
(22, 'push_notification_key', 'demo', NULL, NULL),
(24, 'order_pending_message', '{\"status\":1,\"message\":\"Your order has been placed successfully.\"}', NULL, NULL),
(25, 'order_processing_message', '{\"status\":1,\"message\":\"Your order is going to the cook\"}', NULL, NULL),
(26, 'out_for_delivery_message', '{\"status\":1,\"message\":\"Order out for delivery.\"}', NULL, NULL),
(27, 'order_delivered_message', '{\"status\":1,\"message\":\"delivered\"}', NULL, NULL),
(28, 'delivery_boy_assign_message', '{\"status\":1,\"message\":\"boy assigned\"}', NULL, NULL),
(29, 'delivery_boy_start_message', '{\"status\":1,\"message\":\"start delivery\"}', NULL, NULL),
(30, 'delivery_boy_delivered_message', '{\"status\":1,\"message\":\"boy delivered\"}', NULL, NULL),
(32, 'order_confirmation_msg', '{\"status\":1,\"message\":\"Your order has been confirmed.\"}', NULL, NULL),
(33, 'razor_pay', '{\"status\":\"0\",\"razor_key\":null,\"razor_secret\":null}', '2021-02-14 10:15:12', '2021-07-25 09:32:32'),
(34, 'location_coverage', '{\"status\":1,\"longitude\":\"91.410747\",\"latitude\":\"22.986282\",\"coverage\":\"20\"}', NULL, NULL),
(35, 'minimum_order_value', '50', NULL, NULL),
(36, 'software_mode', 'dev', NULL, NULL),
(37, 'software_version', NULL, NULL, NULL),
(38, 'paystack', '{\"status\":\"0\",\"publicKey\":null,\"secretKey\":null,\"paymentUrl\":\"https:\\/\\/api.paystack.co\",\"merchantEmail\":null}', '2021-04-24 18:40:34', '2021-07-25 09:33:19'),
(39, 'senang_pay', '{\"status\":\"0\",\"secret_key\":null,\"merchant_id\":null}', '2021-04-27 14:02:18', '2021-07-25 09:33:06'),
(40, 'privacy_policy', '<p>rjdrjf</p>', NULL, '2021-05-30 08:52:40'),
(41, 'about_us', '<p><strong>hello</strong></p>', NULL, '2021-05-30 08:44:52'),
(42, 'paystack', '{\"status\":\"0\",\"publicKey\":null,\"secretKey\":null,\"paymentUrl\":\"https:\\/\\/api.paystack.co\",\"merchantEmail\":null}', NULL, '2021-07-25 09:33:19'),
(43, 'currency_symbol_position', 'right', NULL, NULL),
(44, 'country', 'IN', NULL, NULL),
(45, 'language', '[{\"id\":1,\"name\":\"en\",\"direction\":\"ltr\",\"code\":\"en\",\"status\":1,\"default\":false},{\"id\":2,\"name\":\"Japanese\",\"direction\":\"ltr\",\"code\":\"ja\",\"status\":1,\"default\":true}]', NULL, '2023-10-11 04:32:55'),
(46, 'time_zone', 'Asia/Kolkata', NULL, NULL),
(47, 'phone_verification', '0', NULL, NULL),
(48, 'maintenance_mode', '0', NULL, NULL),
(49, 'twilio_sms', '{\"status\":0,\"sid\":null,\"token\":null,\"from\":null,\"otp_template\":null}', '2021-09-05 09:16:15', '2021-09-05 09:16:15'),
(50, 'nexmo_sms', '{\"status\":0,\"api_key\":null,\"api_secret\":null,\"signature_secret\":\"\",\"private_key\":\"\",\"application_id\":\"\",\"from\":null,\"otp_template\":null}', '2021-09-05 09:16:20', '2021-09-05 09:16:20'),
(51, '2factor_sms', '{\"status\":\"0\",\"api_key\":null}', '2021-09-05 09:16:25', '2021-09-05 09:16:25'),
(52, 'msg91_sms', '{\"status\":0,\"template_id\":null,\"authkey\":null}', '2021-09-05 09:16:30', '2021-09-05 09:16:30'),
(53, 'pagination_limit', '10', NULL, NULL),
(54, 'map_api_key', '', NULL, NULL),
(55, 'delivery_management', '{\"status\":0,\"min_shipping_charge\":0,\"shipping_per_km\":0}', NULL, NULL),
(56, 'play_store_config', '{\"status\":\"\",\"link\":\"\",\"min_version\":\"1\"}', NULL, NULL),
(57, 'app_store_config', '{\"status\":\"\",\"link\":\"\",\"min_version\":\"1\"}', NULL, NULL),
(58, 'recaptcha', '{\"status\":\"0\",\"site_key\":\"\",\"secret_key\":\"\"}', NULL, NULL),
(59, 'decimal_point_settings', '2', NULL, NULL),
(60, 'time_format', '24', NULL, NULL),
(61, 'minimum_stock_limit', '1', NULL, NULL),
(62, 'faq', NULL, NULL, NULL),
(63, 'google_social_login', '1', NULL, NULL),
(64, 'facebook_social_login', '1', NULL, NULL),
(65, 'wallet_status', '1', NULL, NULL),
(66, 'loyalty_point_status', '0', NULL, NULL),
(67, 'ref_earning_status', '0', NULL, NULL),
(68, 'loyalty_point_exchange_rate', '0', NULL, NULL),
(69, 'ref_earning_exchange_rate', '0', NULL, NULL),
(70, 'loyalty_point_percent_on_item_purchase', '0', NULL, NULL),
(71, 'loyalty_point_minimum_point', '1', NULL, NULL),
(72, 'free_delivery_over_amount', '2000', NULL, NULL),
(73, 'maximum_amount_for_cod_order', '1000', NULL, NULL),
(74, 'cookies', '{\"status\":\"1\",\"text\":\"Allow Cookies for this site\"}', NULL, NULL),
(75, 'offline_payment', '{\"status\":\"1\"}', NULL, NULL),
(76, 'product_vat_tax_status', 'excluded', NULL, NULL),
(77, 'whatsapp', '{\"status\":\"0\",\"number\":\"\"}', NULL, NULL),
(78, 'telegram', '{\"status\":\"0\",\"user_name\":\"\"}', NULL, NULL),
(79, 'messenger', '{\"status\":\"0\",\"user_name\":\"\"}', NULL, NULL),
(80, 'featured_product_status', '1', NULL, NULL),
(81, 'trending_product_status', '1', NULL, NULL),
(82, 'most_reviewed_product_status', '1', NULL, NULL),
(83, 'recommended_product_status', '1', NULL, NULL),
(84, 'fav_icon', '', NULL, NULL),
(85, 'dm_self_registration', '1', NULL, NULL),
(86, 'maximum_otp_hit', '5', NULL, NULL),
(87, 'otp_resend_time', '60', NULL, NULL),
(88, 'temporary_block_time', '600', NULL, NULL),
(89, 'maximum_login_hit', '5', NULL, NULL),
(90, 'temporary_login_block_time', '600', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `parent_id` bigint(20) NOT NULL,
  `position` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `image` varchar(255) NOT NULL DEFAULT 'def.png',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `parent_id`, `position`, `status`, `created_at`, `updated_at`, `image`) VALUES
(1, 'leafy green', 0, 0, 1, '2023-10-09 12:49:35', '2023-10-09 12:49:35', '2023-10-09-6524dee6bf995.png'),
(2, 'cruciferous', 0, 0, 1, '2023-10-09 12:50:48', '2023-10-09 12:50:48', '2023-10-09-6524df300ec7b.png'),
(3, 'marrow', 0, 0, 1, '2023-10-09 12:51:42', '2023-10-09 12:51:42', '2023-10-09-6524df66955fc.png'),
(4, 'lettuce', 1, 1, 1, '2023-10-09 12:52:56', '2023-10-09 12:52:56', 'def.png'),
(5, 'silverbeet', 1, 1, 1, '2023-10-09 12:53:15', '2023-10-09 12:53:15', 'def.png'),
(6, 'cabbage', 2, 1, 1, '2023-10-09 12:53:28', '2023-10-09 12:53:28', 'def.png'),
(7, 'cauliflower', 2, 1, 1, '2023-10-09 12:53:41', '2023-10-09 12:53:41', 'def.png'),
(8, 'broccoli', 2, 1, 1, '2023-10-09 12:54:00', '2023-10-09 12:54:00', 'def.png'),
(9, 'pumpkin', 3, 1, 1, '2023-10-09 12:54:10', '2023-10-09 12:54:10', 'def.png'),
(10, 'zucchini', 3, 1, 1, '2023-10-09 12:54:21', '2023-10-09 12:54:21', 'def.png');

-- --------------------------------------------------------

--
-- Table structure for table `category_discounts`
--

DROP TABLE IF EXISTS `category_discounts`;
CREATE TABLE IF NOT EXISTS `category_discounts` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `start_date` date DEFAULT NULL,
  `expire_date` date DEFAULT NULL,
  `discount_type` varchar(255) DEFAULT NULL,
  `discount_amount` double(8,2) NOT NULL DEFAULT 0.00,
  `maximum_amount` double(8,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_discounts_category_id_unique` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category_discounts`
--

INSERT INTO `category_discounts` (`id`, `name`, `category_id`, `start_date`, `expire_date`, `discount_type`, `discount_amount`, `maximum_amount`, `status`, `created_at`, `updated_at`) VALUES
(1, 'TestDis', 2, '2023-10-11', '2023-10-20', 'percent', 10.00, 5.00, 0, '2023-10-09 14:22:07', '2023-10-09 14:22:07'),
(2, 'NewDIS', 3, '2023-10-10', '2023-10-13', 'amount', 100.00, 0.00, 0, '2023-10-09 14:22:30', '2023-10-09 14:22:30');

-- --------------------------------------------------------

--
-- Table structure for table `category_searched_by_user`
--

DROP TABLE IF EXISTS `category_searched_by_user`;
CREATE TABLE IF NOT EXISTS `category_searched_by_user` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `category_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

DROP TABLE IF EXISTS `conversations`;
CREATE TABLE IF NOT EXISTS `conversations` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `reply` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `checked` tinyint(1) NOT NULL DEFAULT 0,
  `image` text DEFAULT NULL,
  `is_reply` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `code` varchar(15) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `expire_date` date DEFAULT NULL,
  `min_purchase` decimal(8,2) NOT NULL DEFAULT 0.00,
  `max_discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `discount_type` varchar(15) NOT NULL DEFAULT 'percentage',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `coupon_type` varchar(255) NOT NULL DEFAULT 'default',
  `limit` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `title`, `code`, `start_date`, `expire_date`, `min_purchase`, `max_discount`, `discount`, `discount_type`, `status`, `created_at`, `updated_at`, `coupon_type`, `limit`, `customer_id`) VALUES
(1, 'Coupon1', 'bu2o1iv48z', '2023-10-15', '2023-10-31', '3.00', '5.00', '10.00', 'percent', 1, '2023-10-09 14:17:14', '2023-10-09 14:17:14', 'first_order', NULL, NULL),
(2, 'Coupon2', 'ivm1ywwjgi', '2023-10-11', '2023-10-27', '3.00', '3.00', '5.00', 'percent', 1, '2023-10-09 14:18:07', '2023-10-09 14:18:07', 'customer_wise', 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

DROP TABLE IF EXISTS `currencies`;
CREATE TABLE IF NOT EXISTS `currencies` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `country` varchar(255) DEFAULT NULL,
  `currency_code` varchar(255) DEFAULT NULL,
  `currency_symbol` varchar(255) DEFAULT NULL,
  `exchange_rate` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `country`, `currency_code`, `currency_symbol`, `exchange_rate`, `created_at`, `updated_at`) VALUES
(1, 'US Dollar', 'USD', '$', '1.00', NULL, NULL),
(2, 'Canadian Dollar', 'CAD', 'CA$', '1.00', NULL, NULL),
(3, 'Euro', 'EUR', '€', '1.00', NULL, NULL),
(4, 'United Arab Emirates Dirham', 'AED', 'د.إ.‏', '1.00', NULL, NULL),
(5, 'Afghan Afghani', 'AFN', '؋', '1.00', NULL, NULL),
(6, 'Albanian Lek', 'ALL', 'L', '1.00', NULL, NULL),
(7, 'Armenian Dram', 'AMD', '֏', '1.00', NULL, NULL),
(8, 'Argentine Peso', 'ARS', '$', '1.00', NULL, NULL),
(9, 'Australian Dollar', 'AUD', '$', '1.00', NULL, NULL),
(10, 'Azerbaijani Manat', 'AZN', '₼', '1.00', NULL, NULL),
(11, 'Bosnia-Herzegovina Convertible Mark', 'BAM', 'KM', '1.00', NULL, NULL),
(12, 'Bangladeshi Taka', 'BDT', '৳', '1.00', NULL, NULL),
(13, 'Bulgarian Lev', 'BGN', 'лв.', '1.00', NULL, NULL),
(14, 'Bahraini Dinar', 'BHD', 'د.ب.‏', '1.00', NULL, NULL),
(15, 'Burundian Franc', 'BIF', 'FBu', '1.00', NULL, NULL),
(16, 'Brunei Dollar', 'BND', 'B$', '1.00', NULL, NULL),
(17, 'Bolivian Boliviano', 'BOB', 'Bs', '1.00', NULL, NULL),
(18, 'Brazilian Real', 'BRL', 'R$', '1.00', NULL, NULL),
(19, 'Botswanan Pula', 'BWP', 'P', '1.00', NULL, NULL),
(20, 'Belarusian Ruble', 'BYN', 'Br', '1.00', NULL, NULL),
(21, 'Belize Dollar', 'BZD', '$', '1.00', NULL, NULL),
(22, 'Congolese Franc', 'CDF', 'FC', '1.00', NULL, NULL),
(23, 'Swiss Franc', 'CHF', 'CHf', '1.00', NULL, NULL),
(24, 'Chilean Peso', 'CLP', '$', '1.00', NULL, NULL),
(25, 'Chinese Yuan', 'CNY', '¥', '1.00', NULL, NULL),
(26, 'Colombian Peso', 'COP', '$', '1.00', NULL, NULL),
(27, 'Costa Rican Colón', 'CRC', '₡', '1.00', NULL, NULL),
(28, 'Cape Verdean Escudo', 'CVE', '$', '1.00', NULL, NULL),
(29, 'Czech Republic Koruna', 'CZK', 'Kč', '1.00', NULL, NULL),
(30, 'Djiboutian Franc', 'DJF', 'Fdj', '1.00', NULL, NULL),
(31, 'Danish Krone', 'DKK', 'Kr.', '1.00', NULL, NULL),
(32, 'Dominican Peso', 'DOP', 'RD$', '1.00', NULL, NULL),
(33, 'Algerian Dinar', 'DZD', 'د.ج.‏', '1.00', NULL, NULL),
(34, 'Estonian Kroon', 'EEK', 'kr', '1.00', NULL, NULL),
(35, 'Egyptian Pound', 'EGP', 'E£‏', '1.00', NULL, NULL),
(36, 'Eritrean Nakfa', 'ERN', 'Nfk', '1.00', NULL, NULL),
(37, 'Ethiopian Birr', 'ETB', 'Br', '1.00', NULL, NULL),
(38, 'British Pound Sterling', 'GBP', '£', '1.00', NULL, NULL),
(39, 'Georgian Lari', 'GEL', 'GEL', '1.00', NULL, NULL),
(40, 'Ghanaian Cedi', 'GHS', 'GH¢', '1.00', NULL, NULL),
(41, 'Guinean Franc', 'GNF', 'FG', '1.00', NULL, NULL),
(42, 'Guatemalan Quetzal', 'GTQ', 'Q', '1.00', NULL, NULL),
(43, 'Hong Kong Dollar', 'HKD', 'HK$', '1.00', NULL, NULL),
(44, 'Honduran Lempira', 'HNL', 'L', '1.00', NULL, NULL),
(45, 'Croatian Kuna', 'HRK', 'kn', '1.00', NULL, NULL),
(46, 'Hungarian Forint', 'HUF', 'Ft', '1.00', NULL, NULL),
(47, 'Indonesian Rupiah', 'IDR', 'Rp', '1.00', NULL, NULL),
(48, 'Israeli New Sheqel', 'ILS', '₪', '1.00', NULL, NULL),
(49, 'Indian Rupee', 'INR', '₹', '1.00', NULL, NULL),
(50, 'Iraqi Dinar', 'IQD', 'ع.د', '1.00', NULL, NULL),
(51, 'Iranian Rial', 'IRR', '﷼', '1.00', NULL, NULL),
(52, 'Icelandic Króna', 'ISK', 'kr', '1.00', NULL, NULL),
(53, 'Jamaican Dollar', 'JMD', '$', '1.00', NULL, NULL),
(54, 'Jordanian Dinar', 'JOD', 'د.ا‏', '1.00', NULL, NULL),
(55, 'Japanese Yen', 'JPY', '¥', '1.00', NULL, NULL),
(56, 'Kenyan Shilling', 'KES', 'Ksh', '1.00', NULL, NULL),
(57, 'Cambodian Riel', 'KHR', '៛', '1.00', NULL, NULL),
(58, 'Comorian Franc', 'KMF', 'FC', '1.00', NULL, NULL),
(59, 'South Korean Won', 'KRW', 'CF', '1.00', NULL, NULL),
(60, 'Kuwaiti Dinar', 'KWD', 'د.ك.‏', '1.00', NULL, NULL),
(61, 'Kazakhstani Tenge', 'KZT', '₸.', '1.00', NULL, NULL),
(62, 'Lebanese Pound', 'LBP', 'ل.ل.‏', '1.00', NULL, NULL),
(63, 'Sri Lankan Rupee', 'LKR', 'Rs', '1.00', NULL, NULL),
(64, 'Lithuanian Litas', 'LTL', 'Lt', '1.00', NULL, NULL),
(65, 'Latvian Lats', 'LVL', 'Ls', '1.00', NULL, NULL),
(66, 'Libyan Dinar', 'LYD', 'د.ل.‏', '1.00', NULL, NULL),
(67, 'Moroccan Dirham', 'MAD', 'د.م.‏', '1.00', NULL, NULL),
(68, 'Moldovan Leu', 'MDL', 'L', '1.00', NULL, NULL),
(69, 'Malagasy Ariary', 'MGA', 'Ar', '1.00', NULL, NULL),
(70, 'Macedonian Denar', 'MKD', 'Ден', '1.00', NULL, NULL),
(71, 'Myanma Kyat', 'MMK', 'K', '1.00', NULL, NULL),
(72, 'Macanese Pataca', 'MOP', 'MOP$', '1.00', NULL, NULL),
(73, 'Mauritian Rupee', 'MUR', 'Rs', '1.00', NULL, NULL),
(74, 'Mexican Peso', 'MXN', '$', '1.00', NULL, NULL),
(75, 'Malaysian Ringgit', 'MYR', 'RM', '1.00', NULL, NULL),
(76, 'Mozambican Metical', 'MZN', 'MT', '1.00', NULL, NULL),
(77, 'Namibian Dollar', 'NAD', 'N$', '1.00', NULL, NULL),
(78, 'Nigerian Naira', 'NGN', '₦', '1.00', NULL, NULL),
(79, 'Nicaraguan Córdoba', 'NIO', 'C$', '1.00', NULL, NULL),
(80, 'Norwegian Krone', 'NOK', 'kr', '1.00', NULL, NULL),
(81, 'Nepalese Rupee', 'NPR', 'Re.', '1.00', NULL, NULL),
(82, 'New Zealand Dollar', 'NZD', '$', '1.00', NULL, NULL),
(83, 'Omani Rial', 'OMR', 'ر.ع.‏', '1.00', NULL, NULL),
(84, 'Panamanian Balboa', 'PAB', 'B/.', '1.00', NULL, NULL),
(85, 'Peruvian Nuevo Sol', 'PEN', 'S/', '1.00', NULL, NULL),
(86, 'Philippine Peso', 'PHP', '₱', '1.00', NULL, NULL),
(87, 'Pakistani Rupee', 'PKR', 'Rs', '1.00', NULL, NULL),
(88, 'Polish Zloty', 'PLN', 'zł', '1.00', NULL, NULL),
(89, 'Paraguayan Guarani', 'PYG', '₲', '1.00', NULL, NULL),
(90, 'Qatari Rial', 'QAR', 'ر.ق.‏', '1.00', NULL, NULL),
(91, 'Romanian Leu', 'RON', 'lei', '1.00', NULL, NULL),
(92, 'Serbian Dinar', 'RSD', 'din.', '1.00', NULL, NULL),
(93, 'Russian Ruble', 'RUB', '₽.', '1.00', NULL, NULL),
(94, 'Rwandan Franc', 'RWF', 'FRw', '1.00', NULL, NULL),
(95, 'Saudi Riyal', 'SAR', 'ر.س.‏', '1.00', NULL, NULL),
(96, 'Sudanese Pound', 'SDG', 'ج.س.', '1.00', NULL, NULL),
(97, 'Swedish Krona', 'SEK', 'kr', '1.00', NULL, NULL),
(98, 'Singapore Dollar', 'SGD', '$', '1.00', NULL, NULL),
(99, 'Somali Shilling', 'SOS', 'Sh.so.', '1.00', NULL, NULL),
(100, 'Syrian Pound', 'SYP', 'LS‏', '1.00', NULL, NULL),
(101, 'Thai Baht', 'THB', '฿', '1.00', NULL, NULL),
(102, 'Tunisian Dinar', 'TND', 'د.ت‏', '1.00', NULL, NULL),
(103, 'Tongan Paʻanga', 'TOP', 'T$', '1.00', NULL, NULL),
(104, 'Turkish Lira', 'TRY', '₺', '1.00', NULL, NULL),
(105, 'Trinidad and Tobago Dollar', 'TTD', '$', '1.00', NULL, NULL),
(106, 'New Taiwan Dollar', 'TWD', 'NT$', '1.00', NULL, NULL),
(107, 'Tanzanian Shilling', 'TZS', 'TSh', '1.00', NULL, NULL),
(108, 'Ukrainian Hryvnia', 'UAH', '₴', '1.00', NULL, NULL),
(109, 'Ugandan Shilling', 'UGX', 'USh', '1.00', NULL, NULL),
(110, 'Uruguayan Peso', 'UYU', '$', '1.00', NULL, NULL),
(111, 'Uzbekistan Som', 'UZS', 'so\'m', '1.00', NULL, NULL),
(112, 'Venezuelan Bolívar', 'VEF', 'Bs.F.', '1.00', NULL, NULL),
(113, 'Vietnamese Dong', 'VND', '₫', '1.00', NULL, NULL),
(114, 'CFA Franc BEAC', 'XAF', 'FCFA', '1.00', NULL, NULL),
(115, 'CFA Franc BCEAO', 'XOF', 'CFA', '1.00', NULL, NULL),
(116, 'Yemeni Rial', 'YER', '﷼‏', '1.00', NULL, NULL),
(117, 'South African Rand', 'ZAR', 'R', '1.00', NULL, NULL),
(118, 'Zambian Kwacha', 'ZMK', 'ZK', '1.00', NULL, NULL),
(119, 'Zimbabwean Dollar', 'ZWL', 'Z$', '1.00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_addresses`
--

DROP TABLE IF EXISTS `customer_addresses`;
CREATE TABLE IF NOT EXISTS `customer_addresses` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `address_type` varchar(100) NOT NULL,
  `contact_person_number` varchar(20) NOT NULL,
  `address` varchar(250) DEFAULT NULL,
  `latitude` varchar(255) DEFAULT NULL,
  `longitude` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `contact_person_name` varchar(100) DEFAULT NULL,
  `road` varchar(255) DEFAULT NULL,
  `house` varchar(255) DEFAULT NULL,
  `floor` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dc_conversations`
--

DROP TABLE IF EXISTS `dc_conversations`;
CREATE TABLE IF NOT EXISTS `dc_conversations` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_histories`
--

DROP TABLE IF EXISTS `delivery_histories`;
CREATE TABLE IF NOT EXISTS `delivery_histories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) DEFAULT NULL,
  `deliveryman_id` bigint(20) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `longitude` varchar(255) DEFAULT NULL,
  `latitude` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_men`
--

DROP TABLE IF EXISTS `delivery_men`;
CREATE TABLE IF NOT EXISTS `delivery_men` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `f_name` varchar(100) DEFAULT NULL,
  `l_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `identity_number` varchar(30) DEFAULT NULL,
  `identity_type` varchar(50) DEFAULT NULL,
  `identity_image` text DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `password` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `auth_token` varchar(255) DEFAULT NULL,
  `fcm_token` varchar(255) DEFAULT NULL,
  `branch_id` bigint(20) NOT NULL DEFAULT 1,
  `application_status` varchar(255) NOT NULL DEFAULT 'approved' COMMENT 'pending, approved, denied',
  `login_hit_count` tinyint(4) NOT NULL DEFAULT 0,
  `is_temp_blocked` tinyint(1) NOT NULL DEFAULT 0,
  `temp_block_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `delivery_men_phone_unique` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `delivery_men`
--

INSERT INTO `delivery_men` (`id`, `f_name`, `l_name`, `phone`, `email`, `identity_number`, `identity_type`, `identity_image`, `image`, `is_active`, `password`, `created_at`, `updated_at`, `auth_token`, `fcm_token`, `branch_id`, `application_status`, `login_hit_count`, `is_temp_blocked`, `temp_block_time`) VALUES
(1, 'Parth', 'Shighala', '882211445566', 'parth@silverwebbuzz.com', 'DH-12345-ER', 'restaurant_id', '[\"2023-10-10-6526208ac2a11.png\"]', '2023-10-10-6526208a51194.png', 1, '$2y$10$Jiqvl0QL.z8B2obbwuKNFe1GN67Qd19UBXJYBd7D75TvHKEunjyM6', '2023-10-10 11:41:54', '2023-10-10 11:41:54', NULL, NULL, 1, 'approved', 0, 0, NULL),
(2, 'Jones', 'Baly', '7845121245', 'jones@gmail.com', 'HH-23456-RR', 'passport', '[\"2023-10-10-652620cb11992.png\"]', '2023-10-10-652620cb108a3.png', 1, '$2y$10$unFPHVPs7IBqF2dO4YskBuqMhik6w/XMjr5gZMQccVaE8iqLWK2Hu', '2023-10-10 11:42:59', '2023-10-10 11:42:59', NULL, NULL, 1, 'approved', 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `d_m_reviews`
--

DROP TABLE IF EXISTS `d_m_reviews`;
CREATE TABLE IF NOT EXISTS `d_m_reviews` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `delivery_man_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `order_id` bigint(20) NOT NULL,
  `comment` mediumtext DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `rating` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_verifications`
--

DROP TABLE IF EXISTS `email_verifications`;
CREATE TABLE IF NOT EXISTS `email_verifications` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `otp_hit_count` tinyint(4) NOT NULL DEFAULT 0,
  `is_temp_blocked` tinyint(1) NOT NULL DEFAULT 0,
  `temp_block_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorite_products`
--

DROP TABLE IF EXISTS `favorite_products`;
CREATE TABLE IF NOT EXISTS `favorite_products` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `flash_deals`
--

DROP TABLE IF EXISTS `flash_deals`;
CREATE TABLE IF NOT EXISTS `flash_deals` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `deal_type` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `featured` tinyint(4) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `flash_deals`
--

INSERT INTO `flash_deals` (`id`, `title`, `start_date`, `end_date`, `deal_type`, `status`, `featured`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Sale1', '2023-10-11', '2023-10-15', 'flash_deal', 0, 0, '2023-10-09-6524f46d2750a.png', '2023-10-09 14:21:25', '2023-10-09 14:21:25'),
(2, 'sale2', '2023-10-15', '2023-10-20', 'flash_deal', 0, 0, '2023-10-09-6524f47caeb02.png', '2023-10-09 14:21:40', '2023-10-09 14:21:40');

-- --------------------------------------------------------

--
-- Table structure for table `flash_deal_products`
--

DROP TABLE IF EXISTS `flash_deal_products`;
CREATE TABLE IF NOT EXISTS `flash_deal_products` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `flash_deal_id` bigint(20) DEFAULT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `discount` double(8,2) NOT NULL DEFAULT 0.00,
  `discount_type` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_transactions`
--

DROP TABLE IF EXISTS `loyalty_transactions`;
CREATE TABLE IF NOT EXISTS `loyalty_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `credit` decimal(24,2) NOT NULL DEFAULT 0.00,
  `debit` decimal(24,2) NOT NULL DEFAULT 0.00,
  `balance` decimal(24,2) NOT NULL DEFAULT 0.00,
  `transaction_type` varchar(191) DEFAULT NULL,
  `reference` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `conversation_id` bigint(20) NOT NULL,
  `customer_id` bigint(20) DEFAULT NULL,
  `deliveryman_id` bigint(20) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `attachment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=148 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(23, '2016_06_01_000001_create_oauth_auth_codes_table', 17),
(24, '2016_06_01_000002_create_oauth_access_tokens_table', 17),
(25, '2016_06_01_000003_create_oauth_refresh_tokens_table', 17),
(26, '2016_06_01_000004_create_oauth_clients_table', 17),
(27, '2016_06_01_000005_create_oauth_personal_access_clients_table', 17),
(68, '2021_03_07_065637_change_product_image_clumn_type', 42),
(69, '2021_03_11_061437_add_unit_column_to_products', 43),
(70, '2021_03_11_070016_add_unit_to_order_details', 43),
(71, '2021_04_04_153448_add_column_to_product_table', 44),
(72, '2021_04_05_071507_add_stock_info_in_order_details', 44),
(73, '2021_04_07_145217_update_product_price_column', 45),
(74, '2021_01_10_145134_create_time_slots_table', 46),
(75, '2021_03_22_164017_add_time_slot_id_to_orders_table', 46),
(76, '2021_03_24_154346_add_date_to_orders_table', 46),
(77, '2021_03_24_161320_add_date_to_time_slots_table', 46),
(78, '2021_03_27_100851_add_delivery_date_to_orders_table', 46),
(79, '2021_04_08_093406_add_capacity_to_products', 46),
(80, '2021_04_15_184101_add_delivery_date_and_time_to_order_details', 46),
(81, '2021_04_21_081459_add_stock_to_products', 46),
(82, '2021_04_21_094959_add_stock_info_to_order_details', 46),
(83, '2021_05_03_160034_add_callback_to_order', 47),
(84, '2021_06_17_054551_create_soft_credentials_table', 48),
(85, '2021_07_01_160828_add_col_daily_needs_products', 49),
(86, '2021_07_26_170256_change_price_col_type', 50),
(87, '2021_09_01_133521_create_phone_verifications_table', 51),
(88, '2021_09_01_134803_create_translations_table', 51),
(89, '2021_09_04_082220_rename_email_col', 51),
(90, '2021_10_12_104445_add_temporary_token_to_users_table', 52),
(91, '2021_11_06_113028_add_extra_discount_to_order_table', 53),
(92, '2022_02_17_101623_change_conversaton_table_column_and_type', 54),
(93, '2022_02_17_112013_create_dc_conversations_table', 54),
(94, '2022_02_17_112101_create_messages_table', 54),
(95, '2022_02_17_125728_add_fcm_token_to_admin_table', 54),
(96, '2022_02_22_093732_create_social_media_table', 54),
(97, '2022_02_22_103038_change_banner_title_length', 54),
(98, '2022_02_22_130430_create_newsletters_table', 54),
(99, '2022_02_24_085940_change_name_length_in_category_table', 54),
(100, '2022_02_24_095937_change_name_length_in_attribute_table', 54),
(101, '2022_02_26_150826_add_delivery_address_to_order_table', 54),
(102, '2022_02_27_104337_add_image_to_branch_table', 54),
(103, '2022_05_30_033052_create_favorite_products_table', 55),
(104, '2022_06_06_161829_create_admin_roles_table', 55),
(105, '2022_06_06_162546_add_two_column_to_admins_table', 55),
(106, '2022_06_09_095348_add_popularity_count_to_products_table', 55),
(107, '2022_10_27_002321_add_is_active_column_in_attributes_table', 56),
(108, '2022_10_28_214644_add_is_active_column_to_reviews_table', 56),
(109, '2022_10_29_194703_add_multiple_column_for_identity_in_admins_table', 56),
(110, '2022_10_29_224609_add_column_is_active_in_delivery_men_table', 56),
(111, '2022_10_30_190953_add_column_is_visible_in_products_table', 56),
(112, '2022_11_05_043305_add_phone_to_branches_table', 56),
(113, '2022_11_05_045429_add_column_is_block_to_users_table', 56),
(114, '2022_11_07_064201_add_multiple_column_in_customer_addresses_table', 56),
(115, '2022_11_29_160650_delete_is_active_from_attributes_table', 56),
(116, '2022_11_29_165014_delete_is_visible_from_products_table', 56),
(117, '2022_12_04_212159_add_login_medium_column_in_users_tables', 56),
(118, '2023_02_16_170841_add_multiple_column_to_users_table', 57),
(119, '2023_02_16_183440_create_wallet_transction_table', 57),
(120, '2023_02_18_111449_create_loyalty_transaction_table', 57),
(121, '2023_02_19_104242_create_tags_table', 57),
(122, '2023_02_19_104317_create_product_tag_table', 57),
(123, '2023_02_20_155814_add_customer_id_in_coupons_table', 57),
(124, '2023_02_22_160007_add_column_for_offline_payment_in_orders_table', 57),
(125, '2023_02_22_173354_create_table_flash_deals', 57),
(126, '2023_02_22_200523_create_recent_searches_table', 57),
(127, '2023_02_22_200535_create_searched_data_table', 57),
(128, '2023_02_23_095712_create_table_flashdeal_products', 57),
(129, '2023_02_23_150149_add_is_featured_in_products_table', 57),
(130, '2023_02_26_144956_add_maximum_order_quantity_in_products_table', 57),
(131, '2023_02_26_152222_create_category_discounts_table', 57),
(132, '2023_02_26_184816_create_visited_products_table', 57),
(133, '2023_03_11_104833_add_free_delivery_amount_to_orders_table', 57),
(134, '2023_03_18_160336_create_searched_keyword_counts_table', 57),
(135, '2023_03_18_160753_create_searched_category_table', 57),
(136, '2023_03_18_160953_create_searched_products_table', 57),
(137, '2023_03_19_130859_create_searched_keyword_users_table', 57),
(138, '2023_03_20_104722_create_category_searched_by_user_table', 57),
(139, '2023_03_20_110808_create_product_searched_by_user_table', 57),
(140, '2023_03_21_183551_add_vat_status_column_in_order_details_table', 57),
(141, '2023_05_15_041430_add_application_status_in_delivery_men_table', 58),
(142, '2023_05_15_153550_add_otp_hist_counts_column_in_phone_verification_tabel', 58),
(143, '2023_05_15_175430_add_otp_hits_counts_column_in_password_resets', 58),
(144, '2023_05_16_000027_add_otp_hits_counts_column_in_email_verifications', 58),
(145, '2023_05_17_121506_add_login_hit_count_in_users', 58),
(146, '2023_05_17_145621_change_column_type_in_delivery_men', 58),
(147, '2023_05_17_152928_add_login_hit_count_in_delivery_men', 58);

-- --------------------------------------------------------

--
-- Table structure for table `newsletters`
--

DROP TABLE IF EXISTS `newsletters`;
CREATE TABLE IF NOT EXISTS `newsletters` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `newsletters`
--

INSERT INTO `newsletters` (`id`, `email`, `created_at`, `updated_at`) VALUES
(1, 'test@gmail.com', '2023-10-11 04:26:21', '2023-10-11 04:26:21');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `image` varchar(50) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `title`, `description`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Special Offer In Navaratri', 'orem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standardsurvived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised', '2023-10-09-6524f4064b871.png', 1, '2023-10-09 14:19:42', '2023-10-09 14:19:42'),
(2, 'Add New User Discount', 'orem Ipsum is simply dummy text of the printer took a galley of type and scrambled it to makehanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem', '2023-10-09-6524f44e239b1.png', 1, '2023-10-09 14:20:54', '2023-10-09 14:20:54');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

DROP TABLE IF EXISTS `oauth_access_tokens`;
CREATE TABLE IF NOT EXISTS `oauth_access_tokens` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

DROP TABLE IF EXISTS `oauth_auth_codes`;
CREATE TABLE IF NOT EXISTS `oauth_auth_codes` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_auth_codes_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

DROP TABLE IF EXISTS `oauth_clients`;
CREATE TABLE IF NOT EXISTS `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `secret` varchar(100) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `redirect` text NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Laravel Personal Access Client', 'z5ky1aF18fNgAczYo0W2MdglqUxpyt0KsKdmbRIt', NULL, 'http://localhost', 1, 0, 0, '2021-01-05 18:07:29', '2021-01-05 18:07:29'),
(2, NULL, 'Laravel Password Grant Client', 'clk5DEe0ANVDYwD79OUYBkLcZ6CLSykUvULubUuk', 'users', 'http://localhost', 0, 1, 0, '2021-01-05 18:07:29', '2021-01-05 18:07:29'),
(3, NULL, 'Laravel Personal Access Client', 'v89pXMpj0Pv49vFb3vC0uqTZvTRPEGtso4wpvkab', NULL, 'http://localhost', 1, 0, 0, '2021-06-19 03:35:33', '2021-06-19 03:35:33'),
(4, NULL, 'Laravel Password Grant Client', '07Q6Fu6riULXZnYy1yd8lApmsn45TrZZyZKPxW3T', 'users', 'http://localhost', 0, 1, 0, '2021-06-19 03:35:33', '2021-06-19 03:35:33'),
(5, NULL, 'Laravel Personal Access Client', 'Y2hoZR0djsLirjddzmtFwKApIHhs5SNbNY0msWIq', NULL, 'http://localhost', 1, 0, 0, '2023-10-09 12:03:35', '2023-10-09 12:03:35'),
(6, NULL, 'Laravel Password Grant Client', 'hRH72XGk3FCA5VDWkTouxIFC3pVgQabjjUGU4YGh', 'users', 'http://localhost', 0, 1, 0, '2023-10-09 12:03:35', '2023-10-09 12:03:35');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

DROP TABLE IF EXISTS `oauth_personal_access_clients`;
CREATE TABLE IF NOT EXISTS `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2021-01-05 18:07:29', '2021-01-05 18:07:29'),
(2, 3, '2021-06-19 03:35:33', '2021-06-19 03:35:33'),
(3, 5, '2023-10-09 12:03:35', '2023-10-09 12:03:35');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

DROP TABLE IF EXISTS `oauth_refresh_tokens`;
CREATE TABLE IF NOT EXISTS `oauth_refresh_tokens` (
  `id` varchar(100) NOT NULL,
  `access_token_id` varchar(100) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `order_amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `coupon_discount_amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `coupon_discount_title` varchar(255) DEFAULT NULL,
  `payment_status` varchar(255) NOT NULL DEFAULT 'unpaid',
  `order_status` varchar(255) NOT NULL DEFAULT 'pending',
  `total_tax_amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(30) DEFAULT NULL,
  `transaction_reference` varchar(30) DEFAULT NULL,
  `delivery_address_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `checked` tinyint(1) NOT NULL DEFAULT 0,
  `delivery_man_id` bigint(20) DEFAULT NULL,
  `delivery_charge` decimal(8,2) NOT NULL DEFAULT 0.00,
  `order_note` text DEFAULT NULL,
  `coupon_code` varchar(255) DEFAULT NULL,
  `order_type` varchar(255) NOT NULL DEFAULT 'delivery',
  `branch_id` bigint(20) NOT NULL DEFAULT 1,
  `time_slot_id` bigint(20) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `callback` varchar(255) DEFAULT NULL,
  `extra_discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `delivery_address` text DEFAULT NULL,
  `payment_by` varchar(255) DEFAULT NULL,
  `payment_note` varchar(255) DEFAULT NULL,
  `free_delivery_amount` double(8,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100004 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_amount`, `coupon_discount_amount`, `coupon_discount_title`, `payment_status`, `order_status`, `total_tax_amount`, `payment_method`, `transaction_reference`, `delivery_address_id`, `created_at`, `updated_at`, `checked`, `delivery_man_id`, `delivery_charge`, `order_note`, `coupon_code`, `order_type`, `branch_id`, `time_slot_id`, `date`, `delivery_date`, `callback`, `extra_discount`, `delivery_address`, `payment_by`, `payment_note`, `free_delivery_amount`) VALUES
(100001, NULL, '229.50', '0.00', NULL, 'paid', 'delivered', '15.75', 'cash', NULL, NULL, '2023-10-09 14:24:04', '2023-10-09 14:24:04', 1, NULL, '0.00', NULL, NULL, 'pos', 1, NULL, NULL, '2023-10-09', NULL, '0.00', NULL, NULL, NULL, 0.00),
(100002, 1, '389.70', '0.00', NULL, 'paid', 'delivered', '19.00', 'card', NULL, NULL, '2023-10-09 14:25:11', '2023-10-09 14:25:11', 1, NULL, '0.00', NULL, NULL, 'pos', 1, NULL, NULL, '2023-10-09', NULL, '0.00', NULL, NULL, NULL, 0.00),
(100003, 2, '285.60', '0.00', NULL, 'paid', 'delivered', '14.00', 'cash', NULL, NULL, '2023-10-09 14:26:19', '2023-10-09 14:26:19', 1, NULL, '0.00', NULL, NULL, 'pos', 1, NULL, NULL, '2023-10-09', NULL, '0.00', NULL, NULL, NULL, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_delivery_histories`
--

DROP TABLE IF EXISTS `order_delivery_histories`;
CREATE TABLE IF NOT EXISTS `order_delivery_histories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) DEFAULT NULL,
  `delivery_man_id` bigint(20) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `start_location` varchar(255) DEFAULT NULL,
  `end_location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

DROP TABLE IF EXISTS `order_details`;
CREATE TABLE IF NOT EXISTS `order_details` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) DEFAULT NULL,
  `order_id` bigint(20) DEFAULT NULL,
  `price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `product_details` text DEFAULT NULL,
  `variation` varchar(255) DEFAULT NULL,
  `discount_on_product` decimal(8,2) DEFAULT NULL,
  `discount_type` varchar(20) NOT NULL DEFAULT 'amount',
  `quantity` int(11) NOT NULL DEFAULT 1,
  `tax_amount` decimal(8,2) NOT NULL DEFAULT 1.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `variant` varchar(255) DEFAULT NULL,
  `unit` varchar(255) NOT NULL DEFAULT 'pc',
  `is_stock_decreased` tinyint(1) NOT NULL DEFAULT 1,
  `time_slot_id` bigint(20) DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `vat_status` varchar(255) NOT NULL DEFAULT 'excluded' COMMENT 'included/excluded',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `product_id`, `order_id`, `price`, `product_details`, `variation`, `discount_on_product`, `discount_type`, `quantity`, `tax_amount`, `created_at`, `updated_at`, `variant`, `unit`, `is_stock_decreased`, `time_slot_id`, `delivery_date`, `vat_status`) VALUES
(1, 14, 100001, '75.00', '{\"id\":14,\"name\":\"GADZUKES ZUCCHINI\",\"description\":\"Gadzukes zucchinis are a hybrid variety that is a cross between a zucchini and a cucumber. They are similar in appearance to regular zucchinis, but they have a slightly lighter green color and a more cylindrical shape.\",\"image\":[],\"price\":75,\"variations\":[{\"type\":\"75\",\"price\":75,\"stock\":100}],\"tax\":7,\"status\":1,\"created_at\":\"2023-10-10T06:44:27.000000Z\",\"updated_at\":\"2023-10-10T06:44:27.000000Z\",\"attributes\":[\"3\"],\"category_ids\":[{\"id\":\"3\",\"position\":1},{\"id\":\"10\",\"position\":2}],\"choice_options\":[{\"name\":\"choice_3\",\"title\":\"asoft,fatty,vasculartissueintheinteriorcavitiesofbonesthatisamajorsiteofbloodcellproduction\",\"options\":[\"75\"]}],\"discount\":5,\"discount_type\":\"percent\",\"tax_type\":\"percent\",\"unit\":\"kg\",\"total_stock\":100,\"capacity\":1,\"daily_needs\":0,\"popularity_count\":0,\"is_featured\":0,\"view_count\":0,\"maximum_order_quantity\":10,\"category_discount\":null,\"translations\":[]}', '{\"asoft,fatty,vasculartissueintheinteriorcavitiesofbonesthatisamajorsiteofbloodcellproduction\":\"75\"}', '3.75', 'discount_on_product', 3, '5.25', '2023-10-09 14:24:04', '2023-10-09 14:24:04', '\"75\"', 'pc', 1, NULL, NULL, 'excluded'),
(2, 12, 100002, '100.00', '{\"id\":12,\"name\":\"Field Trip F1 Hybrid\",\"description\":\"Weighing in at 5 to 7 pounds each, with long, sturdy stems, these orange gourds are perfect for kids to grab and go.\",\"image\":[],\"price\":100,\"variations\":[{\"type\":\"100\",\"price\":100,\"stock\":70}],\"tax\":5,\"status\":1,\"created_at\":\"2023-10-10T06:41:21.000000Z\",\"updated_at\":\"2023-10-10T06:41:21.000000Z\",\"attributes\":[\"1\"],\"category_ids\":[{\"id\":\"3\",\"position\":1},{\"id\":\"9\",\"position\":2}],\"choice_options\":[{\"name\":\"choice_1\",\"title\":\"highanti-oxidantcapacity\",\"options\":[\"100\"]}],\"discount\":3,\"discount_type\":\"percent\",\"tax_type\":\"percent\",\"unit\":\"kg\",\"total_stock\":70,\"capacity\":1,\"daily_needs\":0,\"popularity_count\":0,\"is_featured\":0,\"view_count\":0,\"maximum_order_quantity\":10,\"category_discount\":null,\"translations\":[]}', '{\"highanti-oxidantcapacity\":\"100\"}', '3.00', 'discount_on_product', 1, '5.00', '2023-10-09 14:25:11', '2023-10-09 14:25:11', '\"100\"', 'pc', 1, NULL, NULL, 'excluded'),
(3, 13, 100002, '70.00', '{\"id\":13,\"name\":\"CLASSIC GREEN ZUCCHINI\",\"description\":\"The classic green zucchini is the most common type of zucchini found in grocery stores. It has a dark green skin with white flesh and is oblong or cylindrical in shape.\",\"image\":[],\"price\":70,\"variations\":[{\"type\":\"70\",\"price\":70,\"stock\":75}],\"tax\":5,\"status\":1,\"created_at\":\"2023-10-10T06:43:25.000000Z\",\"updated_at\":\"2023-10-10T06:43:25.000000Z\",\"attributes\":[\"3\"],\"category_ids\":[{\"id\":\"3\",\"position\":1},{\"id\":\"10\",\"position\":2}],\"choice_options\":[{\"name\":\"choice_3\",\"title\":\"asoft,fatty,vasculartissueintheinteriorcavitiesofbonesthatisamajorsiteofbloodcellproduction\",\"options\":[\"70\"]}],\"discount\":3,\"discount_type\":\"percent\",\"tax_type\":\"percent\",\"unit\":\"kg\",\"total_stock\":75,\"capacity\":1,\"daily_needs\":0,\"popularity_count\":0,\"is_featured\":0,\"view_count\":0,\"maximum_order_quantity\":5,\"category_discount\":null,\"translations\":[]}', '{\"asoft,fatty,vasculartissueintheinteriorcavitiesofbonesthatisamajorsiteofbloodcellproduction\":\"70\"}', '2.10', 'discount_on_product', 1, '3.50', '2023-10-09 14:25:11', '2023-10-09 14:25:11', '\"70\"', 'pc', 1, NULL, NULL, 'excluded'),
(4, 7, 100002, '42.00', '{\"id\":7,\"name\":\"Green Cauliflower\",\"description\":\"This cauliflower is also known as \\u2018broccoflower\\u2019 as it is a cross between broccoli and cauliflower and comes in various varieties.\",\"image\":[],\"price\":42,\"variations\":[{\"type\":\"42\",\"price\":42,\"stock\":75}],\"tax\":5,\"status\":1,\"created_at\":\"2023-10-10T06:34:09.000000Z\",\"updated_at\":\"2023-10-10T06:35:40.000000Z\",\"attributes\":[\"3\",\"2\"],\"category_ids\":[{\"id\":\"2\",\"position\":1},{\"id\":\"7\",\"position\":2}],\"choice_options\":[{\"name\":\"choice_3\",\"title\":\"asoft,fatty,vasculartissueintheinteriorcavitiesofbonesthatisamajorsiteofbloodcellproduction\",\"options\":[\"42\"]}],\"discount\":2,\"discount_type\":\"percent\",\"tax_type\":\"percent\",\"unit\":\"kg\",\"total_stock\":75,\"capacity\":1,\"daily_needs\":1,\"popularity_count\":0,\"is_featured\":0,\"view_count\":0,\"maximum_order_quantity\":5,\"category_discount\":null,\"translations\":[]}', '{\"asoft,fatty,vasculartissueintheinteriorcavitiesofbonesthatisamajorsiteofbloodcellproduction\":\"42\"}', '0.84', 'discount_on_product', 5, '2.10', '2023-10-09 14:25:11', '2023-10-09 14:25:11', '\"42\"', 'pc', 1, NULL, NULL, 'excluded'),
(5, 13, 100003, '70.00', '{\"id\":13,\"name\":\"CLASSIC GREEN ZUCCHINI\",\"description\":\"The classic green zucchini is the most common type of zucchini found in grocery stores. It has a dark green skin with white flesh and is oblong or cylindrical in shape.\",\"image\":[],\"price\":70,\"variations\":[{\"type\":\"70\",\"price\":70,\"stock\":74}],\"tax\":5,\"status\":1,\"created_at\":\"2023-10-10T06:43:25.000000Z\",\"updated_at\":\"2023-10-10T06:55:11.000000Z\",\"attributes\":[\"3\"],\"category_ids\":[{\"id\":\"3\",\"position\":1},{\"id\":\"10\",\"position\":2}],\"choice_options\":[{\"name\":\"choice_3\",\"title\":\"asoft,fatty,vasculartissueintheinteriorcavitiesofbonesthatisamajorsiteofbloodcellproduction\",\"options\":[\"70\"]}],\"discount\":3,\"discount_type\":\"percent\",\"tax_type\":\"percent\",\"unit\":\"kg\",\"total_stock\":74,\"capacity\":1,\"daily_needs\":0,\"popularity_count\":1,\"is_featured\":0,\"view_count\":0,\"maximum_order_quantity\":5,\"category_discount\":null,\"translations\":[]}', '{\"asoft,fatty,vasculartissueintheinteriorcavitiesofbonesthatisamajorsiteofbloodcellproduction\":\"70\"}', '2.10', 'discount_on_product', 4, '3.50', '2023-10-09 14:26:19', '2023-10-09 14:26:19', '\"70\"', 'pc', 1, NULL, NULL, 'excluded');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email_or_phone` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `otp_hit_count` tinyint(4) NOT NULL DEFAULT 0,
  `is_temp_blocked` tinyint(1) NOT NULL DEFAULT 0,
  `temp_block_time` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email_or_phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phone_verifications`
--

DROP TABLE IF EXISTS `phone_verifications`;
CREATE TABLE IF NOT EXISTS `phone_verifications` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `phone` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `otp_hit_count` tinyint(4) NOT NULL DEFAULT 0,
  `is_temp_blocked` tinyint(1) NOT NULL DEFAULT 0,
  `temp_block_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `price` double NOT NULL DEFAULT 0,
  `variations` text DEFAULT NULL,
  `tax` decimal(8,2) NOT NULL DEFAULT 0.00,
  `model` varchar(255) DEFAULT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `upc` varchar(255) DEFAULT NULL,
  `ena` varchar(255) DEFAULT NULL,
  `jan` varchar(255) DEFAULT NULL,
  `isbn` varchar(255) DEFAULT NULL,
  `mpn` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `substrack_stock` enum('yes','no') NOT NULL DEFAULT 'yes',
  `out_of_stock_status` enum('in stock','2-3 days','out of stock','pre order') NOT NULL DEFAULT 'in stock',
  `requires_shipping` enum('yes','no') DEFAULT NULL,
  `product_mark` varchar(255) DEFAULT NULL,
  `product_type` varchar(255) DEFAULT NULL,
  `date_available` date DEFAULT NULL,
  `d_length` varchar(255) DEFAULT NULL,
  `d_width` varchar(255) DEFAULT NULL,
  `d_height` varchar(255) DEFAULT NULL,
  `length_class` varchar(255) DEFAULT NULL,
  `weight` varchar(255) DEFAULT NULL,
  `weight_class` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT NULL,
  `attributes` varchar(255) DEFAULT NULL,
  `manufacturer` varchar(255) DEFAULT NULL,
  `filters` varchar(255) DEFAULT NULL,
  `stores` varchar(255) DEFAULT NULL,
  `downloads` varchar(255) DEFAULT NULL,
  `releted_product` varchar(255) DEFAULT NULL,
  `category_ids` varchar(255) DEFAULT NULL,
  `choice_options` text DEFAULT NULL,
  `discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `discount_type` varchar(20) NOT NULL DEFAULT 'percent',
  `tax_type` varchar(20) NOT NULL DEFAULT 'percent',
  `unit` varchar(255) NOT NULL DEFAULT 'pc',
  `total_stock` bigint(20) NOT NULL DEFAULT 0,
  `capacity` decimal(8,2) DEFAULT NULL,
  `daily_needs` tinyint(1) NOT NULL DEFAULT 0,
  `popularity_count` int(11) NOT NULL DEFAULT 0,
  `is_featured` tinyint(4) NOT NULL DEFAULT 0,
  `view_count` int(11) NOT NULL DEFAULT 0,
  `maximum_order_quantity` int(11) NOT NULL DEFAULT 10,
  `meta_title` text DEFAULT NULL,
  `meta_tag_description` text DEFAULT NULL,
  `meta_tag_keywords` text DEFAULT NULL,
  `product_tag` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `image`, `price`, `variations`, `tax`, `model`, `sku`, `upc`, `ena`, `jan`, `isbn`, `mpn`, `status`, `created_at`, `updated_at`, `location`, `substrack_stock`, `out_of_stock_status`, `requires_shipping`, `product_mark`, `product_type`, `date_available`, `d_length`, `d_width`, `d_height`, `length_class`, `weight`, `weight_class`, `sort_order`, `attributes`, `manufacturer`, `filters`, `stores`, `downloads`, `releted_product`, `category_ids`, `choice_options`, `discount`, `discount_type`, `tax_type`, `unit`, `total_stock`, `capacity`, `daily_needs`, `popularity_count`, `is_featured`, `view_count`, `maximum_order_quantity`, `meta_title`, `meta_tag_description`, `meta_tag_keywords`, `product_tag`) VALUES
(1, 'Iceberg lettuce', 'Iceberg lettuce is a great bridge food for people who don\'t eat enough other vegetables', '[\"2023-10-10-6525481e1b0b1.png\"]', 35, '[]', '5.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-10-09 13:51:02', '2023-10-11 13:00:13', NULL, 'yes', 'in stock', NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, '[]', NULL, NULL, NULL, NULL, NULL, '[{\"id\":\"1\",\"position\":1},{\"id\":\"4\",\"position\":2}]', '[]', '3.00', 'percent', 'percent', 'kg', 50, '1.00', 1, 0, 0, 2, 5, NULL, NULL, NULL, NULL),
(2, 'Butterhead lettuce', 'Butterhead lettuce is a soft, tasty lettuce variation that gets its name thanks to its buttery flavor!', '[\"2023-10-10-65254837d8113.png\"]', 30, '[{\"type\":\"30\",\"price\":30,\"stock\":100}]', '5.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-10-09 13:52:42', '2023-10-12 11:42:53', NULL, 'yes', 'in stock', NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, '[\"3\",\"1\"]', NULL, NULL, NULL, NULL, NULL, '[{\"id\":\"1\",\"position\":1},{\"id\":\"4\",\"position\":2}]', '[{\"name\":\"choice_3\",\"title\":\"asoft,fatty,vasculartissueintheinteriorcavitiesofbonesthatisamajorsiteofbloodcellproduction\",\"options\":[\"30\"]}]', '2.00', 'percent', 'percent', 'kg', 100, '1.00', 1, 0, 0, 2, 10, NULL, NULL, NULL, NULL),
(3, 'Barese', 'This white-stemmed heirloom variety is a dwarf type, only reaching about 9 inches tall at maturity.', '[\"2023-10-10-652547e68a06e.png\"]', 45, '[{\"type\":\"45\",\"price\":45,\"stock\":70}]', '5.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-10-09 13:54:30', '2023-10-09 20:17:34', NULL, 'yes', 'in stock', NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, '[\"2\"]', NULL, NULL, NULL, NULL, NULL, '[{\"id\":\"1\",\"position\":1},{\"id\":\"5\",\"position\":2}]', '[{\"name\":\"choice_2\",\"title\":\"coldhardyandcancerfighting\",\"options\":[\"45\"]}]', '5.00', 'percent', 'percent', 'kg', 70, '1.00', 1, 0, 0, 0, 5, NULL, NULL, NULL, NULL),
(4, 'ordhook Giant', 'Introduced by Burpee in 1934, his mild-flavored cultivar has thick, dark green leaves that are heavily savoyed and quite tender.', '[\"2023-10-10-652547d12ffc0.png\"]', 55, '[{\"type\":\"55\",\"price\":55,\"stock\":70}]', '5.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-10-09 13:56:08', '2023-10-12 11:42:59', NULL, 'yes', 'in stock', NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, '[\"3\"]', NULL, NULL, NULL, NULL, NULL, '[{\"id\":\"1\",\"position\":1},{\"id\":\"5\",\"position\":2}]', '[{\"name\":\"choice_3\",\"title\":\"asoft,fatty,vasculartissueintheinteriorcavitiesofbonesthatisamajorsiteofbloodcellproduction\",\"options\":[\"55\"]}]', '5.00', 'percent', 'percent', 'kg', 70, '1.00', 1, 0, 0, 1, 7, NULL, NULL, NULL, NULL),
(5, 'Red cabbage', 'The red cabbage is a kind of cabbage, also known as Blaukraut after preparation.', '[\"2023-10-10-652547bae1ee4.png\",\"2023-10-10-652547bae2df9.png\"]', 25, '[{\"type\":\"25\",\"price\":25,\"stock\":120}]', '5.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-10-09 13:57:42', '2023-10-09 20:16:50', NULL, 'yes', 'in stock', NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, '[\"2\"]', NULL, NULL, NULL, NULL, NULL, '[{\"id\":\"2\",\"position\":1},{\"id\":\"6\",\"position\":2}]', '[{\"name\":\"choice_2\",\"title\":\"coldhardyandcancerfighting\",\"options\":[\"25\"]}]', '2.00', 'percent', 'percent', 'kg', 120, '1.00', 1, 0, 0, 0, 10, NULL, NULL, NULL, NULL),
(6, 'Pointed white cabbage', 'Pointed cabbage, also known as cone, sweetheart, hispi or sugarloaf cabbage is an F1 hybrid form of cabbage with a tapering shape and large delicate leaves varying in colour from yellowish to blue-green.', '[\"2023-10-10-652547a661f21.png\"]', 20, '[{\"type\":\"20\",\"price\":20,\"stock\":100}]', '4.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-10-09 13:59:02', '2023-10-09 20:16:30', NULL, 'yes', 'in stock', NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, '[\"2\"]', NULL, NULL, NULL, NULL, NULL, '[{\"id\":\"2\",\"position\":1},{\"id\":\"6\",\"position\":2}]', '[{\"name\":\"choice_2\",\"title\":\"coldhardyandcancerfighting\",\"options\":[\"20\"]}]', '2.00', 'percent', 'percent', 'kg', 100, '1.00', 1, 0, 0, 0, 10, NULL, NULL, NULL, NULL),
(7, 'Green Cauliflower', 'This cauliflower is also known as ‘broccoflower’ as it is a cross between broccoli and cauliflower and comes in various varieties.', '[\"2023-10-10-6525479466132.png\"]', 42, '[{\"type\":\"42\",\"price\":42,\"stock\":70}]', '5.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-10-09 14:04:09', '2023-10-09 20:16:12', NULL, 'yes', 'in stock', NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, '[\"3\",\"2\"]', NULL, NULL, NULL, NULL, NULL, '[{\"id\":\"2\",\"position\":1},{\"id\":\"7\",\"position\":2}]', '[{\"name\":\"choice_3\",\"title\":\"asoft,fatty,vasculartissueintheinteriorcavitiesofbonesthatisamajorsiteofbloodcellproduction\",\"options\":[\"42\"]}]', '2.00', 'percent', 'percent', 'kg', 70, '1.00', 1, 1, 0, 0, 5, NULL, NULL, NULL, NULL),
(8, 'White Cauliflower', 'This is one of the common types of cauliflowers that you can see in the vegetable market and is found in abundance in India.', '[\"2023-10-10-652547839213e.png\"]', 35, '[{\"type\":\"35\",\"price\":35,\"stock\":80}]', '5.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-10-09 14:05:26', '2023-10-09 20:15:55', NULL, 'yes', 'in stock', NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, '[\"1\"]', NULL, NULL, NULL, NULL, NULL, '[{\"id\":\"2\",\"position\":1},{\"id\":\"7\",\"position\":2}]', '[{\"name\":\"choice_1\",\"title\":\"highanti-oxidantcapacity\",\"options\":[\"35\"]}]', '3.00', 'percent', 'percent', 'kg', 80, '1.00', 1, 0, 0, 0, 10, NULL, NULL, NULL, NULL),
(9, 'Broccolini', 'Think broccoli but on thinner, longer stalks with smaller heads and a sweeter taste. It’s a cross between broccoli and Chinese broccoli.', '[\"2023-10-10-6525476fa80e7.png\"]', 39, '[{\"type\":\"39\",\"price\":39,\"stock\":50}]', '7.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-10-09 14:07:24', '2023-10-09 20:15:35', NULL, 'yes', 'in stock', NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, '[\"3\"]', NULL, NULL, NULL, NULL, NULL, '[{\"id\":\"2\",\"position\":1},{\"id\":\"8\",\"position\":2}]', '[{\"name\":\"choice_3\",\"title\":\"asoft,fatty,vasculartissueintheinteriorcavitiesofbonesthatisamajorsiteofbloodcellproduction\",\"options\":[\"39\"]}]', '3.00', 'percent', 'percent', 'kg', 50, '1.00', 1, 0, 0, 0, 5, NULL, NULL, NULL, NULL),
(10, 'Romanesco broccoli', 'Think of flower buds that are spikey and exotic looking. That’s romanesco. It’s bitter and crunchy like regular broccoli but a bit earthier.', '[\"2023-10-10-6525475e311bd.png\",\"2023-10-10-6525475e322c3.png\"]', 45, '[{\"type\":\"45\",\"price\":45,\"stock\":50}]', '7.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-10-09 14:08:47', '2023-10-09 20:15:18', NULL, 'yes', 'in stock', NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, '[\"3\"]', NULL, NULL, NULL, NULL, NULL, '[{\"id\":\"2\",\"position\":1},{\"id\":\"8\",\"position\":2}]', '[{\"name\":\"choice_3\",\"title\":\"asoft,fatty,vasculartissueintheinteriorcavitiesofbonesthatisamajorsiteofbloodcellproduction\",\"options\":[\"45\"]}]', '3.00', 'percent', 'percent', 'kg', 50, '1.00', 1, 0, 0, 0, 5, NULL, NULL, NULL, NULL),
(11, 'Turban Squash', 'hese funny-looking pumpkins are a variety of winter squash that can grow up to 5 pounds. They\'re identifiable by their unusual shape—it almost looks like one squash bursting out of another one.', '[\"2023-10-10-652547415c6aa.png\"]', 120, '[{\"type\":\"120\",\"price\":120,\"stock\":30}]', '5.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-10-09 14:10:25', '2023-10-09 20:14:49', NULL, 'yes', 'in stock', NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, '[\"1\"]', NULL, NULL, NULL, NULL, NULL, '[{\"id\":\"3\",\"position\":1},{\"id\":\"9\",\"position\":2}]', '[{\"name\":\"choice_1\",\"title\":\"highanti-oxidantcapacity\",\"options\":[\"120\"]}]', '3.00', 'percent', 'percent', 'kg', 30, '1.00', 1, 0, 0, 0, 5, NULL, NULL, NULL, NULL),
(12, 'Field Trip F1 Hybrid', 'Weighing in at 5 to 7 pounds each, with long, sturdy stems, these orange gourds are perfect for kids to grab and go.', '[\"2023-10-10-65254729189ac.png\"]', 100, '[{\"type\":\"100\",\"price\":100,\"stock\":69}]', '5.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-10-09 14:11:21', '2023-10-09 20:14:25', NULL, 'yes', 'in stock', NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, '[\"1\"]', NULL, NULL, NULL, NULL, NULL, '[{\"id\":\"3\",\"position\":1},{\"id\":\"9\",\"position\":2}]', '[{\"name\":\"choice_1\",\"title\":\"highanti-oxidantcapacity\",\"options\":[\"100\"]}]', '3.00', 'percent', 'percent', 'kg', 69, '1.00', 1, 1, 0, 0, 10, NULL, NULL, NULL, NULL),
(13, 'CLASSIC GREEN ZUCCHINI', 'The classic green zucchini is the most common type of zucchini found in grocery stores. It has a dark green skin with white flesh and is oblong or cylindrical in shape.', '[\"2023-10-10-65254718c306c.png\",\"2023-10-10-65254718c7e52.png\"]', 70, '[{\"type\":\"70\",\"price\":70,\"stock\":70}]', '5.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-10-09 14:13:25', '2023-10-09 20:14:08', NULL, 'yes', 'in stock', NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, '[\"3\"]', NULL, NULL, NULL, NULL, NULL, '[{\"id\":\"3\",\"position\":1},{\"id\":\"10\",\"position\":2}]', '[{\"name\":\"choice_3\",\"title\":\"asoft,fatty,vasculartissueintheinteriorcavitiesofbonesthatisamajorsiteofbloodcellproduction\",\"options\":[\"70\"]}]', '3.00', 'percent', 'percent', 'kg', 70, '1.00', 1, 2, 0, 0, 5, NULL, NULL, NULL, NULL),
(14, 'GADZUKES ZUCCHINI', 'Gadzukes zucchinis are a hybrid variety that is a cross between a zucchini and a cucumber. They are similar in appearance to regular zucchinis, but they have a slightly lighter green color and a more cylindrical shape.', '[\"2023-10-09-65252e49ba1b9.png\"]', 75, '[{\"type\":\"75\",\"price\":75,\"stock\":97}]', '7.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-10-09 14:14:27', '2023-10-09 18:28:17', NULL, 'yes', 'in stock', NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, '[\"3\"]', NULL, NULL, NULL, NULL, NULL, '[{\"id\":\"3\",\"position\":1},{\"id\":\"10\",\"position\":2}]', '[{\"name\":\"choice_3\",\"title\":\"asoft,fatty,vasculartissueintheinteriorcavitiesofbonesthatisamajorsiteofbloodcellproduction\",\"options\":[\"75\"]}]', '5.00', 'percent', 'percent', 'kg', 97, '1.00', 1, 1, 0, 0, 10, NULL, NULL, NULL, NULL),
(15, 'Test122ww', '23d3d3d', '[\"2023-10-17-652e15bb8ac4a.png\"]', 100, '[{\"type\":\"100\",\"price\":100,\"stock\":80}]', '7.00', '121344', '12313', '13131', '12233', '1313131', '131313', '1313', 1, '2023-10-17 05:03:55', '2023-10-17 05:33:31', 'Ahmdabad', 'yes', '2-3 days', 'yes', 'Halal,Vegan', '1', '2023-11-01', NULL, NULL, NULL, 'Millimeter', '45.25', 'Kilogram', 1, '[\"2\"]', NULL, NULL, NULL, NULL, NULL, '[{\"id\":\"1\",\"position\":1},{\"id\":\"5\",\"position\":2}]', '[{\"name\":\"choice_2\",\"title\":\"coldhardyandcancerfighting\",\"options\":[\"100\"]}]', '3.00', 'percent', 'percent', 'kg', 80, '1.00', 1, 0, 0, 0, 10, 'ssssss', 'vrvr\"\"\"\"\"\"\"\"', 'rvrv', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_searched_by_user`
--

DROP TABLE IF EXISTS `product_searched_by_user`;
CREATE TABLE IF NOT EXISTS `product_searched_by_user` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_tag`
--

DROP TABLE IF EXISTS `product_tag`;
CREATE TABLE IF NOT EXISTS `product_tag` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recent_searches`
--

DROP TABLE IF EXISTS `recent_searches`;
CREATE TABLE IF NOT EXISTS `recent_searches` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `comment` mediumtext DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `rating` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `order_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `searched_categories`
--

DROP TABLE IF EXISTS `searched_categories`;
CREATE TABLE IF NOT EXISTS `searched_categories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `recent_search_id` bigint(20) DEFAULT NULL,
  `category_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `searched_data`
--

DROP TABLE IF EXISTS `searched_data`;
CREATE TABLE IF NOT EXISTS `searched_data` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `category_id` bigint(20) DEFAULT NULL,
  `attribute` varchar(255) DEFAULT NULL,
  `attribute_id` bigint(20) DEFAULT NULL,
  `response_data_count` int(11) NOT NULL DEFAULT 0,
  `volume` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `searched_keyword_counts`
--

DROP TABLE IF EXISTS `searched_keyword_counts`;
CREATE TABLE IF NOT EXISTS `searched_keyword_counts` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `recent_search_id` bigint(20) DEFAULT NULL,
  `keyword_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `searched_keyword_users`
--

DROP TABLE IF EXISTS `searched_keyword_users`;
CREATE TABLE IF NOT EXISTS `searched_keyword_users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `recent_search_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `searched_products`
--

DROP TABLE IF EXISTS `searched_products`;
CREATE TABLE IF NOT EXISTS `searched_products` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `recent_search_id` bigint(20) DEFAULT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `social_medias`
--

DROP TABLE IF EXISTS `social_medias`;
CREATE TABLE IF NOT EXISTS `social_medias` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `soft_credentials`
--

DROP TABLE IF EXISTS `soft_credentials`;
CREATE TABLE IF NOT EXISTS `soft_credentials` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(255) DEFAULT NULL,
  `value` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
CREATE TABLE IF NOT EXISTS `tags` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `tag`, `created_at`, `updated_at`) VALUES
(1, 'vegetables.', '2023-10-09 12:59:30', '2023-10-09 12:59:30'),
(2, 'vegetables', '2023-10-09 12:59:33', '2023-10-09 12:59:33'),
(3, '', '2023-10-09 12:59:33', '2023-10-09 12:59:33');

-- --------------------------------------------------------

--
-- Table structure for table `time_slots`
--

DROP TABLE IF EXISTS `time_slots`;
CREATE TABLE IF NOT EXISTS `time_slots` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_slots`
--

INSERT INTO `time_slots` (`id`, `start_time`, `end_time`, `status`, `created_at`, `updated_at`, `date`) VALUES
(1, '10:30:00', '19:30:00', 1, '2021-04-22 04:08:15', '2021-04-22 04:08:15', '2021-04-22'),
(2, '01:37:00', '11:00:00', 1, '2021-04-22 04:08:33', '2021-05-08 04:41:24', '2021-05-08'),
(3, '09:50:00', '23:30:00', 1, '2021-04-22 04:08:55', '2021-07-01 04:34:39', '2021-04-26');

-- --------------------------------------------------------

--
-- Table structure for table `track_deliverymen`
--

DROP TABLE IF EXISTS `track_deliverymen`;
CREATE TABLE IF NOT EXISTS `track_deliverymen` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) DEFAULT NULL,
  `delivery_man_id` bigint(20) DEFAULT NULL,
  `longitude` varchar(20) DEFAULT NULL,
  `latitude` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `translations`
--

DROP TABLE IF EXISTS `translations`;
CREATE TABLE IF NOT EXISTS `translations` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `translationable_type` varchar(255) NOT NULL,
  `translationable_id` bigint(20) UNSIGNED NOT NULL,
  `locale` varchar(255) NOT NULL,
  `key` varchar(255) DEFAULT NULL,
  `value` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `translations_translationable_id_index` (`translationable_id`),
  KEY `translations_locale_index` (`locale`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `translations`
--

INSERT INTO `translations` (`id`, `translationable_type`, `translationable_id`, `locale`, `key`, `value`) VALUES
(1, 'App\\Model\\Category', 3, 'ja', 'name', 'テスト'),
(2, 'App\\Model\\Category', 2, 'ja', 'name', 'テスト'),
(3, 'App\\Model\\Product', 14, 'ja', 'name', 'テスト'),
(4, 'App\\Model\\Product', 14, 'ja', 'description', 'テスト'),
(5, 'App\\Model\\Product', 15, 'ja', 'name', 'vfvr'),
(6, 'App\\Model\\Product', 15, 'ja', 'description', 'vrvr');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `f_name` varchar(100) DEFAULT NULL,
  `l_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `is_phone_verified` tinyint(1) NOT NULL DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `email_verification_token` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `is_block` tinyint(1) NOT NULL DEFAULT 0,
  `cm_firebase_token` varchar(255) DEFAULT NULL,
  `temporary_token` varchar(255) DEFAULT NULL,
  `login_medium` varchar(255) NOT NULL DEFAULT 'general',
  `loyalty_point` double NOT NULL DEFAULT 0,
  `wallet_balance` double NOT NULL DEFAULT 0,
  `referral_code` varchar(255) DEFAULT NULL,
  `referred_by` varchar(255) DEFAULT NULL,
  `login_hit_count` tinyint(4) NOT NULL DEFAULT 0,
  `is_temp_blocked` tinyint(1) NOT NULL DEFAULT 0,
  `temp_block_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `f_name`, `l_name`, `email`, `image`, `is_phone_verified`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `email_verification_token`, `phone`, `is_block`, `cm_firebase_token`, `temporary_token`, `login_medium`, `loyalty_point`, `wallet_balance`, `referral_code`, `referred_by`, `login_hit_count`, `is_temp_blocked`, `temp_block_time`) VALUES
(1, 'krunal', 'krunal', 'krunal@silverwebbuzz.com', NULL, 0, NULL, '$2y$10$KyG/.EcrLzN5haXkEYBeIOK4O0O6ufWkG7trjxH7/GkClCHAlJimW', NULL, '2023-10-09 12:45:53', '2023-10-10 11:39:09', NULL, '9874545445', 0, NULL, NULL, 'general', 0, 5000, 'hYaAL4KLYxlmAiI7nhNc', NULL, 0, 0, NULL),
(2, 'Mukesh', 'Mahanto', 'mukesh@silverwebbuzz.com', NULL, 0, NULL, '$2y$10$SL1kD7qq9PmdAaYgMPxYkubFOq7XEusZ38h.kN5Y/7B6R0D.WA7e2', NULL, '2023-10-09 14:23:43', '2023-10-10 11:39:24', NULL, '997744551122', 0, NULL, NULL, 'general', 0, 10000, NULL, NULL, 0, 0, NULL),
(3, 'krunal', 'krunal', 'krunal1@silverwebbuzz.com', NULL, 0, NULL, '$2y$10$NUS/rnLG1WzTohmj/Nr.Qe2KACsIu7jplbVS9SKC/UOfCqmXctzcy', NULL, '2023-10-11 04:25:34', '2023-10-11 04:25:34', NULL, '9874545441', 0, NULL, NULL, 'general', 0, 0, 'szrHt03I0jdpMSAO9qgX', NULL, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `visited_products`
--

DROP TABLE IF EXISTS `visited_products`;
CREATE TABLE IF NOT EXISTS `visited_products` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wallet_transactions`
--

DROP TABLE IF EXISTS `wallet_transactions`;
CREATE TABLE IF NOT EXISTS `wallet_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `credit` decimal(24,2) NOT NULL DEFAULT 0.00,
  `debit` decimal(24,2) NOT NULL DEFAULT 0.00,
  `balance` decimal(24,2) NOT NULL DEFAULT 0.00,
  `transaction_type` varchar(191) DEFAULT NULL,
  `reference` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wallet_transactions`
--

INSERT INTO `wallet_transactions` (`id`, `user_id`, `transaction_id`, `credit`, `debit`, `balance`, `transaction_type`, `reference`, `created_at`, `updated_at`) VALUES
(1, 1, 'MbaGv0yEJquknXe35LSeRvTLtdIW6i', '5000.00', '0.00', '5000.00', 'add_fund_by_admin', NULL, '2023-10-10 11:39:09', '2023-10-10 11:39:09'),
(2, 2, 'lAPuph5wELKMRrJJbeXd9E6RONhAai', '10000.00', '0.00', '10000.00', 'add_fund_by_admin', NULL, '2023-10-10 11:39:24', '2023-10-10 11:39:24');

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

DROP TABLE IF EXISTS `wishlists`;
CREATE TABLE IF NOT EXISTS `wishlists` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
