-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 29, 2025 at 04:32 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `store`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `user_id`, `nama`, `jenis_kelamin`, `no_telp`, `alamat`, `created_at`, `updated_at`, `deleted_at`) VALUES
(3, 6, 'Administrator', 'L', '08123456789', 'Jl. Admin No. 1', '2025-05-29 14:52:03', '2025-05-29 14:52:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE `karyawan` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`id`, `user_id`, `nama`, `no_telp`, `alamat`, `foto`, `created_at`, `updated_at`, `deleted_at`) VALUES
(4, 7, 'winter', '08987654321', 'jombang', 'profile_cvemAdneqa_1748531797.jpg', '2025-05-29 14:52:03', '2025-05-29 15:34:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `laporanpenjualans`
--

CREATE TABLE `laporanpenjualans` (
  `id` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `total_penjualan` int NOT NULL,
  `total_produk_terjual` int NOT NULL,
  `jumlah_transaksi` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_03_18_000000_create_all_tables', 1),
(6, '2024_05_04_130531_create_karyawans_table', 1),
(7, '2024_05_04_132113_create_admins_table', 1),
(8, '2024_05_30_add_last_login_to_users', 1),
(9, '2024_05_30_fix_product_recipes_table', 1),
(10, '2025_05_21_115459_create_transaksis_table', 1),
(11, '2025_05_21_214437_modify_roles_column_in_users_table', 1),
(12, '2025_05_21_232303_create_pelanggan_table', 1),
(13, '2025_05_22_104808_create_laporanpenjualans_table', 1),
(14, '2025_05_29_150056_remove_jenis_kelamin_from_karyawan_table', 2),
(15, '2024_05_30_add_minimum_stock_to_raw_materials', 3);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default_foto.jpg',
  `id_user` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_recipes`
--

CREATE TABLE `product_recipes` (
  `id` bigint UNSIGNED NOT NULL,
  `produk_id` bigint UNSIGNED NOT NULL,
  `raw_material_id` bigint UNSIGNED NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produks`
--

CREATE TABLE `produks` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_produk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga_produk` decimal(10,2) NOT NULL,
  `stok_produk` int NOT NULL DEFAULT '0',
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi_produk` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `produks`
--

INSERT INTO `produks` (`id`, `nama_produk`, `harga_produk`, `stok_produk`, `foto`, `deskripsi_produk`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'geprek paket 1', '15000.00', 3, '1748108260_2223196960-removebg-preview.png', 'geprek paket 1 mantap', '2025-05-24 10:37:40', '2025-05-29 15:43:36', NULL),
(2, 'geprek paket sambal pedas', '25000.00', 9, '1748108983_ayam-geprek_169-removebg-preview.png', 'geprek paket sambal pedas', '2025-05-24 10:49:43', '2025-05-29 15:43:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `raw_materials`
--

CREATE TABLE `raw_materials` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` decimal(10,2) NOT NULL DEFAULT '0.00',
  `minimum_stock` decimal(10,2) NOT NULL DEFAULT '10.00',
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `raw_materials`
--

INSERT INTO `raw_materials` (`id`, `name`, `price`, `stock`, `minimum_stock`, `unit`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'nasi', '65000.00', '10.00', '10.00', 'kg', NULL, '2025-05-29 15:45:02', '2025-05-29 16:25:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `raw_material_logs`
--

CREATE TABLE `raw_material_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `raw_material_id` bigint UNSIGNED NOT NULL,
  `supplier_id` bigint UNSIGNED DEFAULT NULL,
  `type` enum('in','out') COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `raw_material_logs`
--

INSERT INTO `raw_material_logs` (`id`, `raw_material_id`, `supplier_id`, `type`, `quantity`, `price`, `subtotal`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 1, NULL, 'in', '5.00', NULL, '0.00', 'Initial stock', '2025-05-29 15:45:02', '2025-05-29 15:45:02', NULL),
(3, 1, NULL, 'in', '5.00', NULL, '325000.00', NULL, '2025-05-29 16:08:58', '2025-05-29 16:08:58', NULL),
(4, 1, NULL, 'in', '5.00', NULL, '325000.00', NULL, '2025-05-29 16:20:34', '2025-05-29 16:20:34', NULL),
(5, 1, NULL, 'out', '5.00', NULL, '325000.00', NULL, '2025-05-29 16:24:33', '2025-05-29 16:24:33', NULL),
(6, 1, NULL, 'in', '5.00', NULL, '325000.00', NULL, '2025-05-29 16:24:53', '2025-05-29 16:24:53', NULL),
(7, 1, NULL, 'out', '5.00', NULL, '325000.00', NULL, '2025-05-29 16:25:42', '2025-05-29 16:25:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_transaksi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `total_harga` decimal(10,2) NOT NULL DEFAULT '0.00',
  `metode_pembayaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `status` enum('pending','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `tanggal_transaksi` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `kode_transaksi`, `user_id`, `total_harga`, `metode_pembayaran`, `status`, `tanggal_transaksi`, `customer_name`, `customer_phone`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'TRX-20250529-0001', 7, '65000.00', 'cash', 'completed', '2025-05-29 15:36:31', 'febrii', '646564', '2025-05-29 15:36:31', '2025-05-29 15:36:31', NULL),
(2, 'TRX-20250529-0002', 1, '70000.00', 'cash', 'completed', '2025-05-29 15:43:36', 'winter sayang', '9837535', '2025-05-29 15:43:36', '2025-05-29 15:43:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_details`
--

CREATE TABLE `transaksi_details` (
  `id` bigint UNSIGNED NOT NULL,
  `transaksi_id` bigint UNSIGNED NOT NULL,
  `produk_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaksi_details`
--

INSERT INTO `transaksi_details` (`id`, `transaksi_id`, `produk_id`, `quantity`, `price`, `subtotal`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 2, 2, '25000.00', '50000.00', '2025-05-29 15:36:31', '2025-05-29 15:36:31', NULL),
(2, 1, 1, 1, '15000.00', '15000.00', '2025-05-29 15:36:31', '2025-05-29 15:36:31', NULL),
(3, 2, 2, 1, '25000.00', '25000.00', '2025-05-29 15:43:36', '2025-05-29 15:43:36', NULL),
(4, 2, 1, 3, '15000.00', '45000.00', '2025-05-29 15:43:36', '2025-05-29 15:43:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `roles`, `password`, `created_at`, `updated_at`, `deleted_at`, `last_login`) VALUES
(1, 'admin@gmail.com', 'admin', '$2y$10$rHE9U8bYL1xQGafF2E8RJ.spwQI84mi06yDU6SUyJTzc1IaVVLROi', '2025-05-29 06:53:33', '2025-05-29 06:53:33', NULL, NULL),
(2, 'admin@gmail.com', 'admin', '$2y$10$hutV4wwrmviDUP/Ewn/buO7duHhcthGZE2Uo6EZOmXdAJiOWOUD7W', '2025-05-29 06:53:33', '2025-05-29 06:53:33', NULL, NULL),
(3, 'karyawan1@gmail.com', 'karyawan1', 'karyawan', NULL, '2025-05-29 07:52:12', NULL, NULL),
(4, 'karyawan2@gmail.com', 'karyawan2', 'karyawan', NULL, NULL, NULL, NULL),
(5, 'kasir@gmail.com', 'karyawan', '$2y$10$rzjITWOCPK4M08xXReUvGOOjv/4TX3n0Mn5xVxODbGmRtSvL8FlOi', '2025-05-29 08:20:42', '2025-05-29 08:20:42', NULL, NULL),
(6, 'admin@admin.com', 'admin', '$2y$10$wyb3SJ86ezGcS.fOmwceQ.1cKSfxDtiHDReOU7ka6jcT.Id3CB1du', '2025-05-29 14:52:03', '2025-05-29 14:52:03', NULL, NULL),
(7, 'karyawan@gmail.com', 'karyawan', '$2y$10$os0ub0Kjcn0MSW5Ph3ZEhePBmv5MjEb7KW2CvkoobjVEk0XLWFigO', '2025-05-29 14:52:03', '2025-05-29 15:34:25', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admins_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `karyawan_user_id_foreign` (`user_id`);

--
-- Indexes for table `laporanpenjualans`
--
ALTER TABLE `laporanpenjualans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pelanggan_no_telp_unique` (`no_telp`),
  ADD UNIQUE KEY `pelanggan_id_user_unique` (`id_user`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `product_recipes`
--
ALTER TABLE `product_recipes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_recipes_produk_id_foreign` (`produk_id`),
  ADD KEY `product_recipes_raw_material_id_foreign` (`raw_material_id`);

--
-- Indexes for table `produks`
--
ALTER TABLE `produks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `raw_materials`
--
ALTER TABLE `raw_materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `raw_material_logs`
--
ALTER TABLE `raw_material_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `raw_material_logs_raw_material_id_foreign` (`raw_material_id`),
  ADD KEY `raw_material_logs_supplier_id_foreign` (`supplier_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaksi_kode_transaksi_unique` (`kode_transaksi`),
  ADD KEY `transaksi_user_id_foreign` (`user_id`);

--
-- Indexes for table `transaksi_details`
--
ALTER TABLE `transaksi_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaksi_details_transaksi_id_foreign` (`transaksi_id`),
  ADD KEY `transaksi_details_produk_id_foreign` (`produk_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `karyawan`
--
ALTER TABLE `karyawan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `laporanpenjualans`
--
ALTER TABLE `laporanpenjualans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_recipes`
--
ALTER TABLE `product_recipes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `produks`
--
ALTER TABLE `produks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `raw_materials`
--
ALTER TABLE `raw_materials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `raw_material_logs`
--
ALTER TABLE `raw_material_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transaksi_details`
--
ALTER TABLE `transaksi_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD CONSTRAINT `karyawan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD CONSTRAINT `pelanggan_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_recipes`
--
ALTER TABLE `product_recipes`
  ADD CONSTRAINT `product_recipes_produk_id_foreign` FOREIGN KEY (`produk_id`) REFERENCES `produks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_recipes_raw_material_id_foreign` FOREIGN KEY (`raw_material_id`) REFERENCES `raw_materials` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `raw_material_logs`
--
ALTER TABLE `raw_material_logs`
  ADD CONSTRAINT `raw_material_logs_raw_material_id_foreign` FOREIGN KEY (`raw_material_id`) REFERENCES `raw_materials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `raw_material_logs_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaksi_details`
--
ALTER TABLE `transaksi_details`
  ADD CONSTRAINT `transaksi_details_produk_id_foreign` FOREIGN KEY (`produk_id`) REFERENCES `produks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaksi_details_transaksi_id_foreign` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
