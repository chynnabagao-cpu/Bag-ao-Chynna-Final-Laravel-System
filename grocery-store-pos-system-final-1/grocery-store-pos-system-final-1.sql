-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2026 at 07:10 AM
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
-- Database: `grocery-store-pos-system-final-1`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Beverages', '2026-05-21 00:35:07', '2026-05-21 00:35:07'),
(2, 'Dairy', '2026-05-21 00:35:07', '2026-05-21 00:35:07'),
(3, 'Snacks', '2026-05-21 00:35:07', '2026-05-21 00:35:07'),
(4, 'Personal Care', '2026-05-21 00:35:07', '2026-05-21 00:35:07'),
(5, 'Bakery', '2026-05-21 00:35:07', '2026-05-21 00:35:07'),
(6, 'Canned Goods', '2026-05-21 00:35:07', '2026-05-21 00:35:07'),
(7, 'frozen foods', '2026-05-21 19:53:01', '2026-05-21 19:53:01');

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
(1, '2024_01_01_000000_create_pos_tables', 1),
(2, '2024_01_01_000001_add_image_to_products', 1),
(3, '2024_01_01_000002_create_settings_table', 1),
(4, '2024_01_01_000003_add_soft_deletes_to_products', 1),
(5, '2026_05_15_144748_create_sessions_table', 1),
(6, '2026_05_21_024131_add_expiration_date_to_products_table', 1),
(7, '2024_01_01_000004_add_discount_to_products', 2),
(8, '2024_01_01_000005_create_promotions_table', 3),
(9, '2024_01_01_000006_add_discount_fields_to_sale_items', 4),
(10, '2026_05_21_125828_add_discount_columns_to_sale_items', 4),
(11, '2026_05_22_031731_fix_sale_items_columns', 4);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `barcode` varchar(255) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `discount_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `stock_quantity` int(11) NOT NULL,
  `min_stock_threshold` int(11) NOT NULL DEFAULT 10,
  `expiration_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `barcode`, `image_path`, `category_id`, `cost_price`, `selling_price`, `discount_percentage`, `stock_quantity`, `min_stock_threshold`, `expiration_date`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Sting', '3423437897', 'https://cdn.grofers.com/da/cms-assets/cms/product/9d1a9c8a-c8ef-40f2-bdb6-9ad65e968162.jpg?ts=1729514605', 1, 10.00, 15.00, 0.00, 97, 10, '2026-05-21', '2026-05-21 03:54:05', '2026-05-21 17:26:24', NULL),
(2, '1.5 Coke Classic', '1234545', 'https://thfvnext.bing.com/th/id/OIP.WYwDCHNKbLf6wvDDb9by4AHaHa?w=185&h=185&c=7&r=0&o=7&cb=thfvnextfalcon&dpr=1.3&pid=1.7&rm=3', 1, 70.00, 75.00, 0.00, 98, 10, '2027-09-22', '2026-05-21 17:28:47', '2026-05-21 19:45:15', NULL),
(3, 'Oishi prawn Crackers', '98765678', 'https://thfvnext.bing.com/th/id/OIP.YovJbDAN12wwOBWeEmcdvgHaHa?w=161&h=180&c=7&r=0&o=7&cb=thfvnextfalcon&dpr=1.3&pid=1.7&rm=3', 3, 25.00, 28.00, 0.00, 0, 10, '2030-01-22', '2026-05-21 17:30:50', '2026-05-21 19:47:33', NULL),
(4, 'nature spring water', '98767654456', 'https://thfvnext.bing.com/th/id/OIP.sSDeU-UU9tnwTEQ6Xw1hWgHaHa?w=191&h=191&c=7&r=0&o=7&cb=thfvnextfalcon&dpr=1.3&pid=1.7&rm=3', 1, 10.00, 15.00, 0.00, 1000, 8, '2027-07-01', '2026-05-21 19:52:33', '2026-05-21 19:52:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

CREATE TABLE `promotions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'PRODUCT',
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `discount_percentage` decimal(5,2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `promotions`
--

INSERT INTO `promotions` (`id`, `name`, `type`, `product_id`, `discount_percentage`, `start_date`, `end_date`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Sting', 'PRODUCT', 1, 10.00, '2026-05-21', '2026-10-21', 1, '2026-05-21 03:55:26', '2026-05-21 19:06:43');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `receipt_number` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` enum('Cash','GCash') NOT NULL,
  `cash_received` decimal(10,2) DEFAULT NULL,
  `change_amount` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `receipt_number`, `user_id`, `total_amount`, `payment_method`, `cash_received`, `change_amount`, `created_at`, `updated_at`) VALUES
