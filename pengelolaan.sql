-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 09, 2026 at 04:20 AM
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
-- Database: `pengelolaan`
--

-- --------------------------------------------------------

--
-- Table structure for table `databarang`
--

CREATE TABLE `databarang` (
  `id_barang` int(11) NOT NULL,
  `kode_barang` varchar(50) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `spesifikasi` text DEFAULT NULL,
  `satuan` varchar(20) DEFAULT NULL,
  `harga_perolehan` decimal(15,2) DEFAULT NULL,
  `tanggal_perolehan` date DEFAULT NULL,
  `umur_ekonomis` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `databarang`
--

INSERT INTO `databarang` (`id_barang`, `kode_barang`, `nama_barang`, `kategori`, `spesifikasi`, `satuan`, `harga_perolehan`, `tanggal_perolehan`, `umur_ekonomis`) VALUES
(1, 'MMK01', 'Meja Belajar Kayu', 'Furniture', 'Kayu jati, ukuran 120x60 cm', 'unit', 850000.00, '2026-06-10', 7);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `image` varchar(128) NOT NULL,
  `password` varchar(256) NOT NULL,
  `role_id` int(11) NOT NULL,
  `is_active` int(1) NOT NULL,
  `date` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `image`, `password`, `role_id`, `is_active`, `date`) VALUES
(1, 'naila', 'wrisninaila@gmail.com', 'default.jpg', '$2y$10$eADA1Fcuhd3OGYYBSkoIG.QfJ6wgma./rO7v/pV.wM0JirJG3jNAe', 2, 1, 1770278467),
(2, 'lilian', 'wrisnihusna@gmail.com', 'default.jpg', '$2y$10$hDSISYo5RcZoQ6ZrEm4YEuIOYPOX5pqbxRjKLCaqdZG1XKVo9uLI.', 1, 1, 1770347897);

-- --------------------------------------------------------

--
-- Table structure for table `user_access_menu`
--

CREATE TABLE `user_access_menu` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_access_menu`
--

INSERT INTO `user_access_menu` (`id`, `role_id`, `menu_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7),
(15, 2, 1),
(16, 2, 3),
(17, 2, 4),
(18, 2, 5),
(19, 2, 6),
(20, 2, 7);

-- --------------------------------------------------------

--
-- Table structure for table `user_menu`
--

CREATE TABLE `user_menu` (
  `id` int(11) NOT NULL,
  `menu` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_menu`
--

INSERT INTO `user_menu` (`id`, `menu`) VALUES
(1, 'Dahsboard'),
(2, 'Master data'),
(3, 'Management Inventaris'),
(4, 'Barang'),
(5, 'kondisi & pemeliharaan '),
(6, 'Penghapusan'),
(7, 'laporan\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `id` int(11) NOT NULL,
  `role` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`id`, `role`) VALUES
(1, 'Administrator'),
(2, 'Petugas'),
(3, 'Pimpinan');

-- --------------------------------------------------------

--
-- Table structure for table `user_sub_menu`
--

CREATE TABLE `user_sub_menu` (
  `id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `url` varchar(128) NOT NULL,
  `icon` varchar(128) NOT NULL,
  `is_active` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_sub_menu`
--

INSERT INTO `user_sub_menu` (`id`, `menu_id`, `title`, `url`, `icon`, `is_active`) VALUES
(1, 1, 'Dahsboard', 'admin', 'fas fa-fw fa-clipboard', 1),
(2, 2, 'Data barang', 'master/databarang', 'fas fa-fw fa-clipboard', 1),
(3, 2, 'Kategori barang', 'master', 'fas fa-fw fa-clipboard', 1),
(4, 2, 'Lokasi', 'master', 'fas fa-fw fa-clipboard', 1),
(5, 2, 'Supplier', 'master', 'fas fa-fw fa-clipboard', 1),
(6, 2, 'Data Pengguna', 'master', 'fas fa-fw fa-clipboard', 1),
(7, 3, 'Daftar barang', 'inventaris', 'fas fa-fw fa-box', 1),
(8, 4, 'Barang', 'barang', 'fas fa-fw fa-box', 1),
(9, 4, 'peminjaman', 'barang', 'fas fa-fw fa-box', 1),
(10, 5, 'kondisi dan perawatan', 'kp', 'fas fa-fw fa-box', 1),
(11, 6, 'Penghapusan barang ', 'penghapusan ', 'fas fa-fw fa-box', 1),
(12, 7, 'Laporan Inventaris', 'laporan', 'fas fa-fw fa-box', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `databarang`
--
ALTER TABLE `databarang`
  ADD PRIMARY KEY (`id_barang`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_access_menu`
--
ALTER TABLE `user_access_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_menu`
--
ALTER TABLE `user_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_sub_menu`
--
ALTER TABLE `user_sub_menu`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `databarang`
--
ALTER TABLE `databarang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_access_menu`
--
ALTER TABLE `user_access_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `user_menu`
--
ALTER TABLE `user_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_sub_menu`
--
ALTER TABLE `user_sub_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
