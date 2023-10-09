-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 09, 2023 at 04:28 AM
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
-- Database: `grofresh`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `f_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `l_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `identity_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `identity_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `identity_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `fcm_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `admin_role_id` bigint NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `f_name`, `l_name`, `phone`, `email`, `identity_image`, `identity_type`, `identity_number`, `image`, `password`, `remember_token`, `created_at`, `updated_at`, `fcm_token`, `status`, `admin_role_id`) VALUES
(1, 'John', 'Doe', '917984843285', 'admin@gmail.com', NULL, NULL, NULL, NULL, '$2a$12$qZD96.3umtjeHOfzZ1e1oe/mYh38c54buKV5z7cD7F16rZvb6pbLq', NULL, '2023-10-03 12:56:42', '2023-10-03 12:56:42', NULL, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `admin_roles`
--

DROP TABLE IF EXISTS `admin_roles`;
CREATE TABLE IF NOT EXISTS `admin_roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `module_access` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_roles`
--

INSERT INTO `admin_roles` (`id`, `name`, `module_access`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Master Admin', NULL, 1, '2022-06-17 16:45:59', '2022-06-17 16:45:59');

-- --------------------------------------------------------

--
-- Table structure for table `attributes`
--

DROP TABLE IF EXISTS `attributes`;
CREATE TABLE IF NOT EXISTS `attributes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
CREATE TABLE IF NOT EXISTS `banners` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_id` bigint DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` bigint DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

DROP TABLE IF EXISTS `branches`;
CREATE TABLE IF NOT EXISTS `branches` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `restaurant_id` bigint DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `coverage` int NOT NULL DEFAULT '1',
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `restaurant_id`, `name`, `email`, `phone`, `password`, `latitude`, `longitude`, `address`, `status`, `created_at`, `updated_at`, `coverage`, `remember_token`, `image`) VALUES
(1, NULL, 'Dinajpur', 'newb@gmail.com', NULL, '$2y$10$HJW/gFTjbpX8VIDp4I.EjO0QhlFTek7ydjRMkIiDjSuCHEpWZDafK', '22.848823', '91.390306', 'Hazi osman gani lane', 1, '2021-02-24 09:45:49', '2021-07-06 03:09:00', 500, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `business_settings`
--

DROP TABLE IF EXISTS `business_settings`;
CREATE TABLE IF NOT EXISTS `business_settings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
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
(44, 'country', 'BD', NULL, NULL),
(45, 'language', '[{\"id\":1,\"name\":\"en\",\"direction\":\"ltr\",\"code\":\"en\",\"status\":1,\"default\":true}]', NULL, '2022-06-17 16:45:59'),
(46, 'time_zone', 'Pacific/Midway', NULL, NULL),
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
(65, 'wallet_status', '0', NULL, NULL),
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
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint NOT NULL,
  `position` int NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'def.png',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_discounts`
--

DROP TABLE IF EXISTS `category_discounts`;
CREATE TABLE IF NOT EXISTS `category_discounts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int NOT NULL,
  `start_date` date DEFAULT NULL,
  `expire_date` date DEFAULT NULL,
  `discount_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_amount` double(8,2) NOT NULL DEFAULT '0.00',
  `maximum_amount` double(8,2) NOT NULL DEFAULT '0.00',
  `status` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_discounts_category_id_unique` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_searched_by_user`
--

DROP TABLE IF EXISTS `category_searched_by_user`;
CREATE TABLE IF NOT EXISTS `category_searched_by_user` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint DEFAULT NULL,
  `category_id` bigint DEFAULT NULL,
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
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `reply` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `checked` tinyint(1) NOT NULL DEFAULT '0',
  `image` text COLLATE utf8mb4_unicode_ci,
  `is_reply` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `expire_date` date DEFAULT NULL,
  `min_purchase` decimal(8,2) NOT NULL DEFAULT '0.00',
  `max_discount` decimal(8,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(8,2) NOT NULL DEFAULT '0.00',
  `discount_type` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'percentage',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `coupon_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  `limit` int DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

DROP TABLE IF EXISTS `currencies`;
CREATE TABLE IF NOT EXISTS `currencies` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `address_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `contact_person_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `road` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `house` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `floor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dc_conversations`
--

DROP TABLE IF EXISTS `dc_conversations`;
CREATE TABLE IF NOT EXISTS `dc_conversations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint NOT NULL,
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
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint DEFAULT NULL,
  `deliveryman_id` bigint DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `longitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `f_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `l_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `identity_number` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `identity_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `identity_image` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `auth_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fcm_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` bigint NOT NULL DEFAULT '1',
  `application_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'approved' COMMENT 'pending, approved, denied',
  `login_hit_count` tinyint NOT NULL DEFAULT '0',
  `is_temp_blocked` tinyint(1) NOT NULL DEFAULT '0',
  `temp_block_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `delivery_men_phone_unique` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `d_m_reviews`
--

DROP TABLE IF EXISTS `d_m_reviews`;
CREATE TABLE IF NOT EXISTS `d_m_reviews` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `delivery_man_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `order_id` bigint NOT NULL,
  `comment` mediumtext COLLATE utf8mb4_unicode_ci,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating` int NOT NULL DEFAULT '0',
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
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `otp_hit_count` tinyint NOT NULL DEFAULT '0',
  `is_temp_blocked` tinyint(1) NOT NULL DEFAULT '0',
  `temp_block_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorite_products`
--

DROP TABLE IF EXISTS `favorite_products`;
CREATE TABLE IF NOT EXISTS `favorite_products` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `product_id` bigint NOT NULL,
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
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `deal_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `featured` tinyint NOT NULL DEFAULT '0',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `flash_deal_products`
--

DROP TABLE IF EXISTS `flash_deal_products`;
CREATE TABLE IF NOT EXISTS `flash_deal_products` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `flash_deal_id` bigint DEFAULT NULL,
  `product_id` bigint DEFAULT NULL,
  `discount` double(8,2) NOT NULL DEFAULT '0.00',
  `discount_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint DEFAULT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `credit` decimal(24,2) NOT NULL DEFAULT '0.00',
  `debit` decimal(24,2) NOT NULL DEFAULT '0.00',
  `balance` decimal(24,2) NOT NULL DEFAULT '0.00',
  `transaction_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `conversation_id` bigint NOT NULL,
  `customer_id` bigint DEFAULT NULL,
  `deliveryman_id` bigint DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `attachment` text COLLATE utf8mb4_unicode_ci,
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
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
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
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

DROP TABLE IF EXISTS `oauth_access_tokens`;
CREATE TABLE IF NOT EXISTS `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
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
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
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
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Laravel Personal Access Client', 'z5ky1aF18fNgAczYo0W2MdglqUxpyt0KsKdmbRIt', NULL, 'http://localhost', 1, 0, 0, '2021-01-05 18:07:29', '2021-01-05 18:07:29'),
(2, NULL, 'Laravel Password Grant Client', 'clk5DEe0ANVDYwD79OUYBkLcZ6CLSykUvULubUuk', 'users', 'http://localhost', 0, 1, 0, '2021-01-05 18:07:29', '2021-01-05 18:07:29'),
(3, NULL, 'Laravel Personal Access Client', 'v89pXMpj0Pv49vFb3vC0uqTZvTRPEGtso4wpvkab', NULL, 'http://localhost', 1, 0, 0, '2021-06-19 03:35:33', '2021-06-19 03:35:33'),
(4, NULL, 'Laravel Password Grant Client', '07Q6Fu6riULXZnYy1yd8lApmsn45TrZZyZKPxW3T', 'users', 'http://localhost', 0, 1, 0, '2021-06-19 03:35:33', '2021-06-19 03:35:33');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

DROP TABLE IF EXISTS `oauth_personal_access_clients`;
CREATE TABLE IF NOT EXISTS `oauth_personal_access_clients` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2021-01-05 18:07:29', '2021-01-05 18:07:29'),
(2, 3, '2021-06-19 03:35:33', '2021-06-19 03:35:33');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

DROP TABLE IF EXISTS `oauth_refresh_tokens`;
CREATE TABLE IF NOT EXISTS `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint DEFAULT NULL,
  `order_amount` decimal(8,2) NOT NULL DEFAULT '0.00',
  `coupon_discount_amount` decimal(8,2) NOT NULL DEFAULT '0.00',
  `coupon_discount_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `order_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `total_tax_amount` decimal(8,2) NOT NULL DEFAULT '0.00',
  `payment_method` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_reference` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_address_id` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `checked` tinyint(1) NOT NULL DEFAULT '0',
  `delivery_man_id` bigint DEFAULT NULL,
  `delivery_charge` decimal(8,2) NOT NULL DEFAULT '0.00',
  `order_note` text COLLATE utf8mb4_unicode_ci,
  `coupon_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'delivery',
  `branch_id` bigint NOT NULL DEFAULT '1',
  `time_slot_id` bigint DEFAULT NULL,
  `date` date DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `callback` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extra_discount` decimal(8,2) NOT NULL DEFAULT '0.00',
  `delivery_address` text COLLATE utf8mb4_unicode_ci,
  `payment_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `free_delivery_amount` double(8,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_delivery_histories`
--

DROP TABLE IF EXISTS `order_delivery_histories`;
CREATE TABLE IF NOT EXISTS `order_delivery_histories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint DEFAULT NULL,
  `delivery_man_id` bigint DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `start_location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `end_location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint DEFAULT NULL,
  `order_id` bigint DEFAULT NULL,
  `price` decimal(8,2) NOT NULL DEFAULT '0.00',
  `product_details` text COLLATE utf8mb4_unicode_ci,
  `variation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_on_product` decimal(8,2) DEFAULT NULL,
  `discount_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'amount',
  `quantity` int NOT NULL DEFAULT '1',
  `tax_amount` decimal(8,2) NOT NULL DEFAULT '1.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `variant` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pc',
  `is_stock_decreased` tinyint(1) NOT NULL DEFAULT '1',
  `time_slot_id` bigint DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `vat_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'excluded' COMMENT 'included/excluded',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email_or_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `otp_hit_count` tinyint NOT NULL DEFAULT '0',
  `is_temp_blocked` tinyint(1) NOT NULL DEFAULT '0',
  `temp_block_time` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email_or_phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phone_verifications`
--

DROP TABLE IF EXISTS `phone_verifications`;
CREATE TABLE IF NOT EXISTS `phone_verifications` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `otp_hit_count` tinyint NOT NULL DEFAULT '0',
  `is_temp_blocked` tinyint(1) NOT NULL DEFAULT '0',
  `temp_block_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` text COLLATE utf8mb4_unicode_ci,
  `price` double NOT NULL DEFAULT '0',
  `variations` text COLLATE utf8mb4_unicode_ci,
  `tax` decimal(8,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `attributes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_ids` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `choice_options` text COLLATE utf8mb4_unicode_ci,
  `discount` decimal(8,2) NOT NULL DEFAULT '0.00',
  `discount_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'percent',
  `tax_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'percent',
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pc',
  `total_stock` bigint NOT NULL DEFAULT '0',
  `capacity` decimal(8,2) DEFAULT NULL,
  `daily_needs` tinyint(1) NOT NULL DEFAULT '0',
  `popularity_count` int NOT NULL DEFAULT '0',
  `is_featured` tinyint NOT NULL DEFAULT '0',
  `view_count` int NOT NULL DEFAULT '0',
  `maximum_order_quantity` int NOT NULL DEFAULT '10',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_searched_by_user`
--

DROP TABLE IF EXISTS `product_searched_by_user`;
CREATE TABLE IF NOT EXISTS `product_searched_by_user` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint DEFAULT NULL,
  `product_id` bigint DEFAULT NULL,
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
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint UNSIGNED NOT NULL,
  `tag_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recent_searches`
--

DROP TABLE IF EXISTS `recent_searches`;
CREATE TABLE IF NOT EXISTS `recent_searches` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `comment` mediumtext COLLATE utf8mb4_unicode_ci,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `order_id` bigint DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `searched_categories`
--

DROP TABLE IF EXISTS `searched_categories`;
CREATE TABLE IF NOT EXISTS `searched_categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `recent_search_id` bigint DEFAULT NULL,
  `category_id` bigint DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
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
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `category_id` bigint DEFAULT NULL,
  `attribute` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attribute_id` bigint DEFAULT NULL,
  `response_data_count` int NOT NULL DEFAULT '0',
  `volume` int NOT NULL DEFAULT '0',
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
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `recent_search_id` bigint DEFAULT NULL,
  `keyword_count` int NOT NULL DEFAULT '0',
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
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `recent_search_id` bigint DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
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
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `recent_search_id` bigint DEFAULT NULL,
  `product_id` bigint DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
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
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
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
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` longtext COLLATE utf8mb4_unicode_ci,
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
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `time_slots`
--

DROP TABLE IF EXISTS `time_slots`;
CREATE TABLE IF NOT EXISTS `time_slots` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
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
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint DEFAULT NULL,
  `delivery_man_id` bigint DEFAULT NULL,
  `longitude` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `translationable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `translationable_id` bigint UNSIGNED NOT NULL,
  `locale` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `translations_translationable_id_index` (`translationable_id`),
  KEY `translations_locale_index` (`locale`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `f_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `l_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_phone_verified` tinyint(1) NOT NULL DEFAULT '0',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `email_verification_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_block` tinyint(1) NOT NULL DEFAULT '0',
  `cm_firebase_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `temporary_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_medium` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `loyalty_point` double NOT NULL DEFAULT '0',
  `wallet_balance` double NOT NULL DEFAULT '0',
  `referral_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referred_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_hit_count` tinyint NOT NULL DEFAULT '0',
  `is_temp_blocked` tinyint(1) NOT NULL DEFAULT '0',
  `temp_block_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visited_products`
--

DROP TABLE IF EXISTS `visited_products`;
CREATE TABLE IF NOT EXISTS `visited_products` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint DEFAULT NULL,
  `product_id` bigint DEFAULT NULL,
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
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint DEFAULT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `credit` decimal(24,2) NOT NULL DEFAULT '0.00',
  `debit` decimal(24,2) NOT NULL DEFAULT '0.00',
  `balance` decimal(24,2) NOT NULL DEFAULT '0.00',
  `transaction_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

DROP TABLE IF EXISTS `wishlists`;
CREATE TABLE IF NOT EXISTS `wishlists` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `product_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