(1, 'REC-WEINPDCV', 1, 30.00, 'Cash', 55.00, 25.00, '2026-05-21 03:56:30', '2026-05-21 03:56:30'),
(2, 'REC-KQF69RRB', 1, 15.00, 'GCash', 0.00, 0.00, '2026-05-21 03:57:12', '2026-05-21 03:57:12'),
(7, 'REC-SIOXNW4P', 1, 75.00, 'Cash', 100.00, 25.00, '2026-05-21 19:20:01', '2026-05-21 19:20:01'),
(8, 'REC-2KBMP4KX', 2, 103.00, 'GCash', 0.00, 0.00, '2026-05-21 19:45:15', '2026-05-21 19:45:15'),
(10, 'REC-FGWI8ZWJ', 2, 2772.00, 'Cash', 4000.00, 1228.00, '2026-05-21 19:47:33', '2026-05-21 19:47:33');

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--

CREATE TABLE `sale_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `original_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_items`
--

INSERT INTO `sale_items` (`id`, `sale_id`, `product_id`, `original_price`, `quantity`, `unit_price`, `discount_amount`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 0.00, 2, 15.00, 0.00, 30.00, '2026-05-21 03:56:30', '2026-05-21 03:56:30'),
(2, 2, 1, 0.00, 1, 15.00, 0.00, 15.00, '2026-05-21 03:57:12', '2026-05-21 03:57:12'),
(3, 7, 2, 75.00, 1, 75.00, 0.00, 75.00, '2026-05-21 19:20:01', '2026-05-21 19:20:01'),
(4, 8, 3, 28.00, 1, 28.00, 0.00, 28.00, '2026-05-21 19:45:15', '2026-05-21 19:45:15'),
(5, 8, 2, 75.00, 1, 75.00, 0.00, 75.00, '2026-05-21 19:45:15', '2026-05-21 19:45:15'),
(6, 10, 3, 28.00, 99, 28.00, 0.00, 2772.00, '2026-05-21 19:47:33', '2026-05-21 19:47:33');

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

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'gcash_qr_path', 'settings/5PJk48Hgb4QswNdRh1tq3LaLdAfz8aUkU20kTsYk.jpg', '2026-05-21 00:35:05', '2026-05-21 20:06:42'),
(2, 'store_name', 'Nicolle Grocery Store', '2026-05-21 00:35:05', '2026-05-21 00:35:05'),
(3, 'store_address', '123 Market St, Metro Manila', '2026-05-21 00:35:05', '2026-05-21 00:35:05'),
(4, 'store_contact', '+63 912 345 6789', '2026-05-21 00:35:05', '2026-05-21 00:35:05');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Cashier') NOT NULL DEFAULT 'Cashier',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Nicolle Admin', 'admin', '$2y$12$FPPky8O8QgpJ3VZPSHGJauAA/0Jcj2k73zHuP.KlImdocB92qMXou', 'Admin', 'pZD0WMPumkQp1oeSeZ5J6hXupfAbCnJqtvhHhNhbltNr1cHpgFWM0EHzAOhj', '2026-05-21 00:35:07', '2026-05-21 00:35:07'),
(2, 'Nicolle Cashier', 'cashier', '$2y$12$3iwkl9RlkUtrXg/p.LO.4u2TTqLp/2BThYlmzeJYLbEnG.xIWvzSW', 'Cashier', '39ib5CSZamlpmfTTTDLJ7vyweiEziRy9CEhde5PqT4kVBNmezvZwl3DyISQ0', '2026-05-21 00:35:07', '2026-05-21 18:56:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_barcode_unique` (`barcode`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indexes for table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `promotions_product_id_foreign` (`product_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sales_receipt_number_unique` (`receipt_number`),
  ADD KEY `sales_user_id_foreign` (`user_id`);

--
-- Indexes for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_items_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `promotions`
--
ALTER TABLE `promotions`
  ADD CONSTRAINT `promotions_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD CONSTRAINT `sale_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `sale_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
