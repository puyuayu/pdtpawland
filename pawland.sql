-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 14, 2025 at 05:51 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pawland`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `tambah_penitipan` (IN `uid` INT, IN `nama` VARCHAR(100), IN `jenis` VARCHAR(50), IN `masuk` DATE, IN `keluar` DATE, IN `tarif` DECIMAL(10,2))   BEGIN
    INSERT INTO penitipan (id_user, nama_hewan, jenis_hewan, tanggal_masuk, tanggal_keluar, biaya)
    VALUES (uid, nama, jenis, masuk, keluar, tarif);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_status_penitipan` (IN `p_id` INT, IN `p_status_baru` VARCHAR(50))   BEGIN
    UPDATE penitipan
    SET status = p_status_baru
    WHERE id = p_id;
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `hitung_biaya` (`masuk` DATE, `keluar` DATE, `tarif_harian` DECIMAL(10,2)) RETURNS DECIMAL(10,2) DETERMINISTIC BEGIN
    DECLARE durasi INT;
    SET durasi = DATEDIFF(keluar, masuk);
    RETURN durasi * tarif_harian;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `log_penitipan`
--

CREATE TABLE `log_penitipan` (
  `id` int NOT NULL,
  `penitipan_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `waktu_log` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `aksi` varchar(50) DEFAULT NULL,
  `status_lama` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `log_penitipan`
--

INSERT INTO `log_penitipan` (`id`, `penitipan_id`, `user_id`, `waktu_log`, `aksi`, `status_lama`) VALUES
(1, 5, 8, '2025-06-14 04:56:47', 'Update status ke \"Selesai\"', 'Diproses');

-- --------------------------------------------------------

--
-- Table structure for table `penitipan`
--

CREATE TABLE `penitipan` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `nama_hewan` varchar(100) NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `durasi_hari` int NOT NULL,
  `status` enum('Diproses','Selesai','Dibatalkan') DEFAULT 'Diproses',
  `tanggal_mulai` date DEFAULT NULL,
  `biaya_total` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `penitipan`
--

INSERT INTO `penitipan` (`id`, `user_id`, `nama_hewan`, `jenis`, `durasi_hari`, `status`, `tanggal_mulai`, `biaya_total`) VALUES
(1, 1, 'cina', 'kucink', 7, 'Diproses', '2025-06-13', 140000),
(2, 2, 'cina', 'kucink', 7, 'Selesai', '2025-06-06', 140000),
(3, 2, 'cina', 'kucink', 7, 'Diproses', '2025-06-21', 140000),
(4, 2, 'cina', 'Kucing', 7, 'Diproses', '2025-06-12', 140000),
(5, 8, 'agus', 'Kucing', 14, 'Selesai', '2025-06-18', 280000),
(7, 9, 'allisya', 'Kucing', 9, 'Diproses', '2025-06-13', 180000),
(8, 9, 'mili', 'kucing', 2, 'Diproses', '2025-06-14', 40000),
(9, 9, 'nana', 'kucing', 4, 'Diproses', '2025-06-14', 80000),
(10, 9, 'jeno', 'anjing', 7, 'Diproses', '2025-06-14', 210000),
(11, 10, 'nini', 'kucing', 2, 'Diproses', '2025-06-14', 40000),
(12, 10, 'coco', 'Anjing', 1, 'Diproses', '2025-06-14', 30000),
(13, 10, 'lala', 'Anjing', 1, 'Diproses', '2025-06-14', 30000),
(14, 10, 'dede', 'Anjing', 1, 'Diproses', '2025-06-14', 30000),
(15, 10, 'aye', 'Kucing', 1, 'Diproses', '2025-06-14', 20000);

--
-- Triggers `penitipan`
--
DELIMITER $$
CREATE TRIGGER `after_insert_penitipan` AFTER INSERT ON `penitipan` FOR EACH ROW BEGIN
    INSERT INTO log_penitipan (penitipan_id, user_id, aksi)
    VALUES (NEW.id, NEW.user_id, 'Tambah Penitipan');
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_update_status` AFTER UPDATE ON `penitipan` FOR EACH ROW BEGIN
    IF OLD.status <> NEW.status THEN
        INSERT INTO log_penitipan (penitipan_id, user_id, aksi, status_lama)
        VALUES (NEW.id, NEW.user_id, CONCAT('Update status ke "', NEW.status, '"'), OLD.status);
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `role`) VALUES
(1, 'all', 'all@gmail.com', '$2y$10$CsizI1L.bo0BG.m1FMJQRebiB8Oge0sIWjS5xT4VBDCwlFLW2chTq', 'user'),
(2, 'al', 'd@gmail.com', '$2y$10$JVu1sGf2lIqKR7vRoDjedOHN7XI7yz9v2bodDeJgEO9TaRJiXZvqi', 'user'),
(4, 'adin', 'adin@gmail.com', '$2y$10$U.MB3OT2o92YZNxfcM3lpe6.XPkyZE6CP4CApXdfuh2.CTOCuSIbC', 'admin'),
(8, 'algg', 'algg@gmail.com', '$2y$10$B9FBxN9JmP5C7ehkKVK4SuHp0l8gQm2i4UcdsIMm4wDr2uCTf7MWO', 'user'),
(9, 'ooo', 'o@gmail.com', '$2y$10$aCYYeEDSsUjTK/7rUcfyh.h2cKkHg6YbN4VmVqGVmjecDpGVDZw0i', 'user'),
(10, 'puyu', 'puyu@gmail.com', '$2y$10$LKK4s3k0Pzneq/6YiCwpzOEEbdAG0c3JT9d6ststqK1kYi4F.vKZu', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `log_penitipan`
--
ALTER TABLE `log_penitipan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penitipan`
--
ALTER TABLE `penitipan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `log_penitipan`
--
ALTER TABLE `log_penitipan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `penitipan`
--
ALTER TABLE `penitipan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
