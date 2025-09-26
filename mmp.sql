-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 26, 2025 at 02:34 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mmp`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `logo`, `status`, `description`, `created_at`, `updated_at`) VALUES
(2, 'asd', NULL, 'inactive', 'asdad', '2025-09-26 07:01:14', '2025-09-26 07:01:14');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` text NOT NULL DEFAULT 'active',
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `logo` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `status`, `parent_id`, `logo`, `created_at`, `updated_at`) VALUES
(5, 'Noman Khan', 'noman-khan', 'asdasd', 'active', NULL, NULL, '2025-09-26 06:25:41', '2025-09-26 06:37:17'),
(6, 'zxc', 'zxc', 'zxc', 'active', NULL, NULL, '2025-09-26 06:34:02', '2025-09-26 06:34:02'),
(9, 'asd', 'asd', 'xcbf', 'active', NULL, NULL, '2025-09-26 07:27:05', '2025-09-26 07:33:52'),
(10, 'asdasdasdsd', 'asdasdasdsd', 'asdasd', 'active', 9, NULL, '2025-09-26 07:29:55', '2025-09-26 07:29:55');

-- --------------------------------------------------------

--
-- Table structure for table `cross_references`
--

CREATE TABLE `cross_references` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `reference_number` varchar(255) NOT NULL,
  `reference_brand` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_roles_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000001_create_users_table', 1),
