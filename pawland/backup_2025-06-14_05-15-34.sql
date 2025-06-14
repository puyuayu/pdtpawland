-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: pawland
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `log_penitipan`
--

DROP TABLE IF EXISTS `log_penitipan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_penitipan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `penitipan_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `waktu_log` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `aksi` varchar(50) DEFAULT NULL,
  `status_lama` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_penitipan`
--

LOCK TABLES `log_penitipan` WRITE;
/*!40000 ALTER TABLE `log_penitipan` DISABLE KEYS */;
INSERT INTO `log_penitipan` VALUES (1,5,8,'2025-06-14 04:56:47','Update status ke \"Selesai\"','Diproses');
/*!40000 ALTER TABLE `log_penitipan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `penitipan`
--

DROP TABLE IF EXISTS `penitipan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `penitipan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `nama_hewan` varchar(100) NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `durasi_hari` int NOT NULL,
  `status` enum('Diproses','Selesai','Dibatalkan') DEFAULT 'Diproses',
  `tanggal_mulai` date DEFAULT NULL,
  `biaya_total` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penitipan`
--

LOCK TABLES `penitipan` WRITE;
/*!40000 ALTER TABLE `penitipan` DISABLE KEYS */;
INSERT INTO `penitipan` VALUES (1,1,'cina','kucink',7,'Diproses','2025-06-13',140000),(2,2,'cina','kucink',7,'Selesai','2025-06-06',140000),(3,2,'cina','kucink',7,'Diproses','2025-06-21',140000),(4,2,'cina','Kucing',7,'Diproses','2025-06-12',140000),(5,8,'agus','Kucing',14,'Selesai','2025-06-18',280000),(7,9,'allisya','Kucing',9,'Diproses','2025-06-13',180000),(8,9,'mili','kucing',2,'Diproses','2025-06-14',40000),(9,9,'nana','kucing',4,'Diproses','2025-06-14',80000),(10,9,'jeno','anjing',7,'Diproses','2025-06-14',210000),(11,10,'nini','kucing',2,'Diproses','2025-06-14',40000),(12,10,'coco','Anjing',1,'Diproses','2025-06-14',30000),(13,10,'lala','Anjing',1,'Diproses','2025-06-14',30000),(14,10,'dede','Anjing',1,'Diproses','2025-06-14',30000),(15,10,'aye','Kucing',1,'Diproses','2025-06-14',20000);
/*!40000 ALTER TABLE `penitipan` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `after_insert_penitipan` AFTER INSERT ON `penitipan` FOR EACH ROW BEGIN
    INSERT INTO log_penitipan (penitipan_id, user_id, aksi)
    VALUES (NEW.id, NEW.user_id, 'Tambah Penitipan');
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `after_update_status` AFTER UPDATE ON `penitipan` FOR EACH ROW BEGIN
    IF OLD.status <> NEW.status THEN
        INSERT INTO log_penitipan (penitipan_id, user_id, aksi, status_lama)
        VALUES (NEW.id, NEW.user_id, CONCAT('Update status ke "', NEW.status, '"'), OLD.status);
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'all','all@gmail.com','$2y$10$CsizI1L.bo0BG.m1FMJQRebiB8Oge0sIWjS5xT4VBDCwlFLW2chTq','user'),(2,'al','d@gmail.com','$2y$10$JVu1sGf2lIqKR7vRoDjedOHN7XI7yz9v2bodDeJgEO9TaRJiXZvqi','user'),(4,'adin','adin@gmail.com','$2y$10$U.MB3OT2o92YZNxfcM3lpe6.XPkyZE6CP4CApXdfuh2.CTOCuSIbC','admin'),(8,'algg','algg@gmail.com','$2y$10$B9FBxN9JmP5C7ehkKVK4SuHp0l8gQm2i4UcdsIMm4wDr2uCTf7MWO','user'),(9,'ooo','o@gmail.com','$2y$10$aCYYeEDSsUjTK/7rUcfyh.h2cKkHg6YbN4VmVqGVmjecDpGVDZw0i','user'),(10,'puyu','puyu@gmail.com','$2y$10$LKK4s3k0Pzneq/6YiCwpzOEEbdAG0c3JT9d6ststqK1kYi4F.vKZu','user');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-14 12:15:34