(4, '0001_01_01_000002_create_jobs_table', 1),
(7, '2025_09_25_215144_create_products_table', 3),
(8, '2025_09_25_215145_create_product_images_table', 4),
(9, '2025_09_25_215146_create_oe_numbers_table', 4),
(10, '2025_09_25_215146_create_product_fitments_table', 4),
(11, '2025_09_25_215147_create_cross_references_table', 4),
(12, '2025_09_25_215148_create_stock_batches_table', 4),
(13, '2025_09_25_215148_create_stock_movements_table', 4),
(15, '2025_09_25_215144_create_categories_table', 5),
(16, '2025_09_25_215144_create_brands_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `oe_numbers`
--

CREATE TABLE `oe_numbers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `oe_number` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sku` varchar(255) NOT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit` varchar(255) NOT NULL DEFAULT 'pcs',
  `reorder_level` int(11) NOT NULL DEFAULT 0,
  `is_special_order` tinyint(1) NOT NULL DEFAULT 0,
  `default_purchase_price` decimal(12,2) DEFAULT NULL,
  `default_sale_price` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_fitments`
--

CREATE TABLE `product_fitments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_make` varchar(255) NOT NULL,
  `vehicle_model` varchar(255) NOT NULL,
  `year_range` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Owner', '2025-09-25 05:03:45', '2025-09-25 05:03:45'),
(2, 'Manager', '2025-09-25 05:03:45', '2025-09-25 05:03:45'),
(3, 'Staff', '2025-09-25 05:03:45', '2025-09-25 05:03:45');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('H7wumWAlGUYcvFPBqrz2t9syK0zPYhiSlm8ClFpo', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoidVlqY0IwVElJZFZtNjRlRFlKNUI1YTlmOVMxcU1ZaEJLMU1wb29PYSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO31zOjM6InVybCI7YTowOnt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9', 1758846873);

-- --------------------------------------------------------

--
-- Table structure for table `stock_batches`
--

CREATE TABLE `stock_batches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity_received` int(11) NOT NULL,
  `quantity_remaining` int(11) NOT NULL,
  `cost_price` decimal(12,2) NOT NULL,
  `sale_price` decimal(12,2) DEFAULT NULL,
  `received_at` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `stock_batch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` enum('in','out','adjustment') NOT NULL,
  `quantity` int(11) NOT NULL,
  `reference_type` varchar(255) NOT NULL,
  `reference_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `avatar` longtext DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `two_factor_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `two_factor_code` varchar(255) DEFAULT NULL,
  `two_factor_expires_at` datetime DEFAULT NULL,
  `force_password_change` tinyint(1) NOT NULL DEFAULT 1,
  `two_factor_attempts` int(11) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `status` text NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `avatar`, `email_verified_at`, `password`, `role_id`, `two_factor_enabled`, `two_factor_code`, `two_factor_expires_at`, `force_password_change`, `two_factor_attempts`, `remember_token`, `status`, `created_at`, `updated_at`) VALUES
(1, 'System Owner', 'owner@mmp.com', NULL, NULL, '$2y$12$XRhfe97bHTuYfj.sAiNNtemvSdQtehn8gULzJXozIERxi6qmG/NSG', 1, 0, NULL, NULL, 0, 0, NULL, 'active', '2025-09-25 05:03:45', '2025-09-25 06:19:25'),
(3, 'Noman Khan', 'naumankhankhalid@gmail.com', NULL, NULL, '$2y$12$8cRVuWJCcq4c0W8RzoV4tuHan7Vt8KpHgEU9sBj/mVNJV73KKZndO', 3, 0, NULL, NULL, 0, 0, NULL, 'active', '2025-09-25 06:57:46', '2025-09-26 04:16:09'),
(64, 'User 1', 'user1@example.com', NULL, '2025-09-25 07:08:14', '$2y$12$O2NPiAL9BaJjGb1o0EkFye40d.CHrt7L.cOwHyyu2rOW3grQF1j66', 3, 0, NULL, NULL, 0, 0, 'TcHphVfCuW', 'active', '2025-09-25 07:08:14', '2025-09-25 07:08:14'),
(65, 'User 2', 'user2@example.com', NULL, '2025-09-25 07:08:15', '$2y$12$HliQxSd4EFoKt3FxjqgSPubqWYnsyTQf2vPgBljMWPP4OV6y.flIO', 1, 0, NULL, NULL, 0, 0, 'L8raSVuWcl', 'active', '2025-09-25 07:08:15', '2025-09-25 07:08:15'),
(66, 'User 3', 'user3@example.com', NULL, '2025-09-25 07:08:15', '$2y$12$PqmtlVa/jb6UoQAZolNWseQie7NCwBfyAPkTsz2NO3c1/CvxiRw/.', 1, 0, NULL, NULL, 0, 0, 'Px9jzAtclG', 'active', '2025-09-25 07:08:15', '2025-09-25 07:08:15'),
(67, 'User 4', 'user4@example.com', NULL, '2025-09-25 07:08:15', '$2y$12$ehbZRpEbYHK9wI1jP1dpLuHj0ISmIfLdEIENOBGIRRb7kJAyjqyzK', 3, 0, NULL, NULL, 0, 0, 'MS2L7cXyox', 'active', '2025-09-25 07:08:16', '2025-09-25 07:08:16'),
(68, 'User 5', 'user5@example.com', NULL, '2025-09-25 07:08:16', '$2y$12$.DUw/oJGU0fJbDZ.CEnsye6/PtaqDOcg.7auDOI7eisRIbDLw6GtC', 2, 0, NULL, NULL, 0, 0, 'vGUOn0d3dd', 'active', '2025-09-25 07:08:16', '2025-09-26 04:28:33'),
(69, 'User 6', 'user6@example.com', NULL, '2025-09-25 07:08:16', '$2y$12$ggFZD5NL1yzwIexCpqOh7u0VdYqhuQZ0TeSyCbD.xjLxUC5Zjh.8q', 2, 0, NULL, NULL, 0, 0, 'xsj7TZWSCA', 'active', '2025-09-25 07:08:17', '2025-09-25 07:08:17'),
(70, 'User 7', 'user7@example.com', NULL, '2025-09-25 07:08:17', '$2y$12$u0s1XCIx0JSNBacF65jIN.9Cb/6fJJdNwX.bYCtEmZRZJPQJvJs/C', 1, 0, NULL, NULL, 0, 0, 'tPjYvei464', 'active', '2025-09-25 07:08:17', '2025-09-25 07:08:17'),
(71, 'User 8', 'user8@example.com', NULL, '2025-09-25 07:08:17', '$2y$12$zb4aSrjbSyxiuOI35MElDeOEl2CV/YJKvDS/g/Af7JUKDOzyFaA3W', 1, 0, NULL, NULL, 0, 0, '9ACNJT0tgj', 'active', '2025-09-25 07:08:17', '2025-09-25 07:08:17'),
(72, 'User 9', 'user9@example.com', NULL, '2025-09-25 07:08:17', '$2y$12$DXs4WfSmI46C6A7sgqM2jObXoYQMWb0CkFe4dkjuXK3np0JKdboJK', 1, 0, NULL, NULL, 0, 0, 'k0CMSGZWd8', 'active', '2025-09-25 07:08:18', '2025-09-25 07:08:18'),
(73, 'User 10', 'user10@example.com', NULL, '2025-09-25 07:08:18', '$2y$12$5uxP/8IIbgA9JUGYPTNlz.OrGiDLriXCwVc0QFRrv.hVVZUkTi.fa', 3, 0, NULL, NULL, 0, 0, 'GNhR5DRC7l', 'active', '2025-09-25 07:08:18', '2025-09-25 07:08:18'),
(74, 'User 11', 'user11@example.com', NULL, '2025-09-25 07:08:18', '$2y$12$5oHL4k.CV3WNDFgxNycJz.uI.p6fcio0V2hJ782SYWlbvYrmurKLi', 1, 0, NULL, NULL, 0, 0, '44qqnBDYVi', 'active', '2025-09-25 07:08:18', '2025-09-25 07:08:18'),
(75, 'User 12', 'user12@example.com', NULL, '2025-09-25 07:08:18', '$2y$12$w8az4Pa56nhOvVis//eknujcREu3fNq9zSgok7mHOEgPThJ6Ys3fa', 2, 0, NULL, NULL, 0, 0, 'Kp1SqIKf9a', 'active', '2025-09-25 07:08:19', '2025-09-25 07:08:19'),
(76, 'User 13', 'user13@example.com', NULL, '2025-09-25 07:08:19', '$2y$12$.wbelZT.k9l5UV0sw9FTRe4/c/r0SDD3BneO8K9j3CFMcovJttFbu', 2, 0, NULL, NULL, 0, 0, 'LXrWvOIx5Z', 'active', '2025-09-25 07:08:19', '2025-09-25 07:08:19'),
(77, 'User 14', 'user14@example.com', NULL, '2025-09-25 07:08:19', '$2y$12$vfJPbKREX7DQoeZ/zMtr2eWNABMgjauFdDPbI.VUKaMCtn1.FqR8a', 1, 0, NULL, NULL, 0, 0, 'zYup139Ims', 'active', '2025-09-25 07:08:19', '2025-09-25 07:08:19'),
(78, 'User 15', 'user15@example.com', NULL, '2025-09-25 07:08:19', '$2y$12$uHdYIkh/CONayzw5qmVqn.7V8xOLfUnwDfvDBLDScuIWteWnUhPoW', 2, 0, NULL, NULL, 0, 0, 'efDbHnZqTf', 'active', '2025-09-25 07:08:20', '2025-09-25 07:08:20'),
(79, 'User 16', 'user16@example.com', NULL, '2025-09-25 07:08:20', '$2y$12$wBuM5vpgxQJzKyQY3KLhReKRdBNcRTo8lWTOjXKsG6wssAU7vw06q', 2, 0, NULL, NULL, 0, 0, 'WrFp2EKgxX', 'active', '2025-09-25 07:08:20', '2025-09-25 07:08:20'),
(80, 'User 17', 'user17@example.com', NULL, '2025-09-25 07:08:20', '$2y$12$Cm6leR5aVF1xvqrOZN7xFOcTXYqKu/OQA5BLTt2MhaoT9UaViZcoC', 2, 0, NULL, NULL, 0, 0, '61PRpkbHGY', 'active', '2025-09-25 07:08:21', '2025-09-25 07:08:21'),
(81, 'User 18', 'user18@example.com', NULL, '2025-09-25 07:08:21', '$2y$12$c.8SUTchGOQ8lEIWZG2M8O92xO35LN2.uraesQIKL4gqw8e2UiY/.', 2, 0, NULL, NULL, 0, 0, 'AcbCsfv6lD', 'active', '2025-09-25 07:08:21', '2025-09-25 07:08:21'),
(82, 'User 19', 'user19@example.com', NULL, '2025-09-25 07:08:21', '$2y$12$0ebQ9d34PJMSo3PKNJo03eWc9.m9khkaY0jBhCVBO9j57xnRNwzNC', 1, 0, NULL, NULL, 0, 0, 'ryxFHU90v6', 'active', '2025-09-25 07:08:21', '2025-09-25 07:08:21'),
(83, 'User 20', 'user20@example.com', NULL, '2025-09-25 07:08:21', '$2y$12$xWQOthgV86FAAQa4kD0JGODYtLUlE8G7jybweEs7ZaQ1czZI7qE/a', 1, 0, NULL, NULL, 0, 0, '2BXRDp0XCh', 'active', '2025-09-25 07:08:22', '2025-09-25 07:08:22'),
(84, 'User 21', 'user21@example.com', NULL, '2025-09-25 07:08:22', '$2y$12$ke34pek2dz6q3nKRsRlUbe1DCch55BdJLUpA.g8nfnS03PsnmRH4y', 1, 0, NULL, NULL, 0, 0, 'M0dNFdYKNI', 'active', '2025-09-25 07:08:22', '2025-09-25 07:08:22'),
(85, 'User 22', 'user22@example.com', NULL, '2025-09-25 07:08:22', '$2y$12$fbMtgqYWqYL9eO7NaqluKuj8NTPdv3mn6fGLHKDfV4xWZhpoSUDdS', 1, 0, NULL, NULL, 0, 0, 'Jvee3HYx1Q', 'active', '2025-09-25 07:08:22', '2025-09-25 07:08:22'),
(86, 'User 23', 'user23@example.com', NULL, '2025-09-25 07:08:22', '$2y$12$vPYtgLZQ2RYsMw1K96O/ZeARqzlA.1RzqW9SHMs1jqEa9HZg7.cLi', 2, 0, NULL, NULL, 0, 0, '7R3FEOyj7a', 'active', '2025-09-25 07:08:23', '2025-09-25 07:08:23'),
(87, 'User 24', 'user24@example.com', NULL, '2025-09-25 07:08:23', '$2y$12$xjFbhA4bamMTy80ExiN9Ne0ATFiVPufqL9Ha4BG8iwdcquXjlHTfm', 1, 0, NULL, NULL, 0, 0, 'oUPSrPx2Ld', 'active', '2025-09-25 07:08:23', '2025-09-25 07:08:23'),
(88, 'User 25', 'user25@example.com', NULL, '2025-09-25 07:08:23', '$2y$12$5.WBwgsk74paYfwZPTYjQeoEoh.t5D4nwG72kKbpQFie0TrUyR6bO', 2, 0, NULL, NULL, 0, 0, 'X7WkNbSHoa', 'active', '2025-09-25 07:08:23', '2025-09-25 07:08:23'),
(89, 'User 26', 'user26@example.com', NULL, '2025-09-25 07:08:23', '$2y$12$698y3/yoNqxFjIdUdipdNerXZ2I/0rQPpgN6KOH/bNomHZc2834o2', 3, 0, NULL, NULL, 0, 0, 'e4Zu2O8vVx', 'active', '2025-09-25 07:08:24', '2025-09-25 07:08:24'),
(90, 'User 27', 'user27@example.com', NULL, '2025-09-25 07:08:24', '$2y$12$1lkt6OFboY8vhVyUWj.HpuVQ1AFGAeQszJp0Vna7hcHdc3ul5Hb8G', 1, 0, NULL, NULL, 0, 0, 'jDXIaBAP5C', 'active', '2025-09-25 07:08:24', '2025-09-25 07:08:24'),
(91, 'User 28', 'user28@example.com', NULL, '2025-09-25 07:08:24', '$2y$12$ToeRGpNRPWFtSgA4TsCPQO2kFHCrmHiOYxkmYp0MchnhDBFDZX1Le', 1, 0, NULL, NULL, 0, 0, 'ePNqth3WgQ', 'active', '2025-09-25 07:08:24', '2025-09-25 07:08:24'),
(92, 'User 29', 'user29@example.com', NULL, '2025-09-25 07:08:24', '$2y$12$0JttaOS1Z7e5JUbOvQyy6ePwkcq9fodsyv985vk8ZraixUCNjrUAq', 1, 0, NULL, NULL, 0, 0, '863B1gDR2C', 'active', '2025-09-25 07:08:25', '2025-09-25 07:08:25'),
(93, 'User 30', 'user30@example.com', NULL, '2025-09-25 07:08:25', '$2y$12$2bz56G8sKDIwYqQpMTqSOun6Y0GPVo.JRkGU0/KCP3BAVQQBxV9Wu', 1, 0, NULL, NULL, 0, 0, '2VWcL77UXA', 'active', '2025-09-25 07:08:25', '2025-09-25 07:08:25'),
(95, 'User 32', 'user32@example.com', NULL, '2025-09-25 07:08:25', '$2y$12$PVAQhLaykqu9sde/7F4L/ujnjpFBuwdvL1tu5ZYx7ULonbWLoMzF.', 3, 0, NULL, NULL, 0, 0, 'L20iZfaY50', 'active', '2025-09-25 07:08:26', '2025-09-25 07:08:26'),
(96, 'User 33', 'user33@example.com', NULL, '2025-09-25 07:08:26', '$2y$12$ige.aLLesnYp/tC2c6nVt.03.3TgSclyDvw1i5an7USLn9z/yD3Y6', 3, 0, NULL, NULL, 0, 0, 'S3jKpiruAG', 'active', '2025-09-25 07:08:26', '2025-09-25 07:08:26'),
(97, 'User 34', 'user34@example.com', NULL, '2025-09-25 07:08:26', '$2y$12$d5suR1nwKeOA4XI9ChHsGObnEqthteW1CDRV0xJS7mAy164WceXZ2', 2, 0, NULL, NULL, 0, 0, 'xEL7Fcjkla', 'active', '2025-09-25 07:08:27', '2025-09-25 07:08:27'),
(98, 'User 35', 'user35@example.com', NULL, '2025-09-25 07:08:27', '$2y$12$KIXxIApfPad6XzHMJvTb8Oxx9W0Ul8K/LOn.U3j6bXXpJOk7cC1E6', 3, 0, NULL, NULL, 0, 0, 'g74jtUn99j', 'active', '2025-09-25 07:08:27', '2025-09-25 07:08:27'),
(99, 'User 36', 'user36@example.com', NULL, '2025-09-25 07:08:27', '$2y$12$u9As5N/CPAb4dXCdBhqp1OOX54OnpM.JkVJW0ZxggKhvwGBJ1mJAS', 2, 0, NULL, NULL, 0, 0, '1k6G6VTn2C', 'active', '2025-09-25 07:08:27', '2025-09-25 07:08:27'),
(100, 'User 37', 'user37@example.com', NULL, '2025-09-25 07:08:27', '$2y$12$mzaWOtl0A5Sc7HJfGM9U5eI2aoDT3QpG7dph9lXeNGgyWlB/rKcD2', 2, 0, NULL, NULL, 0, 0, 'XAmqOCzGql', 'active', '2025-09-25 07:08:28', '2025-09-25 07:08:28'),
(101, 'User 38', 'user38@example.com', NULL, '2025-09-25 07:08:28', '$2y$12$9HNn3mdhFr6BAQWD6HzQJeC1arSpMjPT.or296A2BqOgYuc9YopgO', 1, 0, NULL, NULL, 0, 0, '0JgmBPls1m', 'active', '2025-09-25 07:08:28', '2025-09-25 07:08:28'),
(102, 'User 39', 'user39@example.com', NULL, '2025-09-25 07:08:28', '$2y$12$8EAhACkuxLEDzWVM7H4GAOkMzJ.Jq6c8xMyJANvVgBh7LtvXiRzU2', 3, 0, NULL, NULL, 0, 0, 'x25FCgvMTf', 'active', '2025-09-25 07:08:28', '2025-09-25 07:08:28'),
(103, 'User 40', 'user40@example.com', NULL, '2025-09-25 07:08:28', '$2y$12$aixiVQcoXLHHqC1g5zb6S.v0C7tNU4LQCcMDK5IQ/rXnibz5mamXi', 2, 0, NULL, NULL, 0, 0, '3M412odQQj', 'active', '2025-09-25 07:08:29', '2025-09-25 07:08:29'),
(104, 'User 41', 'user41@example.com', NULL, '2025-09-25 07:08:29', '$2y$12$lu2O.sZQkhqJAq7aWOCK3erUvppvAblA60pV3a.tjlRI4ZYAIIwMi', 1, 0, NULL, NULL, 0, 0, '7hNzd5t7EP', 'active', '2025-09-25 07:08:29', '2025-09-25 07:08:29'),
(105, 'User 42', 'user42@example.com', NULL, '2025-09-25 07:08:29', '$2y$12$PWxPdlnvvs9r7YsBzSGSmeHH6P2k7TLEXwGNK.U9INHhxOyF11dLG', 3, 0, NULL, NULL, 0, 0, 'KeI6RBbrLo', 'active', '2025-09-25 07:08:30', '2025-09-25 07:08:30'),
(106, 'User 43', 'user43@example.com', NULL, '2025-09-25 07:08:30', '$2y$12$5dnkhzbv1gGSF8vf6d6FDeSkXs1MT1qwjD7kAMUcpROU59U8HTc2i', 3, 0, NULL, NULL, 0, 0, '49wZrWWrbo', 'active', '2025-09-25 07:08:30', '2025-09-25 07:08:30'),
(107, 'User 44', 'user44@example.com', NULL, '2025-09-25 07:08:30', '$2y$12$9QMnQp340EYlZiPFu8eiMe9qnFvqIrYY5cj4n6TjnNqeECOuVdaHG', 3, 0, NULL, NULL, 0, 0, 'ABMweFLqbl', 'active', '2025-09-25 07:08:31', '2025-09-25 07:08:31'),
(108, 'User 45', 'user45@example.com', NULL, '2025-09-25 07:08:31', '$2y$12$LQKWN9xN2rA1dHSBQ2bWD.JrJl7uN9Z5Wlje.96VYSK4v5de/DB46', 3, 0, NULL, NULL, 0, 0, 'uXFTnktHgY', 'active', '2025-09-25 07:08:31', '2025-09-25 07:08:31'),
(109, 'User 46', 'user46@example.com', NULL, '2025-09-25 07:08:31', '$2y$12$IlP13KL4kitIRGgIvFI5Nudofzy3thhZsRY3MNzwC7oK98oTM0nga', 2, 0, NULL, NULL, 0, 0, 'kuIaHetQRT', 'active', '2025-09-25 07:08:31', '2025-09-25 07:08:31'),
(110, 'User 47', 'user47@example.com', NULL, '2025-09-25 07:08:31', '$2y$12$54HgNZ96hHgEQdHUuZi1C.reDwKB0GSyMF8mPsC4yV5NVDSNZStSu', 2, 0, NULL, NULL, 0, 0, 'mhubLqUGws', 'active', '2025-09-25 07:08:32', '2025-09-25 07:08:32'),
(111, 'User 48', 'user48@example.com', NULL, '2025-09-25 07:08:32', '$2y$12$uqUoBMvvBf7Hhsr4Soxp..dUsSjHs6FY/KW.tjREk8DeQlXfXvxi2', 1, 0, NULL, NULL, 0, 0, 'TP8dnzPmHv', 'active', '2025-09-25 07:08:32', '2025-09-25 07:08:32'),
(112, 'User 49', 'user49@example.com', NULL, '2025-09-25 07:08:32', '$2y$12$7gvRGqZ903B.BHW/r9ti5ukbdfwZq100ufePH4t6FygvxRt6TrOr.', 1, 0, NULL, NULL, 0, 0, 'ebJhtsOQFB', 'active', '2025-09-25 07:08:33', '2025-09-25 07:08:33'),
(113, 'User 50', 'user50@example.com', NULL, '2025-09-25 07:08:33', '$2y$12$EJn2fNwXgWfE53UWLdwnYeMP7NiUKEVjEsXw/5yq6xKSz6cwwrD0a', 3, 0, NULL, NULL, 0, 0, 'dEugkcQ30j', 'active', '2025-09-25 07:08:33', '2025-09-25 07:08:33'),
(114, 'User 51', 'user51@example.com', NULL, '2025-09-25 07:08:33', '$2y$12$OfaqOmS44P7tyvjalRMVq.B7CsqrmpNdBPAXkCzAXqBtSC38qXhBW', 3, 0, NULL, NULL, 0, 0, 'Sy3t9GOFY0', 'active', '2025-09-25 07:08:34', '2025-09-25 07:08:34'),
(115, 'User 52', 'user52@example.com', NULL, '2025-09-25 07:08:34', '$2y$12$QfD1OZzkQ5yXcQT1BsiLaOaS36.MrlEkKO61VsaqqHc5zdAzPdugG', 1, 0, NULL, NULL, 0, 0, 'eJ6GJNINHm', 'active', '2025-09-25 07:08:34', '2025-09-25 07:08:34'),
(116, 'User 53', 'user53@example.com', NULL, '2025-09-25 07:08:34', '$2y$12$8odF0oou3yfGn08ewhPsLu9jiEG1FMd44WQJLKNK5tcOEzMzO5rke', 2, 0, NULL, NULL, 0, 0, 'dH5QqYwNQF', 'active', '2025-09-25 07:08:35', '2025-09-25 07:08:35'),
(117, 'User 54', 'user54@example.com', NULL, '2025-09-25 07:08:35', '$2y$12$SJX/Z1zLHS1HkNoxTb203OdHtYzmaXYe39Hi/liSEEfcQHB6XTK6q', 1, 0, NULL, NULL, 0, 0, 'lzAeXS5bCD', 'active', '2025-09-25 07:08:35', '2025-09-25 07:08:35'),
(118, 'User 55', 'user55@example.com', NULL, '2025-09-25 07:08:35', '$2y$12$HwgtWT6vX.GFOTMxeG/l6uXklQj4p4fGmewuRam/GR8pGX3316yuy', 2, 0, NULL, NULL, 0, 0, 'xHbYtm5LRB', 'active', '2025-09-25 07:08:36', '2025-09-25 07:08:36'),
(119, 'User 56', 'user56@example.com', NULL, '2025-09-25 07:08:36', '$2y$12$gizg0JCHI2z3v4IbW9uf4uazLVcO0P/fOL.1jGpmCTEmKmyDWkI.y', 3, 0, NULL, NULL, 0, 0, 'XdG3PZVQ7k', 'active', '2025-09-25 07:08:36', '2025-09-25 07:08:36'),
(120, 'User 57', 'user57@example.com', NULL, '2025-09-25 07:08:36', '$2y$12$M3k5f0sIXb4TEcV4PG0Enu3WvD7rF6t8VlUdlp1QNLmyBun7e/Fpy', 1, 0, NULL, NULL, 0, 0, 'zukTPmKFjH', 'active', '2025-09-25 07:08:37', '2025-09-25 07:08:37'),
(121, 'User 58', 'user58@example.com', NULL, '2025-09-25 07:08:37', '$2y$12$NqnHb3bXOy/a1.kT8eXLnuC7Y4A.AiS63bViYeBoFFyQfrMniQyPy', 1, 0, NULL, NULL, 0, 0, 'QpuRzD0stL', 'active', '2025-09-25 07:08:37', '2025-09-25 07:08:37'),
(122, 'User 59', 'user59@example.com', NULL, '2025-09-25 07:08:37', '$2y$12$CRf8DDwlK6HGPisEowM0O.mWetPLNQAgNA9/DII5JdJF8r3T5zhYe', 2, 0, NULL, NULL, 0, 0, 'y8hXBWpjPn', 'active', '2025-09-25 07:08:38', '2025-09-25 07:08:38'),
(123, 'User 60', 'user60@example.com', NULL, '2025-09-25 07:08:38', '$2y$12$SAwWdoBNGI8q5Bpqp11C..cnlokCjYJh/pLBiAMIfvDI/U9.dBspW', 3, 0, NULL, NULL, 0, 0, 'm4s7cuXDXV', 'active', '2025-09-25 07:08:38', '2025-09-25 07:08:38'),
(124, 'User 61', 'user61@example.com', NULL, '2025-09-25 07:08:38', '$2y$12$2o4iTsU.YFSql42tk8YLg.vEYhForF7deFyJVp539YrV0F2NMZFh2', 1, 0, NULL, NULL, 0, 0, 'qnUa0j0nqR', 'active', '2025-09-25 07:08:39', '2025-09-25 07:08:39'),
(125, 'User 62', 'user62@example.com', NULL, '2025-09-25 07:08:39', '$2y$12$eM0GFNE8wkYBn0UEFXsKsuA1hrzzwdaoZsNu7eAJh8rDCytqh6Wn2', 2, 0, NULL, NULL, 0, 0, '31PWZCGmlq', 'active', '2025-09-25 07:08:39', '2025-09-25 07:08:39'),
(126, 'User 63', 'user63@example.com', NULL, '2025-09-25 07:08:39', '$2y$12$0geOUWm5iP1cAblkB.P2Me46j/TYSkwlMJvS.133QixvXphNVlpWO', 2, 0, NULL, NULL, 0, 0, '8wZ7GJ0UZp', 'active', '2025-09-25 07:08:40', '2025-09-25 07:08:40'),
(127, 'User 64', 'user64@example.com', NULL, '2025-09-25 07:08:40', '$2y$12$BfUMuiVBZbL4hRuDxCTdQuAVDCN5w.K3/LupPdDJJ/6RvN1irB7AS', 3, 0, NULL, NULL, 0, 0, 'nIF8anq1V0', 'active', '2025-09-25 07:08:40', '2025-09-25 07:08:40'),
(128, 'User 65', 'user65@example.com', NULL, '2025-09-25 07:08:40', '$2y$12$L3oq57Bel2BqSUMTfdEdSORbADmzfBksM0qPOjEu/pef4MvysfPLa', 3, 0, NULL, NULL, 0, 0, 'WZFkVoceF7', 'active', '2025-09-25 07:08:41', '2025-09-25 07:08:41'),
(129, 'User 66', 'user66@example.com', NULL, '2025-09-25 07:08:41', '$2y$12$kPfa0RFSQ9zSxgxw7Okt/OChqcGwicE1wLp68j5sBPKXJ2.JrlOau', 1, 0, NULL, NULL, 0, 0, '70YHXs4sZP', 'active', '2025-09-25 07:08:41', '2025-09-25 07:08:41'),
(130, 'User 67', 'user67@example.com', NULL, '2025-09-25 07:08:41', '$2y$12$uC1ZzxSFupPWfiQDBuZXNuVvnRPCIpl3ykMEVCG7L7IqHMQFDw.Ky', 2, 0, NULL, NULL, 0, 0, 'LTfNENZIBG', 'active', '2025-09-25 07:08:42', '2025-09-25 07:08:42'),
(131, 'User 68', 'user68@example.com', NULL, '2025-09-25 07:08:42', '$2y$12$JnUY0wbZQI7HzinosB9m0.fE71mbW0IGtnbMhFabaPKO7isGsNWn6', 3, 0, NULL, NULL, 0, 0, 'Gjt7T0nyRY', 'active', '2025-09-25 07:08:42', '2025-09-25 07:08:42'),
(132, 'User 69', 'user69@example.com', NULL, '2025-09-25 07:08:42', '$2y$12$gYaG7ZuNhUO37Smz/oxK0OIs2oBnoKdlpVgA.5.Qr1ympSUFfWb.O', 1, 0, NULL, NULL, 0, 0, 'ah5MUlL9kO', 'active', '2025-09-25 07:08:43', '2025-09-25 07:08:43'),
(133, 'User 70', 'user70@example.com', NULL, '2025-09-25 07:08:43', '$2y$12$XYKa2vovYMkfxOWhg2x1ZeqEd4UdlfJ59TyZ99YgTUixWyIaMM9i6', 3, 0, NULL, NULL, 0, 0, 'CN4MINEj99', 'active', '2025-09-25 07:08:43', '2025-09-25 07:08:43'),
(134, 'User 71', 'user71@example.com', NULL, '2025-09-25 07:08:43', '$2y$12$WLhQGggLySlWOcMzaRL6p.ukBCt6NAiIBVHyu8mhzZrqlEGGpv.De', 3, 0, NULL, NULL, 0, 0, 'pzHLYItcDS', 'active', '2025-09-25 07:08:43', '2025-09-25 07:08:43'),
(135, 'User 72', 'user72@example.com', NULL, '2025-09-25 07:08:43', '$2y$12$z.Ls0A3aq3GQqliayUJ.JerRABXbVQSOBTuuAIEKyrzG72zeCoMGy', 2, 0, NULL, NULL, 0, 0, 'cBGVUpsoPX', 'active', '2025-09-25 07:08:44', '2025-09-25 07:08:44'),
(136, 'User 73', 'user73@example.com', NULL, '2025-09-25 07:08:44', '$2y$12$hPMWrHtF7QD.CS44E8o4Uusl/gr7rjt/rc8O6wLTpIkhBtNBRzLN2', 3, 0, NULL, NULL, 0, 0, 'nJbBFsA2zp', 'active', '2025-09-25 07:08:44', '2025-09-25 07:08:44'),
(137, 'User 74', 'user74@example.com', NULL, '2025-09-25 07:08:44', '$2y$12$vgzNPj4pGrtL0x6GuwJbnO47mfIB.Kwl8hNTQC4Vnf4R.XN0FDySO', 2, 0, NULL, NULL, 0, 0, '1BsXkSfnV1', 'active', '2025-09-25 07:08:45', '2025-09-25 07:08:45'),
(138, 'User 75', 'user75@example.com', NULL, '2025-09-25 07:08:45', '$2y$12$zlwafSyP8rZKkRchVeue6exyfL/q882k/p8KEcqK8jESPGvsHHwwq', 1, 0, NULL, NULL, 0, 0, 'ORLERg8sWM', 'active', '2025-09-25 07:08:45', '2025-09-25 07:08:45'),
(139, 'User 76', 'user76@example.com', NULL, '2025-09-25 07:08:45', '$2y$12$/EoiaWSmgODjqgCBz/k5yOoRoxvecbeoF8P4epSoSxzcyELTwIMRS', 1, 0, NULL, NULL, 0, 0, 'nJHTWmHiEX', 'active', '2025-09-25 07:08:45', '2025-09-25 07:08:45'),
(140, 'User 77', 'user77@example.com', NULL, '2025-09-25 07:08:45', '$2y$12$jYdxTi.6.YFXH1lnc3I0keUhqS5aaqIFSlk8n8gWJ1xIxmRD4YqzC', 3, 0, NULL, NULL, 0, 0, 'sajfwVnni1', 'active', '2025-09-25 07:08:46', '2025-09-25 07:08:46'),
(141, 'User 78', 'user78@example.com', NULL, '2025-09-25 07:08:46', '$2y$12$pSBhLDFikFIVGhKM.9h4m.HmpiqYkxvKoar7Dca2Fciyx1uKzTpTq', 3, 0, NULL, NULL, 0, 0, 'eAf00mgUEK', 'active', '2025-09-25 07:08:46', '2025-09-25 07:08:46'),
(142, 'User 79', 'user79@example.com', NULL, '2025-09-25 07:08:46', '$2y$12$gWfrroFR2WBTYoz14F/swuN259bF5PD.puE8hFjzUXEy2kPmDcVxy', 3, 0, NULL, NULL, 0, 0, 'oRYbYrrghj', 'active', '2025-09-25 07:08:46', '2025-09-25 07:08:46'),
(143, 'User 80', 'user80@example.com', NULL, '2025-09-25 07:08:46', '$2y$12$FDQQgbKEp4uiH40Lm/12xeZxZ.dS6EnJx2vXpvYuBE07SwZVGSNwy', 2, 0, NULL, NULL, 0, 0, '3TyLWlplnP', 'active', '2025-09-25 07:08:47', '2025-09-25 07:08:47'),
(144, 'User 81', 'user81@example.com', NULL, '2025-09-25 07:08:47', '$2y$12$61k7d0bCTzCa0aNr.14vZu5qw9EXZoCcgg6eQDe5A2NVIzQ17PYVC', 1, 0, NULL, NULL, 0, 0, 'LENLpPeqMw', 'active', '2025-09-25 07:08:47', '2025-09-25 07:08:47'),
(145, 'User 82', 'user82@example.com', NULL, '2025-09-25 07:08:47', '$2y$12$w04JPz4izOz/Wrnk.WDfje58cM8rN9ROFnT1slRqAHs6j.C1ZaKNm', 1, 0, NULL, NULL, 0, 0, 'fJOsxU7e8r', 'active', '2025-09-25 07:08:47', '2025-09-25 07:08:47'),
(146, 'User 83', 'user83@example.com', NULL, '2025-09-25 07:08:47', '$2y$12$Loygwhs6CmCDKRmJRNb4YOJSts6zVeNwbvcHF6HrtziSv9lcK2jbm', 2, 0, NULL, NULL, 0, 0, 'gpfz60NEha', 'active', '2025-09-25 07:08:48', '2025-09-25 07:08:48'),
(147, 'User 84', 'user84@example.com', NULL, '2025-09-25 07:08:48', '$2y$12$sgFBNEdZzKTL159x42JS1Or/U3FP3RA3pkECvbVErB0i3YNOBthju', 2, 0, NULL, NULL, 0, 0, 'kfSXzOEv8N', 'active', '2025-09-25 07:08:48', '2025-09-25 07:08:48'),
(148, 'User 85', 'user85@example.com', NULL, '2025-09-25 07:08:48', '$2y$12$9c6V0oRZoQqkkfrvSuvK6.imme25gMBOmXIIvAC3scRlbiOfCaHT.', 2, 0, NULL, NULL, 0, 0, 'MZlR9mEC1d', 'active', '2025-09-25 07:08:49', '2025-09-25 07:08:49'),
(149, 'User 86', 'user86@example.com', NULL, '2025-09-25 07:08:49', '$2y$12$.RIf/yCuPu8nxVD4bu1T/uEw7j8wzRO7Ve4lkAXpvaih3KOC2eUoK', 2, 0, NULL, NULL, 0, 0, 'H51yz3cqoP', 'active', '2025-09-25 07:08:49', '2025-09-25 07:08:49'),
(150, 'User 87', 'user87@example.com', NULL, '2025-09-25 07:08:49', '$2y$12$GRrFP/aBscM/mYT9lFGIq.ESMY8byzuPzBQRQ712rypFXSsmd/TbK', 1, 0, NULL, NULL, 0, 0, '0Z47ipStL9', 'active', '2025-09-25 07:08:50', '2025-09-25 07:08:50'),
(151, 'User 88', 'user88@example.com', NULL, '2025-09-25 07:08:50', '$2y$12$l0oXCqLNjr21rShTw5DsR.OYs.DXBp38PxxMwVnyrhUZHOhhhx6Em', 1, 0, NULL, NULL, 0, 0, 'jfjcJBoUDQ', 'active', '2025-09-25 07:08:50', '2025-09-25 07:08:50'),
(152, 'User 89', 'user89@example.com', NULL, '2025-09-25 07:08:50', '$2y$12$6AU.AbSoucyf3VYSFVwW0ueqgVqbIpEEKya1N/rnQkqHuDesDVZ/G', 2, 0, NULL, NULL, 0, 0, 'l3jjNajoKp', 'active', '2025-09-25 07:08:51', '2025-09-25 07:08:51'),
(153, 'User 90', 'user90@example.com', NULL, '2025-09-25 07:08:51', '$2y$12$Fvrc2FCzQqSxY/if4ze.M.EjBVKVHpljyN25ACENTCWysCHu2qCGC', 1, 0, NULL, NULL, 0, 0, 'nJDdX7I2rk', 'active', '2025-09-25 07:08:51', '2025-09-25 07:08:51'),
(154, 'User 91', 'user91@example.com', NULL, '2025-09-25 07:08:51', '$2y$12$4py.StqmcH8ClJzun2tmnOkl7IB2sZQU7zsgGHw3ttm9Ka0GC/D8S', 3, 0, NULL, NULL, 0, 0, 'iSE5h98TYz', 'active', '2025-09-25 07:08:51', '2025-09-25 07:08:51'),
(155, 'User 92', 'user92@example.com', NULL, '2025-09-25 07:08:51', '$2y$12$I9VzVnZ9CWUKklqIJM.pfOMCLf4/K2u6I3JfbzKBVSAbTjxXaBUaq', 3, 0, NULL, NULL, 0, 0, 'NQiP0JXKmq', 'active', '2025-09-25 07:08:52', '2025-09-25 07:08:52'),
(156, 'User 93', 'user93@example.com', NULL, '2025-09-25 07:08:52', '$2y$12$LpVW98zEI6OXNER6etzv3uvSjMaZUPVR2g9.GikVOjZ44oJ4kuvVO', 1, 0, NULL, NULL, 0, 0, 'bzAMJkA28o', 'active', '2025-09-25 07:08:52', '2025-09-25 07:08:52'),
(157, 'User 94', 'user94@example.com', NULL, '2025-09-25 07:08:52', '$2y$12$qlWze2UK6mdqGMr1jsO7keyj9IkOEsWamstmKw0dD4XhYxdS6dz7u', 2, 0, NULL, NULL, 0, 0, '8YuugRJ9cu', 'active', '2025-09-25 07:08:53', '2025-09-25 07:08:53'),
(158, 'User 95', 'user95@example.com', NULL, '2025-09-25 07:08:53', '$2y$12$Z698AIHsMzB88GwBtJaBtOYiE9fimbXVIteM6rjWZQNaypoTjF2.i', 1, 0, NULL, NULL, 0, 0, 'YkAt4eZIhw', 'active', '2025-09-25 07:08:53', '2025-09-25 07:08:53'),
(159, 'User 96', 'user96@example.com', NULL, '2025-09-25 07:08:53', '$2y$12$LDzFlE10W2XMbdbq8bChmOoTZGSM9mrpf2qPGMuXroScG9qLu.5TG', 1, 0, NULL, NULL, 0, 0, 'h6tdO9kyYi', 'active', '2025-09-25 07:08:54', '2025-09-25 07:08:54'),
(160, 'User 97', 'user97@example.com', NULL, '2025-09-25 07:08:54', '$2y$12$bYbZUzUUt1EwJMl5bZqbWuu0h1KtIDdEH1g3kD0Qp8TrHTiXty6gq', 2, 0, NULL, NULL, 0, 0, 'LVp4kyUQ4s', 'active', '2025-09-25 07:08:54', '2025-09-25 07:08:54'),
(161, 'User 98', 'user98@example.com', NULL, '2025-09-25 07:08:54', '$2y$12$kilhMXKaSL6MuIidXBa1eel68Y3Tj.bF7HhkOiOK7lAwdIsn7v7ae', 2, 0, NULL, NULL, 0, 0, 'aEDNWynkKr', 'active', '2025-09-25 07:08:54', '2025-09-25 07:08:54'),
(162, 'User 99', 'user99@example.com', NULL, '2025-09-25 07:08:54', '$2y$12$UrlJgMCLZbhbV1WAqSPMrOAU9jXwrgxRmMVbJYp0QxSGEXU1dhiH.', 3, 0, NULL, NULL, 0, 0, '29g1hskGGp', 'active', '2025-09-25 07:08:55', '2025-09-25 07:08:55'),
(163, 'User 100', 'user100@example.com', NULL, '2025-09-25 07:08:55', '$2y$12$tkbzCB.bQPxqFlVZw5Tp2O5nHQN659ms7R7tEunfVHStKgyFYtFL.', 1, 0, NULL, NULL, 0, 0, 'VGab2ET6ux', 'active', '2025-09-25 07:08:55', '2025-09-25 07:08:55'),
(164, 'User 101', 'user101@example.com', NULL, '2025-09-25 07:08:55', '$2y$12$FZKbRdz6tyHtQESN1/IzcOWCuIublz5KeP6.ciyDzOv0le2j1xt.O', 3, 0, NULL, NULL, 0, 0, 'Uo03Pc1YgR', 'active', '2025-09-25 07:08:55', '2025-09-25 07:08:55'),
(165, 'User 102', 'user102@example.com', NULL, '2025-09-25 07:08:55', '$2y$12$OJm1zM3W16Q5aqnT6uLPpOutOVQkx8d5qgKTBVUHzrEpZKEumN.Cq', 2, 0, NULL, NULL, 0, 0, '1A8m2xhmeI', 'active', '2025-09-25 07:08:56', '2025-09-25 07:08:56'),
(166, 'User 103', 'user103@example.com', NULL, '2025-09-25 07:08:56', '$2y$12$cXXW2bszT0SkFtgW/f1Uh.HdLXWJx5ycv5iHyP.zq.ZC8vZxX9a7i', 2, 0, NULL, NULL, 0, 0, 'WqVaiwa9kz', 'active', '2025-09-25 07:08:56', '2025-09-25 07:08:56'),
(167, 'User 104', 'user104@example.com', NULL, '2025-09-25 07:08:56', '$2y$12$8UdtH8isUdsK2pabFXSeeuntuWYkQBfy4C3EFhs4LqWX774RlVbUa', 2, 0, NULL, NULL, 0, 0, 'HGrQqtRQEm', 'active', '2025-09-25 07:08:56', '2025-09-25 07:08:56'),
(168, 'User 105', 'user105@example.com', NULL, '2025-09-25 07:08:57', '$2y$12$aT.Ik.PUg8C1yya2gyL9M.X/vgvWbffmbTGU9RdjEP52ICJRU21.G', 2, 0, NULL, NULL, 0, 0, 'yw1YVvBp9g', 'active', '2025-09-25 07:08:57', '2025-09-25 07:08:57'),
(169, 'User 106', 'user106@example.com', NULL, '2025-09-25 07:08:57', '$2y$12$Sxa06Pz3NmRu0Q1i91i/De/LnELPFXXRgqNg0tnIizm8msa.17pp2', 2, 0, NULL, NULL, 0, 0, 'rgQygeYUqH', 'active', '2025-09-25 07:08:57', '2025-09-25 07:08:57'),
(170, 'User 107', 'user107@example.com', NULL, '2025-09-25 07:08:57', '$2y$12$Hqy6hgWhbyukYJbxa62V3uSlelHk8s.zuQggJBpA8AjIbDL4GSXaG', 1, 0, NULL, NULL, 0, 0, '3CciGjgZJ2', 'active', '2025-09-25 07:08:58', '2025-09-25 07:08:58'),
(171, 'User 108', 'user108@example.com', NULL, '2025-09-25 07:08:58', '$2y$12$QQ8LDld8MqdKhqsNdOEKNeUsLnfRiBeKuRSssgH9XqfUY04SehjJi', 2, 0, NULL, NULL, 0, 0, 'ssyJuvE4yy', 'active', '2025-09-25 07:08:58', '2025-09-25 07:08:58'),
(172, 'User 109', 'user109@example.com', NULL, '2025-09-25 07:08:58', '$2y$12$CLmgf5/MszvgNt6EimaxUe0My4DwI3Za6RQSUCJ52pgJ8WlD950E.', 3, 0, NULL, NULL, 0, 0, 'GPcuAbEb6T', 'active', '2025-09-25 07:08:58', '2025-09-25 07:08:58'),
(173, 'User 110', 'user110@example.com', NULL, '2025-09-25 07:08:58', '$2y$12$rpE/lWIxv8T/ECjsJe6WEuF99uiIoVL4UxyWWMqCgCZkYCxNyPFn2', 2, 0, NULL, NULL, 0, 0, 'dnRTEx2Awf', 'active', '2025-09-25 07:08:59', '2025-09-25 07:08:59'),
(174, 'User 111', 'user111@example.com', NULL, '2025-09-25 07:08:59', '$2y$12$IW8.JbF.qOZPO9WT49Nc2ulYcu9LImwcVHmw0tV6pZ9xQx8kpVHUu', 1, 0, NULL, NULL, 0, 0, 'HJWbaBazk1', 'active', '2025-09-25 07:08:59', '2025-09-25 07:08:59'),
(175, 'User 112', 'user112@example.com', NULL, '2025-09-25 07:08:59', '$2y$12$Y5wVyfp5PjTFTGAGc3nmtuF0gCwKzkYaixKZT5GemAEnvk.eJ5O1a', 2, 0, NULL, NULL, 0, 0, '2QWW2tdfTO', 'active', '2025-09-25 07:08:59', '2025-09-25 07:08:59'),
(176, 'User 113', 'user113@example.com', NULL, '2025-09-25 07:08:59', '$2y$12$eky2FHFiv6HCejBQWBAa3eAyN/ENxEDgDOpkqSHKvvatYB26iteuy', 3, 0, NULL, NULL, 0, 0, 'JNvZRks5kE', 'active', '2025-09-25 07:09:00', '2025-09-25 07:09:00'),
(177, 'User 114', 'user114@example.com', NULL, '2025-09-25 07:09:00', '$2y$12$03f.7iBawGF9ONo0a4H6N.hl3bHJKJqTzshLtDXDV89QP2.rTxgtm', 3, 0, NULL, NULL, 0, 0, 'wkzA8bIrpg', 'active', '2025-09-25 07:09:00', '2025-09-25 07:09:00'),
(178, 'User 115', 'user115@example.com', NULL, '2025-09-25 07:09:00', '$2y$12$SzbMC/3NBC.e1zFcE6n6ueUqiqS9FXfCsg/.BlqkkQNrU15VUGmnC', 2, 0, NULL, NULL, 0, 0, 'HsHHBtEil2', 'active', '2025-09-25 07:09:00', '2025-09-25 07:09:00'),
(179, 'User 116', 'user116@example.com', NULL, '2025-09-25 07:09:00', '$2y$12$aILE7IFwBltZkfhMxiPc3OFGOUXpbTsS7PTJW5QbcBlvzEV6Djjoa', 1, 0, NULL, NULL, 0, 0, 'H1FGt9YN1h', 'active', '2025-09-25 07:09:01', '2025-09-25 07:09:01'),
(180, 'User 117', 'user117@example.com', NULL, '2025-09-25 07:09:01', '$2y$12$tPzr0v2g//ELBj7mamyYLuH6wcc1cTwA9KD3diOprxpcOQQMejWy2', 3, 0, NULL, NULL, 0, 0, 'V4G7EWZ2Hy', 'active', '2025-09-25 07:09:01', '2025-09-25 07:09:01'),
(181, 'User 118', 'user118@example.com', NULL, '2025-09-25 07:09:01', '$2y$12$6P.A1UQQ7LHxfDNqrk1THujvhLg8bJaeSbN4kBiCmKpxUZbWuS3OC', 1, 0, NULL, NULL, 0, 0, 'b8uWj3YGqa', 'active', '2025-09-25 07:09:01', '2025-09-25 07:09:01'),
(182, 'User 119', 'user119@example.com', NULL, '2025-09-25 07:09:01', '$2y$12$8ugMAu0MAkL6LTT8ATkBK.p8RcY3RgCHrRvvpRzeGTejZ2ur3Kv/.', 3, 0, NULL, NULL, 0, 0, '372qvCTA6X', 'active', '2025-09-25 07:09:02', '2025-09-25 07:09:02'),
(183, 'User 120', 'user120@example.com', NULL, '2025-09-25 07:09:02', '$2y$12$9m0BFqtki31aBUCLAj9stOda3o5UvBslpCKxlP5CoHCPw4f7evIRC', 2, 0, NULL, NULL, 0, 0, 'Z90lr2hLSn', 'active', '2025-09-25 07:09:02', '2025-09-25 07:09:02'),
(184, 'User 121', 'user121@example.com', NULL, '2025-09-25 07:09:02', '$2y$12$tB49H0L06L4MXgWcVBVer.jJiEULFCF0wZzUXvyctkk2BfNN.Q8au', 3, 0, NULL, NULL, 0, 0, 'fhP1x0KnAq', 'active', '2025-09-25 07:09:03', '2025-09-25 07:09:03'),
(185, 'User 122', 'user122@example.com', NULL, '2025-09-25 07:09:03', '$2y$12$b49NUioQ.TiwZW2YvIetbuuwtFtH.w63ied1YPYRJpe1WJMzre/Q.', 2, 0, NULL, NULL, 0, 0, 'haWZ6tC5yx', 'active', '2025-09-25 07:09:03', '2025-09-25 07:09:03'),
(186, 'User 123', 'user123@example.com', NULL, '2025-09-25 07:09:03', '$2y$12$fQRNihK45xGofHZQZggNRuzmU7gzi6hte2Wm/IT7OUM265q/vrjpO', 1, 0, NULL, NULL, 0, 0, 'CJ3w1MlKZX', 'active', '2025-09-25 07:09:04', '2025-09-25 07:09:04'),
(187, 'User 124', 'user124@example.com', NULL, '2025-09-25 07:09:04', '$2y$12$UQTxz431ChhyUIpENVLx4.g0kATfwLrvvz3AwL5KBY0CPEhp5ryMK', 3, 0, NULL, NULL, 0, 0, '7jOlO7fzRN', 'active', '2025-09-25 07:09:04', '2025-09-25 07:09:04'),
(188, 'User 125', 'user125@example.com', NULL, '2025-09-25 07:09:04', '$2y$12$aeBt4R2k9eJ3QEsQsAJaWu9BI20WVcVsEEoWXrJ2J5ES1WJSFOGA6', 1, 0, NULL, NULL, 0, 0, 'dEhelXjgYo', 'active', '2025-09-25 07:09:04', '2025-09-25 07:09:04'),
(189, 'User 126', 'user126@example.com', NULL, '2025-09-25 07:09:04', '$2y$12$wM30su819LlDl7Zus.Dll.aI0ElDHNEZtsywunVuFkX.T0jIs9L2C', 2, 0, NULL, NULL, 0, 0, '3hVkOAZ7p3', 'active', '2025-09-25 07:09:05', '2025-09-25 07:09:05'),
(190, 'User 127', 'user127@example.com', NULL, '2025-09-25 07:09:05', '$2y$12$Hx0K/QkObLWRK3U/ld957OTI9c.lVL0Ka4kq75wMHvmJ6ID/w08Yu', 1, 0, NULL, NULL, 0, 0, 'w5ArNjiVAw', 'active', '2025-09-25 07:09:05', '2025-09-25 07:09:05'),
(191, 'User 128', 'user128@example.com', NULL, '2025-09-25 07:09:05', '$2y$12$ovNIXjRkV/WW/2Ip1AGJ7OwRQw3Sv0ou4YQu7dHMdJUz1mc1wODOa', 3, 0, NULL, NULL, 0, 0, 'K3L4WtDNev', 'active', '2025-09-25 07:09:05', '2025-09-25 07:09:05'),
(192, 'User 129', 'user129@example.com', NULL, '2025-09-25 07:09:05', '$2y$12$yO8afGcCb7eW0uuB.w01de8SbnT5jfhRMwTx7ASWZOUs/j8h7W54G', 3, 0, NULL, NULL, 0, 0, 'BTPA55PuX3', 'active', '2025-09-25 07:09:06', '2025-09-25 07:09:06'),
(193, 'User 130', 'user130@example.com', NULL, '2025-09-25 07:09:06', '$2y$12$/HGS.tYBlbPsIsm5d3MM8e8L94FiifoQ/q5l/RWy/aAI2mOk2LKzK', 1, 0, NULL, NULL, 0, 0, 'LyYI2S9AbI', 'active', '2025-09-25 07:09:06', '2025-09-25 07:09:06'),
(194, 'User 131', 'user131@example.com', NULL, '2025-09-25 07:09:06', '$2y$12$EzBLwK8pM9M4lVNIh/2Eaeh5KXbncd16bBweJj6NaV0ZgNtDXTbuG', 1, 0, NULL, NULL, 0, 0, 'Zx2SvrAQFb', 'active', '2025-09-25 07:09:06', '2025-09-25 07:09:06'),
(195, 'User 132', 'user132@example.com', NULL, '2025-09-25 07:09:06', '$2y$12$9aGK7WCSHprAcJqys.wM7uEhf93qnk51FJ/9DWiOtFZsQWvVZCPui', 3, 0, NULL, NULL, 0, 0, '5eO93JTFMp', 'active', '2025-09-25 07:09:07', '2025-09-25 07:09:07'),
(196, 'User 133', 'user133@example.com', NULL, '2025-09-25 07:09:07', '$2y$12$yM2MtZ0nLifIdnl0h..Cf.gtbzoCKoLIbBfO.WLC2zCrSUtYTiwNe', 3, 0, NULL, NULL, 0, 0, '0I6blIXQru', 'active', '2025-09-25 07:09:07', '2025-09-25 07:09:07'),
(197, 'User 134', 'user134@example.com', NULL, '2025-09-25 07:09:07', '$2y$12$jHxLaruu4LaCARz1n9GMbeTvpLK.3LHixHtemLv73.UtTFcjwYrEi', 2, 0, NULL, NULL, 0, 0, 'AvsvR6WMPk', 'active', '2025-09-25 07:09:08', '2025-09-25 07:09:08'),
(198, 'User 135', 'user135@example.com', NULL, '2025-09-25 07:09:08', '$2y$12$aJzI0ErPWdWjCqyp1.HfuO.6M2.zhLKflYLjikMsf6SXqrx32.5Qe', 2, 0, NULL, NULL, 0, 0, 'eWB2ffi9GW', 'active', '2025-09-25 07:09:08', '2025-09-25 07:09:08'),
(199, 'User 136', 'user136@example.com', NULL, '2025-09-25 07:09:08', '$2y$12$CirYWMQuIE1wUxEeQDzXpe2gnUZ6gemq36Yp0SLbpJSLynJDE3zL6', 2, 0, NULL, NULL, 0, 0, 'NcoZ4MtLPE', 'active', '2025-09-25 07:09:09', '2025-09-25 07:09:09'),
(200, 'User 137', 'user137@example.com', NULL, '2025-09-25 07:09:09', '$2y$12$Jlo5bc7Lv0STN/q2RWAzbu5A9a4ouXcggwial3Zg0RSRn5/TWiwgG', 2, 0, NULL, NULL, 0, 0, 'HgoalYowXg', 'active', '2025-09-25 07:09:09', '2025-09-25 07:09:09'),
(201, 'User 138', 'user138@example.com', NULL, '2025-09-25 07:09:09', '$2y$12$UD4WLrtu9GEVQb61HBQ0hueOKazQO6QljIGvYBr3eTy9AtCGgRtQO', 1, 0, NULL, NULL, 0, 0, 'Z7LKWW47K7', 'active', '2025-09-25 07:09:09', '2025-09-25 07:09:09'),
(202, 'User 139', 'user139@example.com', NULL, '2025-09-25 07:09:09', '$2y$12$smr5QO4JdjfXZmRzWM50zu.V3Mb0.wbwfbmAMGRF7Quxm3jfDLoJu', 2, 0, NULL, NULL, 0, 0, '1dJ5tmEbme', 'active', '2025-09-25 07:09:10', '2025-09-25 07:09:10'),
(203, 'User 140', 'user140@example.com', NULL, '2025-09-25 07:09:10', '$2y$12$Lkvw3vBTSMcRFV5r5Xjsqu.HEEf3DXK.qo/39OlX/OkmwlL3/FF3W', 3, 0, NULL, NULL, 0, 0, 'vi3spQtNwL', 'active', '2025-09-25 07:09:10', '2025-09-25 07:09:10'),
(204, 'User 141', 'user141@example.com', NULL, '2025-09-25 07:09:10', '$2y$12$E93ddZb/IN8y2bSfI0eFauXZ8zll5VV6jFPiAYPMGd3E8q1slEabe', 1, 0, NULL, NULL, 0, 0, 'zoEDTJMv2M', 'active', '2025-09-25 07:09:10', '2025-09-25 07:09:10'),
(205, 'User 142', 'user142@example.com', NULL, '2025-09-25 07:09:10', '$2y$12$wL5kYRbZW/5YpQNUH1RIaudr4qa2yDNNAQmWgD4fnCo2xUXLF4QQS', 1, 0, NULL, NULL, 0, 0, 'rGVUFQjpQS', 'active', '2025-09-25 07:09:11', '2025-09-25 07:09:11'),
(206, 'User 143', 'user143@example.com', NULL, '2025-09-25 07:09:11', '$2y$12$tMfj4fiZ7LBY4eBKX6/pIe614ahOQ1lmgMPbzpAsBsKdrO80WUevG', 3, 0, NULL, NULL, 0, 0, 'gXJjCc79AP', 'active', '2025-09-25 07:09:11', '2025-09-25 07:09:11'),
(207, 'User 144', 'user144@example.com', NULL, '2025-09-25 07:09:11', '$2y$12$9DwJjaJ2xpRnUBT1ALNJguKiEicN7lCZ5eUa/ch4TYJFm1gOJiGBa', 3, 0, NULL, NULL, 0, 0, 'BaW0D1wQmt', 'active', '2025-09-25 07:09:12', '2025-09-25 07:09:12'),
(208, 'User 145', 'user145@example.com', NULL, '2025-09-25 07:09:12', '$2y$12$b6jqdVNAe/s78PlXxJ6sVOGqmfQnNM4VyoHaERhj3q8PS/oIqTDO6', 3, 0, NULL, NULL, 0, 0, 'jP8I9TtUoA', 'active', '2025-09-25 07:09:12', '2025-09-25 07:09:12'),
(209, 'User 146', 'user146@example.com', NULL, '2025-09-25 07:09:12', '$2y$12$WuGlGE5Wype7Udlh7Hg.X.n7USy/29xixcqWWWhIJLcRqJuOF1tma', 1, 0, NULL, NULL, 0, 0, 'tZGVY5ZnZG', 'active', '2025-09-25 07:09:12', '2025-09-25 07:09:12'),
(210, 'User 147', 'user147@example.com', NULL, '2025-09-25 07:09:12', '$2y$12$Vjv7iG/neFLdIk.K9DJD.e/KsNsQ/n409gOPEJ0bjhKsOQzrw5EHa', 1, 0, NULL, NULL, 0, 0, 'hutLwEs9pO', 'active', '2025-09-25 07:09:13', '2025-09-25 07:09:13'),
(211, 'User 148', 'user148@example.com', NULL, '2025-09-25 07:09:13', '$2y$12$mJZahGvce9NYzyMqnkauG.mFK0oRkhVHB27bJJ.s6k7GgukFI7A3y', 2, 0, NULL, NULL, 0, 0, 'Y9stR9LRMJ', 'active', '2025-09-25 07:09:13', '2025-09-25 07:09:13'),
(212, 'User 149', 'user149@example.com', NULL, '2025-09-25 07:09:13', '$2y$12$k1Xr4nhZxIJjHH6uAs1D8.CzKP9rQQ8d5wi6XANTour8ZNN.rysNm', 3, 0, NULL, NULL, 0, 0, 'KTwklVA4BF', 'active', '2025-09-25 07:09:13', '2025-09-25 07:09:13'),
(213, 'User 150', 'user150@example.com', NULL, '2025-09-25 07:09:13', '$2y$12$KXa6/jRRrybAGadY9al9g.cq75btutg6KB6QH1OflKbApydKSlEP6', 3, 0, NULL, NULL, 0, 0, 'XN1cFfT2dX', 'active', '2025-09-25 07:09:14', '2025-09-25 07:09:14'),
(214, 'User 151', 'user151@example.com', NULL, '2025-09-25 07:09:14', '$2y$12$PsUD92cuE1r6EnO.z44Uk.KKIv4fodZLpmaVVIoSKlH/o89ZSaqEO', 1, 0, NULL, NULL, 0, 0, 'Xefzh7NMJm', 'active', '2025-09-25 07:09:14', '2025-09-25 07:09:14'),
(215, 'User 152', 'user152@example.com', NULL, '2025-09-25 07:09:14', '$2y$12$Y9o5iVrKVzZ5eL5hnBkJKOiRFisSTtEWTiSpNXgmllwkeHMiENeXu', 3, 0, NULL, NULL, 0, 0, 'mjvLNuCJRt', 'active', '2025-09-25 07:09:15', '2025-09-25 07:09:15'),
(216, 'User 153', 'user153@example.com', NULL, '2025-09-25 07:09:15', '$2y$12$6SRNDDhdCR0xuK.FNX7HYegOVoARkCsNc7DqpGrOpTYMMgHlYZJAm', 3, 0, NULL, NULL, 0, 0, 'dc5Vh3hj9t', 'active', '2025-09-25 07:09:15', '2025-09-25 07:09:15'),
(217, 'User 154', 'user154@example.com', NULL, '2025-09-25 07:09:15', '$2y$12$9tE6ddafUi0n3Pwz69B9gOozEqchUnYXrowkewsoxiV72E0huXOmC', 2, 0, NULL, NULL, 0, 0, 'QP21k7iCLi', 'active', '2025-09-25 07:09:15', '2025-09-25 07:09:15'),
(218, 'User 155', 'user155@example.com', NULL, '2025-09-25 07:09:15', '$2y$12$ldefdS77e6B1LeTKUcXtgOw6/E/0SQkVaEOlfbWVNUdFvde/0UUQu', 3, 0, NULL, NULL, 0, 0, 'DKX2dKIdIy', 'active', '2025-09-25 07:09:16', '2025-09-25 07:09:16'),
(219, 'User 156', 'user156@example.com', NULL, '2025-09-25 07:09:16', '$2y$12$qHrGVw4HpSnks5KK89Pafe1DUKbiRskiyXjCTNcZtr/UqXzrhwo62', 1, 0, NULL, NULL, 0, 0, 'tkaeuPZjmu', 'active', '2025-09-25 07:09:16', '2025-09-25 07:09:16'),
(220, 'User 157', 'user157@example.com', NULL, '2025-09-25 07:09:16', '$2y$12$gbTUuZ2/cbwbBDNnq/dIV.jxfeg32T.rVjnsibiOCio/gMREOJ9PC', 3, 0, NULL, NULL, 0, 0, 'JlJh2xDGXi', 'active', '2025-09-25 07:09:17', '2025-09-25 07:09:17'),
(221, 'User 158', 'user158@example.com', NULL, '2025-09-25 07:09:17', '$2y$12$X.6OdDO/rrhOItCKZkxuluUnySaCEqt2tV119Ke/rSGN/Wtzv40ki', 3, 0, NULL, NULL, 0, 0, 'SOFcsk0FFT', 'active', '2025-09-25 07:09:17', '2025-09-25 07:09:17'),
(222, 'User 159', 'user159@example.com', NULL, '2025-09-25 07:09:17', '$2y$12$8xZzpuOiQfqQ1wh3iWZFN.XRv1VG/vZDkSg1pzKpzOpoEgZo.P8gG', 2, 0, NULL, NULL, 0, 0, 'ohkvjhgp4g', 'active', '2025-09-25 07:09:17', '2025-09-25 07:09:17'),
(223, 'User 160', 'user160@example.com', NULL, '2025-09-25 07:09:17', '$2y$12$WyWm/gvtLzuNnHZtM0E4eeJNyhgULbLUaI6s4XnzSWr06K/5BCW7O', 2, 0, NULL, NULL, 0, 0, 'nBqakdcwwn', 'active', '2025-09-25 07:09:18', '2025-09-25 07:09:18'),
(224, 'User 161', 'user161@example.com', NULL, '2025-09-25 07:09:18', '$2y$12$iJQisKTBFcNHph5/RDdePuuoghWO4R686iTGVFXvhW/FFKaKA6466', 2, 0, NULL, NULL, 0, 0, 'kP7TLEBpqI', 'active', '2025-09-25 07:09:18', '2025-09-25 07:09:18'),
(225, 'User 162', 'user162@example.com', NULL, '2025-09-25 07:09:18', '$2y$12$NQqqCJ0D7Qy2jSql9w.GDuTrLBrBN3yE8UJCeppzUiGpm74JhHzXC', 1, 0, NULL, NULL, 0, 0, 'lUQJV5OoE5', 'active', '2025-09-25 07:09:18', '2025-09-25 07:09:18'),
(226, 'User 163', 'user163@example.com', NULL, '2025-09-25 07:09:18', '$2y$12$oiAKjV0ji6pWOa4BtxTVTuaDCGw1DtqjQxjXeZtZOK/QiFrOB3jZe', 3, 0, NULL, NULL, 0, 0, 'BPVLAtZFxL', 'active', '2025-09-25 07:09:19', '2025-09-25 07:09:19'),
(227, 'User 164', 'user164@example.com', NULL, '2025-09-25 07:09:19', '$2y$12$vmyfNxhrB7nSUI.9AG41O.HujoWVKwwshaofnCQhVOAAOU7j.IGka', 2, 0, NULL, NULL, 0, 0, 'RMriA4NpkX', 'active', '2025-09-25 07:09:19', '2025-09-25 07:09:19'),
(228, 'User 165', 'user165@example.com', NULL, '2025-09-25 07:09:19', '$2y$12$UKlCb9NLklbcWagHvWaue.tZ7Yjz/ZXCujBs1VXjVyoB.StBCWvqi', 2, 0, NULL, NULL, 0, 0, 'YXoLkydUKj', 'active', '2025-09-25 07:09:19', '2025-09-25 07:09:19'),
(229, 'User 166', 'user166@example.com', NULL, '2025-09-25 07:09:19', '$2y$12$bHohiOhRS2HSq4SQeCNgUekKHd8JdMjcUWKElzNesC4Bo.XTcHUoa', 2, 0, NULL, NULL, 0, 0, 'PuvuuYHHr2', 'active', '2025-09-25 07:09:20', '2025-09-25 07:09:20'),
(230, 'User 167', 'user167@example.com', NULL, '2025-09-25 07:09:20', '$2y$12$G1Yj6BC2CUCW04EPhVv3WOREgJlVr8YvzeR5L7ntCdyOCqBiqbk/a', 2, 0, NULL, NULL, 0, 0, 'kwriNQYtfd', 'active', '2025-09-25 07:09:20', '2025-09-25 07:09:20'),
(231, 'User 168', 'user168@example.com', NULL, '2025-09-25 07:09:20', '$2y$12$a8meUIa0suWQb/saOhSOC.7Zxw95boub/K1XPwuWLt.1gAQfQwIJu', 1, 0, NULL, NULL, 0, 0, 'XmMV6eEAfu', 'active', '2025-09-25 07:09:20', '2025-09-25 07:09:20'),
(232, 'User 169', 'user169@example.com', NULL, '2025-09-25 07:09:20', '$2y$12$Z3djdbgokTtV6VvzSHF0ZOxRmijCagCHXYsWhT0eHxDUoITrjqBz.', 1, 0, NULL, NULL, 0, 0, '6UpyBt58hH', 'active', '2025-09-25 07:09:21', '2025-09-25 07:09:21'),
(233, 'User 170', 'user170@example.com', NULL, '2025-09-25 07:09:21', '$2y$12$Im51lnRyzhzemyJfgTSYYeDun/M83NM2yEJbX0JvJo.HSjBEeqbBW', 2, 0, NULL, NULL, 0, 0, 'y2yFiE7O3b', 'active', '2025-09-25 07:09:21', '2025-09-25 07:09:21'),
(234, 'User 171', 'user171@example.com', NULL, '2025-09-25 07:09:21', '$2y$12$1p47dGaFV9j7cYmpGHQN8uluQG9oQzFU4mkpnPQt4fi0K4gg8jbjy', 2, 0, NULL, NULL, 0, 0, '9mxRxCfee8', 'active', '2025-09-25 07:09:21', '2025-09-25 07:09:21'),
(235, 'User 172', 'user172@example.com', NULL, '2025-09-25 07:09:21', '$2y$12$9tuDul7Y.tpc3FS2zCEXOueMKkiFJFwyZdGMLFWvERjDBKk7ailPS', 3, 0, NULL, NULL, 0, 0, 'FtvniFmrS1', 'active', '2025-09-25 07:09:22', '2025-09-25 07:09:22'),
(236, 'User 173', 'user173@example.com', NULL, '2025-09-25 07:09:22', '$2y$12$31WtyfpFQBtn4W2.a00Qxuvdaqqr0m29HKFs2du9Xnlz8mZ7mylBq', 1, 0, NULL, NULL, 0, 0, 'mTpMZhM8S0', 'active', '2025-09-25 07:09:22', '2025-09-25 07:09:22'),
(237, 'User 174', 'user174@example.com', NULL, '2025-09-25 07:09:22', '$2y$12$JcD5kumKxfzKzeLWbRHygOqWJyJ6VRkJGzKmLmvGMMtAK0Oo2sQLa', 3, 0, NULL, NULL, 0, 0, 'UHjvEgs3mb', 'active', '2025-09-25 07:09:22', '2025-09-25 07:09:22'),
(238, 'User 175', 'user175@example.com', NULL, '2025-09-25 07:09:22', '$2y$12$1dsFITECg0LKrcAoTGcoV.MiP.S4DXxw/V9uUwC19DHu4KiLcb.8.', 1, 0, NULL, NULL, 0, 0, 'Y6nQZpPL8m', 'active', '2025-09-25 07:09:23', '2025-09-25 07:09:23'),
(239, 'User 176', 'user176@example.com', NULL, '2025-09-25 07:09:23', '$2y$12$ZBCnMMcSW.PAvJ4j19/2K.ajd.3.pcacE38uV/mst3URrFaOpUism', 3, 0, NULL, NULL, 0, 0, 'zucFIQlnu1', 'active', '2025-09-25 07:09:23', '2025-09-25 07:09:23'),
(240, 'User 177', 'user177@example.com', NULL, '2025-09-25 07:09:23', '$2y$12$DF9r1Tazh7ZSQEzgCYBV8OkVdPumMYg2bS.HH9YSr3aXUvciSd/hW', 1, 0, NULL, NULL, 0, 0, 'u9smN3W9SB', 'active', '2025-09-25 07:09:24', '2025-09-25 07:09:24'),
(241, 'User 178', 'user178@example.com', NULL, '2025-09-25 07:09:24', '$2y$12$quF0pDkriLkBc1dR1rvT0.VUsT.XiTO/.sOP.CGj0VjX379.AVyQC', 1, 0, NULL, NULL, 0, 0, 'qk97pvpQoU', 'active', '2025-09-25 07:09:24', '2025-09-25 07:09:24'),
(242, 'User 179', 'user179@example.com', NULL, '2025-09-25 07:09:24', '$2y$12$2J1Wzcnztzj1gFtwjV.kN.mwUer3vv37ZpQRh9UtiA3w0y8dDhMZ2', 1, 0, NULL, NULL, 0, 0, '3vvewgPLhA', 'active', '2025-09-25 07:09:24', '2025-09-25 07:09:24'),
(243, 'User 180', 'user180@example.com', NULL, '2025-09-25 07:09:24', '$2y$12$bPEuHtSxR9/a67Lq5tW1Qefv5xFCvECWCofdCcZPBT.5xTtTrAv3i', 3, 0, NULL, NULL, 0, 0, '1EHMWfM03u', 'active', '2025-09-25 07:09:25', '2025-09-25 07:09:25'),
(244, 'User 181', 'user181@example.com', NULL, '2025-09-25 07:09:25', '$2y$12$xwpFLi/czTne36KK953P7.DjOu1pOB5L6eCwI6D7MR.hLAGzg.HB2', 3, 0, NULL, NULL, 0, 0, 'K9E7AQX5xp', 'active', '2025-09-25 07:09:25', '2025-09-25 07:09:25'),
(245, 'User 182', 'user182@example.com', NULL, '2025-09-25 07:09:25', '$2y$12$EkNoUC4WvQScpT5rncx6QOo.nQ3eQACGwxuAdevkUJVJoecH.ids2', 3, 0, NULL, NULL, 0, 0, 'l9zxya2LkP', 'active', '2025-09-25 07:09:25', '2025-09-25 07:09:25'),
(246, 'User 183', 'user183@example.com', NULL, '2025-09-25 07:09:25', '$2y$12$ORyxguU8/3WztdflScJXxezlKRAb.sKv/cWIc2Q7a1Knj1zD9RAx.', 1, 0, NULL, NULL, 0, 0, '8UWjhPwJEA', 'active', '2025-09-25 07:09:26', '2025-09-25 07:09:26'),
(247, 'User 184', 'user184@example.com', NULL, '2025-09-25 07:09:26', '$2y$12$/S4e/qsB6GpG3ipnXx0IxeDMRU6.klullkGShdnetbz7h7kw/LR4S', 3, 0, NULL, NULL, 0, 0, 'aYsv5qeg7K', 'active', '2025-09-25 07:09:26', '2025-09-25 07:09:26'),
(248, 'User 185', 'user185@example.com', NULL, '2025-09-25 07:09:26', '$2y$12$dV9d96ND2CMBPD0w2YZGmOGBPvsMxvN6IW983FXM17.QTYe1EZjzW', 3, 0, NULL, NULL, 0, 0, 'ggte8umt7l', 'active', '2025-09-25 07:09:26', '2025-09-25 07:09:26'),
(249, 'User 186', 'user186@example.com', NULL, '2025-09-25 07:09:27', '$2y$12$uQ6H45JswN8vij5rff1aT.TcR.nA5To5/JreF9mNmyKTaTRDs1Zq6', 1, 0, NULL, NULL, 0, 0, '2156pezZeo', 'active', '2025-09-25 07:09:27', '2025-09-25 07:09:27'),
(250, 'User 187', 'user187@example.com', NULL, '2025-09-25 07:09:27', '$2y$12$rUwK1oBD5scMY41aDTQvSOkYPFTfqPqQE1412kK5pZo/NJirr2zjG', 2, 0, NULL, NULL, 0, 0, 'Pd5NV76Ewy', 'active', '2025-09-25 07:09:27', '2025-09-25 07:09:27'),
(251, 'User 188', 'user188@example.com', NULL, '2025-09-25 07:09:27', '$2y$12$dz5IKNfDROL4V8iFHL.y.elOY5ZAlaeUpMlGaPckUX7sKQMJbTz6i', 1, 0, NULL, NULL, 0, 0, '7EQ3L9R0vu', 'active', '2025-09-25 07:09:28', '2025-09-25 07:09:28'),
(252, 'User 189', 'user189@example.com', NULL, '2025-09-25 07:09:28', '$2y$12$WvDAz1R0mWsobz75HaazWe8AibHzCu5KbZOWsRu9g.yio7ecOYqmG', 2, 0, NULL, NULL, 0, 0, 'FBGDvmJMFk', 'active', '2025-09-25 07:09:28', '2025-09-25 07:09:28'),
(253, 'User 190', 'user190@example.com', NULL, '2025-09-25 07:09:28', '$2y$12$3TKWePfSnjbVFi78OAkzEu/RkZgBN4bqDVsacy2eZSjeP1/3nQ7BK', 2, 0, NULL, NULL, 0, 0, 'o6Eu5BncJ3', 'active', '2025-09-25 07:09:28', '2025-09-25 07:09:28'),
(254, 'User 191', 'user191@example.com', NULL, '2025-09-25 07:09:28', '$2y$12$5TRQZiPeF5tnXPOQCdhBUulSSURbqwLZwkogE5DbRVu3uM0pe1.yC', 3, 0, NULL, NULL, 0, 0, '3Ty8R1GAac', 'active', '2025-09-25 07:09:29', '2025-09-25 07:09:29'),
(255, 'User 192', 'user192@example.com', NULL, '2025-09-25 07:09:29', '$2y$12$LLqA.iyqktk4LB4R/YaGl.ZgbohowxTm3cgJ/AWqt/qSr7IdsZBxW', 1, 0, NULL, NULL, 0, 0, 'TlLmpIoM4s', 'active', '2025-09-25 07:09:29', '2025-09-25 07:09:29'),
(256, 'User 193', 'user193@example.com', NULL, '2025-09-25 07:09:29', '$2y$12$O1Wk4MfJ0wv9BLnstIl90OzhRhv.RskUzd0mgVCuxikzoSgJF3kQe', 3, 0, NULL, NULL, 0, 0, 'E8TvKe4Q4k', 'active', '2025-09-25 07:09:29', '2025-09-25 07:09:29'),
(257, 'User 194', 'user194@example.com', NULL, '2025-09-25 07:09:29', '$2y$12$mimq/Eu.ieBXqRd1uWXhSei24Pp.TJsxhPqIyuLBFR/T/qogKEkIS', 2, 0, NULL, NULL, 0, 0, 'iDz5p1TU0D', 'active', '2025-09-25 07:09:30', '2025-09-25 07:09:30'),
(258, 'User 195', 'user195@example.com', NULL, '2025-09-25 07:09:30', '$2y$12$Xxa4tGiIkkPK4TaVFm6iKuzL/Nv..G5r47np04HipRZIXbIvb2AWO', 3, 0, NULL, NULL, 0, 0, 'HeINZjjW7M', 'active', '2025-09-25 07:09:30', '2025-09-25 07:09:30'),
(259, 'User 196', 'user196@example.com', NULL, '2025-09-25 07:09:30', '$2y$12$9DsYe27hxJOhW8e/UzUMZ.GbgPRInmFYa0esUDlIEPJjcALFgT.pq', 2, 0, NULL, NULL, 0, 0, 'TrT6O6QDXl', 'active', '2025-09-25 07:09:31', '2025-09-25 07:09:31'),
(260, 'User 197', 'user197@example.com', NULL, '2025-09-25 07:09:31', '$2y$12$yzngBTia8tvm0QG7ZndDs.QioWEVLxXaoqOedvaY/MYVIlJJRd8LS', 2, 0, NULL, NULL, 0, 0, 'ZsLJfENweq', 'active', '2025-09-25 07:09:31', '2025-09-25 07:09:31'),
(261, 'User 198', 'user198@example.com', NULL, '2025-09-25 07:09:31', '$2y$12$cf1ESUrXgFp.nNsa/b/oD.4W9s2m2YchuUgzrVfN79O7Xuht.U0Tm', 3, 0, NULL, NULL, 0, 0, 'S2optzkeC5', 'active', '2025-09-25 07:09:31', '2025-09-25 07:09:31'),
(262, 'User 199', 'user199@example.com', NULL, '2025-09-25 07:09:31', '$2y$12$5I2I/fPZSO21pp.ez5l9O.K16Dvh0vCPcG2reweumwjLQ31MeyzAC', 3, 0, NULL, NULL, 0, 0, 'lMggExiRFq', 'active', '2025-09-25 07:09:32', '2025-09-25 07:09:32'),
(263, 'User 200', 'user200@example.com', NULL, '2025-09-25 07:09:32', '$2y$12$B8G6kK31pJKcuiYV6IJU3u6BWAE0uLkXl4jjWhNnRO6z9KETyE.BO', 2, 0, NULL, NULL, 0, 0, 'o237Vs8kgR', 'active', '2025-09-25 07:09:32', '2025-09-25 07:09:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `cross_references`
--
ALTER TABLE `cross_references`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cross_references_product_id_foreign` (`product_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oe_numbers`
--
ALTER TABLE `oe_numbers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oe_numbers_product_id_foreign` (`product_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_sku_unique` (`sku`),
  ADD UNIQUE KEY `products_barcode_unique` (`barcode`),
  ADD KEY `products_brand_id_foreign` (`brand_id`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indexes for table `product_fitments`
--
ALTER TABLE `product_fitments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_fitments_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_foreign` (`product_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stock_batches`
--
ALTER TABLE `stock_batches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_batches_product_id_foreign` (`product_id`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_movements_product_id_foreign` (`product_id`),
  ADD KEY `stock_movements_stock_batch_id_foreign` (`stock_batch_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `cross_references`
--
ALTER TABLE `cross_references`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `oe_numbers`
--
ALTER TABLE `oe_numbers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_fitments`
--
ALTER TABLE `product_fitments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `stock_batches`
--
ALTER TABLE `stock_batches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=264;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cross_references`
--
ALTER TABLE `cross_references`
  ADD CONSTRAINT `cross_references_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `oe_numbers`
--
ALTER TABLE `oe_numbers`
  ADD CONSTRAINT `oe_numbers_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_fitments`
--
ALTER TABLE `product_fitments`
  ADD CONSTRAINT `product_fitments_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_batches`
--
ALTER TABLE `stock_batches`
  ADD CONSTRAINT `stock_batches_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_movements_stock_batch_id_foreign` FOREIGN KEY (`stock_batch_id`) REFERENCES `stock_batches` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
