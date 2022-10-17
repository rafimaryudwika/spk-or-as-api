-- MySQL dump 10.13  Distrib 8.0.30, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: spk_test
-- ------------------------------------------------------
-- Server version	8.0.30-0ubuntu0.20.04.2

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
-- Table structure for table `bidang_fak`
--

DROP TABLE IF EXISTS `bidang_fak`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bidang_fak` (
  `id_bf` int NOT NULL,
  `bidang_fak` varchar(25) NOT NULL,
  PRIMARY KEY (`id_bf`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bidang_fak`
--

/*!40000 ALTER TABLE `bidang_fak` DISABLE KEYS */;
INSERT INTO `bidang_fak` VALUES (1,'Saintek'),(2,'Soshum');
/*!40000 ALTER TABLE `bidang_fak` ENABLE KEYS */;

--
-- Table structure for table `bidang_fakultas`
--

DROP TABLE IF EXISTS `bidang_fakultas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bidang_fakultas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bidang_fakultas`
--

/*!40000 ALTER TABLE `bidang_fakultas` DISABLE KEYS */;
/*!40000 ALTER TABLE `bidang_fakultas` ENABLE KEYS */;

--
-- Table structure for table `detail_info_t1`
--

DROP TABLE IF EXISTS `detail_info_t1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detail_info_t1` (
  `tipe_info1` int NOT NULL,
  `nama_info` varchar(50) NOT NULL,
  `info_sc` varchar(50) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`tipe_info1`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_info_t1`
--

/*!40000 ALTER TABLE `detail_info_t1` DISABLE KEYS */;
INSERT INTO `detail_info_t1` VALUES (1,'Jenis Tes Bakat','jenis_tes_bakat','2022-07-12 11:45:16','2022-07-12 11:45:16'),(2,'Kelompok FGDeh','kelompok_f_g_deh','2022-07-12 11:53:32','2022-07-12 11:52:46');
/*!40000 ALTER TABLE `detail_info_t1` ENABLE KEYS */;

--
-- Table structure for table `detail_info_t2`
--

DROP TABLE IF EXISTS `detail_info_t2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detail_info_t2` (
  `tipe_info2` int NOT NULL,
  `nama_info` varchar(50) NOT NULL,
  `info_sc` varchar(50) NOT NULL,
  `updated_at` timestamp NOT NULL,
  `created_at` timestamp NOT NULL,
  PRIMARY KEY (`tipe_info2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_info_t2`
--

/*!40000 ALTER TABLE `detail_info_t2` DISABLE KEYS */;
/*!40000 ALTER TABLE `detail_info_t2` ENABLE KEYS */;

--
-- Table structure for table `detail_info_t3`
--

DROP TABLE IF EXISTS `detail_info_t3`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detail_info_t3` (
  `tipe_info3` int NOT NULL,
  `nama_info` varchar(50) NOT NULL,
  `info_sc` varchar(50) NOT NULL,
  `updated_at` timestamp NOT NULL,
  `created_at` timestamp NOT NULL,
  PRIMARY KEY (`tipe_info3`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_info_t3`
--

/*!40000 ALTER TABLE `detail_info_t3` DISABLE KEYS */;
/*!40000 ALTER TABLE `detail_info_t3` ENABLE KEYS */;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;

--
-- Table structure for table `fakultas`
--

DROP TABLE IF EXISTS `fakultas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fakultas` (
  `id_f` int NOT NULL,
  `fakultas` varchar(100) NOT NULL,
  `id_bf` int NOT NULL,
  PRIMARY KEY (`id_f`),
  KEY `id_bf` (`id_bf`),
  CONSTRAINT `fakultas_ibfk_1` FOREIGN KEY (`id_bf`) REFERENCES `bidang_fak` (`id_bf`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fakultas`
--

/*!40000 ALTER TABLE `fakultas` DISABLE KEYS */;
INSERT INTO `fakultas` VALUES (1,'Hukum',2),(2,'Pertanian',1),(3,'Kedokteran',1),(4,'MIPA',1),(5,'Ekonomi',2),(6,'Peternakan',1),(7,'Ilmu Budaya',2),(8,'Ilmu Sosial dan Ilmu Politik',2),(9,'Teknik',1),(10,'Farmasi',1),(11,'Teknologi Pertanian',1),(12,'Kesehatan Masyarakat',1),(13,'Keperawatan',1),(14,'Kedokteran Gigi',1),(15,'Teknologi Informasi',1);
/*!40000 ALTER TABLE `fakultas` ENABLE KEYS */;

--
-- Table structure for table `gender`
--

DROP TABLE IF EXISTS `gender`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gender` (
  `id_g` int NOT NULL,
  `gender` varchar(20) NOT NULL,
  PRIMARY KEY (`id_g`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gender`
--

/*!40000 ALTER TABLE `gender` DISABLE KEYS */;
INSERT INTO `gender` VALUES (1,'Laki-Laki'),(2,'Perempuan');
/*!40000 ALTER TABLE `gender` ENABLE KEYS */;

--
-- Table structure for table `info_peserta_t1`
--

DROP TABLE IF EXISTS `info_peserta_t1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `info_peserta_t1` (
  `id` int NOT NULL,
  `nim` int NOT NULL,
  `tipe_info1` int NOT NULL,
  `informasi` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tipe_info1` (`tipe_info1`),
  KEY `nim` (`nim`),
  CONSTRAINT `info_peserta_t1_ibfk_1` FOREIGN KEY (`tipe_info1`) REFERENCES `detail_info_t1` (`tipe_info1`),
  CONSTRAINT `info_peserta_t1_ibfk_2` FOREIGN KEY (`nim`) REFERENCES `peserta_t1` (`nim`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `info_peserta_t1`
--

/*!40000 ALTER TABLE `info_peserta_t1` DISABLE KEYS */;
/*!40000 ALTER TABLE `info_peserta_t1` ENABLE KEYS */;

--
-- Table structure for table `info_peserta_t2`
--

DROP TABLE IF EXISTS `info_peserta_t2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `info_peserta_t2` (
  `id` int NOT NULL,
  `nim` int NOT NULL,
  `tipe_info2` int NOT NULL,
  `informasi` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tipe_info2` (`tipe_info2`),
  KEY `nim` (`nim`),
  CONSTRAINT `info_peserta_t2_ibfk_1` FOREIGN KEY (`tipe_info2`) REFERENCES `detail_info_t2` (`tipe_info2`),
  CONSTRAINT `info_peserta_t2_ibfk_2` FOREIGN KEY (`nim`) REFERENCES `peserta_t2` (`nim`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `info_peserta_t2`
--

/*!40000 ALTER TABLE `info_peserta_t2` DISABLE KEYS */;
/*!40000 ALTER TABLE `info_peserta_t2` ENABLE KEYS */;

--
-- Table structure for table `info_peserta_t3`
--

DROP TABLE IF EXISTS `info_peserta_t3`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `info_peserta_t3` (
  `id` int NOT NULL,
  `nim` int NOT NULL,
  `tipe_info3` int NOT NULL,
  `informasi` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tipe_info3` (`tipe_info3`),
  KEY `nim` (`nim`),
  CONSTRAINT `info_peserta_t3_ibfk_1` FOREIGN KEY (`tipe_info3`) REFERENCES `detail_info_t3` (`tipe_info3`),
  CONSTRAINT `info_peserta_t3_ibfk_2` FOREIGN KEY (`nim`) REFERENCES `peserta_t3` (`nim`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `info_peserta_t3`
--

/*!40000 ALTER TABLE `info_peserta_t3` DISABLE KEYS */;
/*!40000 ALTER TABLE `info_peserta_t3` ENABLE KEYS */;

--
-- Table structure for table `jurusan`
--

DROP TABLE IF EXISTS `jurusan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jurusan` (
  `id_j` int NOT NULL,
  `id_f` int NOT NULL,
  `jurusan` varchar(100) NOT NULL,
  PRIMARY KEY (`id_j`,`id_f`),
  KEY `id_f` (`id_f`),
  CONSTRAINT `jurusan_ibfk_1` FOREIGN KEY (`id_f`) REFERENCES `fakultas` (`id_f`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jurusan`
--

/*!40000 ALTER TABLE `jurusan` DISABLE KEYS */;
INSERT INTO `jurusan` VALUES (11,1,'Ilmu Hukum'),(21,2,'Agroteknologi'),(22,2,'Agribisnis'),(23,2,'Ilmu Tanah'),(25,2,'Proteksi Tanaman'),(27,2,'Penyuluhan Pertanian'),(31,3,'Kedokteran'),(32,3,'Psikologi'),(33,3,'Kebidanan'),(41,4,'Kimia'),(42,4,'Biologi'),(43,4,'Matematika'),(44,4,'Fisika'),(51,5,'Ilmu Ekonomi/Ekonomi Pembangunan'),(52,5,'Manajemen'),(53,5,'Akuntansi'),(61,6,'Peternakan'),(71,7,'Ilmu Sejarah'),(72,7,'Sastra Indonesia'),(73,7,'Sastra Inggris'),(74,7,'Sastra Minangkabau'),(75,7,'Sastra Jepang'),(81,8,'Sosiologi'),(82,8,'Antropologi Sosial'),(83,8,'Ilmu Politik'),(84,8,'Ilmu Administrasi Negara'),(85,8,'Ilmu Hubungan Internasional'),(86,8,'Ilmu Komunikasi'),(91,9,'Teknik Mesin'),(92,9,'Teknik Sipil'),(93,9,'Teknik Industri'),(94,9,'Teknik Lingkungan'),(95,9,'Teknik Elektro'),(101,10,'Farmasi'),(111,11,'Teknik Pertanian'),(112,11,'Teknologi Hasil Pertanian'),(113,11,'Teknologi Industri Pertanian'),(121,12,'Ilmu Kesehatan Masyarakat'),(122,12,'Ilmu Gizi'),(131,13,'Ilmu Keperawatan'),(141,14,'Kedokteran Gigi'),(151,15,'Sistem Komputer'),(152,15,'Sistem Informasi'),(510,5,'D3 Pemasaran'),(530,5,'D3 Manajemen Perkantoran'),(540,5,'D3 Keuangan');
/*!40000 ALTER TABLE `jurusan` ENABLE KEYS */;

--
-- Table structure for table `kriteria_t1`
--

DROP TABLE IF EXISTS `kriteria_t1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kriteria_t1` (
  `id_k1` int NOT NULL,
  `kriteria` varchar(25) NOT NULL,
  `bobot` float NOT NULL,
  `k_sc` varchar(25) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `kode` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id_k1`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kriteria_t1`
--

/*!40000 ALTER TABLE `kriteria_t1` DISABLE KEYS */;
INSERT INTO `kriteria_t1` VALUES (1,'Forum Group Discussion',0.333,'forum_group_discussion','2021-09-13 03:11:00','2022-08-09 12:47:31','FGD'),(2,'Wawancara',0.333,'wawancara','2021-09-13 03:15:00','2022-08-09 12:47:36','W'),(3,'Tes Bakat',0.333,'tes_bakat','2021-09-13 03:20:00','2022-08-09 12:47:41','TB');
/*!40000 ALTER TABLE `kriteria_t1` ENABLE KEYS */;

--
-- Table structure for table `kriteria_t2`
--

DROP TABLE IF EXISTS `kriteria_t2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kriteria_t2` (
  `id_k2` int NOT NULL,
  `kriteria` varchar(25) NOT NULL,
  `bobot` float NOT NULL,
  `k_sc` varchar(25) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `kode` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id_k2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kriteria_t2`
--

/*!40000 ALTER TABLE `kriteria_t2` DISABLE KEYS */;
INSERT INTO `kriteria_t2` VALUES (1,'PSDO',0.333,'p_s_d_o','2021-09-15 03:11:00','2022-08-21 08:03:33','PS'),(2,'Fotografi',0.333,'fotografi','2021-09-15 03:15:00','2022-08-21 08:03:39','FT'),(3,'Produksi',0.333,'produksi','2021-09-15 03:18:00','2022-08-21 08:03:45','PR');
/*!40000 ALTER TABLE `kriteria_t2` ENABLE KEYS */;

--
-- Table structure for table `kriteria_t3`
--

DROP TABLE IF EXISTS `kriteria_t3`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kriteria_t3` (
  `id_k3` int NOT NULL,
  `kriteria` varchar(25) NOT NULL,
  `bobot` float NOT NULL,
  `k_sc` varchar(25) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `kode` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id_k3`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kriteria_t3`
--

/*!40000 ALTER TABLE `kriteria_t3` DISABLE KEYS */;
INSERT INTO `kriteria_t3` VALUES (1,'PSDO',0.333,'p_s_d_o','2022-07-17 01:54:35','2022-08-25 09:38:04','PS'),(2,'Fotografi',0.333,'fotografi','2022-08-25 09:46:03','2022-08-25 09:46:03','FT'),(3,'Produksi',0.333,'produksi','2022-08-25 09:46:13','2022-08-25 09:46:13','PR');
/*!40000 ALTER TABLE `kriteria_t3` ENABLE KEYS */;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (2,'2014_10_12_100000_create_password_resets_table',2),(3,'2019_08_19_000000_create_failed_jobs_table',3),(4,'2018_08_08_100000_create_telescope_entries_table',4),(5,'2019_12_14_000001_create_personal_access_tokens_table',4),(6,'2021_09_09_181323_create_pendaftars_table',4),(7,'2021_09_09_182346_create_bidang_fakultas_table',4),(8,'2014_10_12_200000_add_two_factor_columns_to_users_table',5),(9,'2022_10_10_080950_user_role',5);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

--
-- Table structure for table `nilai_t1`
--

DROP TABLE IF EXISTS `nilai_t1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nilai_t1` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nim` int NOT NULL,
  `id_sk1` int DEFAULT NULL,
  `nilai` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_sk1` (`id_sk1`),
  KEY `nim` (`nim`),
  CONSTRAINT `nilai_t1_ibfk_1` FOREIGN KEY (`id_sk1`) REFERENCES `sub_kriteria_t1` (`id_sk1`),
  CONSTRAINT `nilai_t1_ibfk_2` FOREIGN KEY (`nim`) REFERENCES `peserta_t1` (`nim`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nilai_t1`
--

/*!40000 ALTER TABLE `nilai_t1` DISABLE KEYS */;
INSERT INTO `nilai_t1` VALUES (1,1810442048,11,25),(2,1810442048,12,35),(3,1810442048,13,30),(4,1810442048,21,76),(5,1810442048,31,58),(6,1810522063,11,40),(7,1810522063,12,30),(8,1810522063,13,30),(9,1810522063,21,80),(10,1810522063,31,84),(11,1810711016,11,0),(12,1810711016,12,0),(13,1810711016,13,0),(14,1810711016,21,48),(15,1810711016,31,18),(16,1810953036,11,35),(17,1810953036,12,20),(18,1810953036,13,30),(19,1810953036,21,66),(20,1810953036,31,72),(21,1900532050,11,10),(22,1900532050,12,20),(23,1900532050,13,0),(24,1900532050,21,66),(25,1900532050,31,38),(26,1910321002,11,40),(27,1910321002,12,30),(28,1910321002,13,30),(29,1910321002,21,76),(30,1910321002,31,75),(31,1910522049,11,35),(32,1910522049,12,30),(33,1910522049,13,25),(34,1910522049,21,78),(35,1910522049,31,64),(36,1910752003,11,30),(37,1910752003,12,20),(38,1910752003,13,25),(39,1910752003,21,84),(40,1910752003,31,75),(41,1910752024,11,25),(42,1910752024,12,20),(43,1910752024,13,25),(44,1910752024,21,77),(45,1910752024,31,67),(46,1911522003,11,40),(47,1911522003,12,30),(48,1911522003,13,30),(49,1911522003,21,84),(50,1911522003,31,75);
/*!40000 ALTER TABLE `nilai_t1` ENABLE KEYS */;

--
-- Table structure for table `nilai_t2`
--

DROP TABLE IF EXISTS `nilai_t2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nilai_t2` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nim` int NOT NULL,
  `id_sk2` int NOT NULL,
  `nilai` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_sk2` (`id_sk2`),
  KEY `nim` (`nim`),
  CONSTRAINT `nilai_t2_ibfk_1` FOREIGN KEY (`id_sk2`) REFERENCES `sub_kriteria_t2` (`id_sk2`),
  CONSTRAINT `nilai_t2_ibfk_2` FOREIGN KEY (`nim`) REFERENCES `peserta_t2` (`nim`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nilai_t2`
--

/*!40000 ALTER TABLE `nilai_t2` DISABLE KEYS */;
INSERT INTO `nilai_t2` VALUES (1,1810442048,11,100),(2,1810442048,12,80),(3,1810442048,13,69),(4,1810442048,21,100),(5,1810442048,22,69),(6,1810442048,23,74),(7,1810442048,24,65),(8,1810442048,31,100),(9,1810442048,32,18),(10,1810442048,33,18),(11,1810442048,34,18),(12,1810442048,35,15),(13,1810442048,36,20),(14,1810522063,11,100),(15,1810522063,12,80),(16,1810522063,13,83),(17,1810522063,21,100),(18,1810522063,22,75),(19,1810522063,23,80),(20,1810522063,24,70),(21,1810522063,31,70),(22,1810522063,32,20),(23,1810522063,33,20),(24,1810522063,34,20),(25,1810522063,35,15),(26,1810522063,36,19),(27,1810953036,11,100),(28,1810953036,12,80),(29,1810953036,13,66),(30,1810953036,21,70),(31,1810953036,22,85),(32,1810953036,23,75),(33,1810953036,24,78),(34,1810953036,31,50),(35,1810953036,32,17),(36,1810953036,33,18),(37,1810953036,34,20),(38,1810953036,35,15),(39,1810953036,36,18),(40,1910321002,11,100),(41,1910321002,12,80),(42,1910321002,13,57),(43,1910321002,21,100),(44,1910321002,22,50),(45,1910321002,23,70),(46,1910321002,24,62),(47,1910321002,31,100),(48,1910321002,32,20),(49,1910321002,33,15),(50,1910321002,34,20),(51,1910321002,35,17),(52,1910321002,36,15),(53,1910522049,11,70),(54,1910522049,12,80),(55,1910522049,13,75),(56,1910522049,21,70),(57,1910522049,22,70),(58,1910522049,23,74),(59,1910522049,24,65),(60,1910522049,31,50),(61,1910522049,32,18),(62,1910522049,33,17),(63,1910522049,34,18),(64,1910522049,35,15),(65,1910522049,36,20),(66,1910752003,11,0),(67,1910752003,12,0),(68,1910752003,13,0),(69,1910752003,21,100),(70,1910752003,22,67),(71,1910752003,23,76),(72,1910752003,24,63),(73,1910752003,31,70),(74,1910752003,32,18),(75,1910752003,33,15),(76,1910752003,34,20),(77,1910752003,35,15),(78,1910752003,36,18),(79,1910752024,11,0),(80,1910752024,12,0),(81,1910752024,13,0),(82,1910752024,21,80),(83,1910752024,22,40),(84,1910752024,23,70),(85,1910752024,24,60),(86,1910752024,31,100),(87,1910752024,32,10),(88,1910752024,33,20),(89,1910752024,34,10),(90,1910752024,35,10),(91,1910752024,36,19),(92,1911522003,11,0),(93,1911522003,12,0),(94,1911522003,13,0),(95,1911522003,21,70),(96,1911522003,22,80),(97,1911522003,23,40),(98,1911522003,24,97),(99,1911522003,31,50),(100,1911522003,32,0),(101,1911522003,33,18),(102,1911522003,34,18),(103,1911522003,35,15),(104,1911522003,36,20);
/*!40000 ALTER TABLE `nilai_t2` ENABLE KEYS */;

--
-- Table structure for table `nilai_t3`
--

DROP TABLE IF EXISTS `nilai_t3`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nilai_t3` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nim` int NOT NULL,
  `id_sk3` int NOT NULL,
  `nilai` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_sk3` (`id_sk3`),
  KEY `nim` (`nim`),
  CONSTRAINT `nilai_t3_ibfk_1` FOREIGN KEY (`id_sk3`) REFERENCES `sub_kriteria_t3` (`id_sk3`),
  CONSTRAINT `nilai_t3_ibfk_2` FOREIGN KEY (`nim`) REFERENCES `peserta_t3` (`nim`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nilai_t3`
--

/*!40000 ALTER TABLE `nilai_t3` DISABLE KEYS */;
INSERT INTO `nilai_t3` VALUES (1,1810442048,11,70),(2,1810442048,12,68),(3,1810442048,13,67),(4,1810442048,14,67),(5,1810442048,21,78),(6,1810442048,22,82),(7,1810442048,23,81),(8,1810442048,31,68),(9,1810442048,32,65),(10,1810442048,33,70),(11,1810442048,34,67),(12,1810442048,35,64),(13,1810953036,11,75),(14,1810953036,12,73),(15,1810953036,13,73),(16,1810953036,14,73),(17,1810953036,21,64),(18,1810953036,22,67),(19,1810953036,23,71),(20,1810953036,31,78),(21,1810953036,32,80),(22,1810953036,33,76),(23,1810953036,34,79),(24,1810953036,35,75),(25,1810522063,11,77),(26,1810522063,12,78),(27,1810522063,13,79),(28,1810522063,14,78),(29,1810522063,21,78),(30,1810522063,22,70),(31,1810522063,23,74),(32,1810522063,31,70),(33,1810522063,32,67),(34,1810522063,33,71),(35,1810522063,34,74),(36,1810522063,35,69),(37,1910321002,11,63),(38,1910321002,12,65),(39,1910321002,13,65),(40,1910321002,14,67),(41,1910321002,21,78),(42,1910321002,22,75),(43,1910321002,23,76),(44,1910321002,31,70),(45,1910321002,32,75),(46,1910321002,33,78),(47,1910321002,34,76),(48,1910321002,35,79),(49,1910522049,11,63),(50,1910522049,12,65),(51,1910522049,13,65),(52,1910522049,14,67),(53,1910522049,21,75),(54,1910522049,22,71),(55,1910522049,23,68),(56,1910522049,31,70),(57,1910522049,32,75),(58,1910522049,33,78),(59,1910522049,34,76),(60,1910522049,35,79),(61,1910752003,11,63),(62,1910752003,12,65),(63,1910752003,13,65),(64,1910752003,14,67),(65,1910752003,21,77),(66,1910752003,22,79),(67,1910752003,23,82),(68,1910752003,31,70),(69,1910752003,32,75),(70,1910752003,33,78),(71,1910752003,34,76),(72,1910752003,35,79),(73,1911522003,11,69),(74,1911522003,12,70),(75,1911522003,13,76),(76,1911522003,14,69),(77,1911522003,21,61),(78,1911522003,22,58),(79,1911522003,23,63),(80,1911522003,31,69),(81,1911522003,32,66),(82,1911522003,33,70),(83,1911522003,34,74),(84,1911522003,35,73);
/*!40000 ALTER TABLE `nilai_t3` ENABLE KEYS */;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;

--
-- Table structure for table `pendaftar`
--

DROP TABLE IF EXISTS `pendaftar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pendaftar` (
  `tgl_daftar` timestamp NULL DEFAULT NULL,
  `nim` int NOT NULL,
  `email` varchar(200) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `panggilan` varchar(30) NOT NULL,
  `id_g` int NOT NULL,
  `tempat_lahir` varchar(100) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `alamat_pdg` varchar(300) NOT NULL,
  `no_hp` bigint DEFAULT NULL,
  `id_j` int NOT NULL,
  `id_f` int NOT NULL,
  `daftar_ulang` tinyint(1) NOT NULL,
  PRIMARY KEY (`nim`),
  KEY `id_g` (`id_g`),
  KEY `id_j` (`id_j`,`id_f`),
  CONSTRAINT `pendaftar_ibfk_1` FOREIGN KEY (`id_g`) REFERENCES `gender` (`id_g`),
  CONSTRAINT `pendaftar_ibfk_2` FOREIGN KEY (`id_j`, `id_f`) REFERENCES `jurusan` (`id_j`, `id_f`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pendaftar`
--

/*!40000 ALTER TABLE `pendaftar` DISABLE KEYS */;
INSERT INTO `pendaftar` VALUES ('2020-01-23 16:09:47',1810111061,'alexisnawawi@gmail.com','Adya Rubiati Alexis','Leksi',2,'Bukittinggi','2000-02-24','Jl. DPR Utama No 50',82268434353,11,1,1),('2020-01-16 01:51:31',1810111065,'linamileniaa@gmail.com','Lina milenia','Lina',2,'Sipangkur, Dharmasraya','2000-06-29','Cupak tangah No. 48',82250016501,11,1,1),('2020-01-05 17:15:54',1810112018,'herp2800@gmail.com','Ibnu Rafli Ramadhan','Ibnu',1,'Padang','2000-11-28','Jl Kakak Tua no 33B, Air Tawar Barat, Padang Utara',81263104411,11,1,0),('2020-01-01 22:14:50',1810112048,'Annisaindra28@gmail.com','Annisa indra','Nisa',2,'Batusangkar','1999-10-30','Pauah',82384332733,11,1,1),('2020-01-15 22:19:30',1810112077,'diniyulianti80@gmail.com','dini yulianti','dini',2,'medan','2020-07-12','jl drs moh hatta',82287527632,11,1,1),('2020-01-14 15:13:32',1810112231,'angelymarselyna@gmail.com','Angely Marselyna','Jee',2,'Padang panjang','1999-02-15','Kapalo koto',82169140516,11,1,0),('2020-01-21 01:07:03',1810231005,'dhasvitanianiel@gmail.com','Dhasvitania niel ','Tania ',2,'padang','2000-09-17','Parak laweh',82384626163,23,2,1),('2020-01-23 00:49:22',1810231015,'gutiberyaadinda2000@gmail.com','guti berta adinda','guti',2,'sungai aro','2000-02-20','simpang pasia,belakang alvanza',82286976159,23,2,0),('2020-01-25 18:25:53',1810232007,'iqbaldaffa282@gmail.com','Iqbal Daffa Rifqi ','Daffa',1,'Padang','2000-02-28','Jl. Kampung Melayu II no. 31 Pegambiran',81370493571,23,2,1),('2020-01-21 00:25:05',1810232012,'s.yuni55@yahoo.com','Yuni Sri Astuti','Yuni',2,'Katimahar','1998-06-27','Jamsek',82389584554,23,2,0),('2020-01-21 00:13:52',1810232013,'akramtriagusti21@gmail.com','Akram Tri Agusti','Akram',1,'Paninggahan','1999-08-28','Durian taruang',81372723347,23,2,1),('2020-01-26 13:58:24',1810232018,'azrifatahillah011@gmail.com','Azri fatahillah','Azri',1,'Kampar','1999-09-15','Parak pisang',82285581622,23,2,1),('2020-01-20 14:30:54',1810232027,'rahmatikbalpdg19@gmail.com','Rahmat ikbal','Ikba',1,'Padang','2000-09-30','Tabing',89628004470,23,2,1),('2020-01-21 02:09:58',1810233004,'khairahagustia31@gmail.com','Khairah Agustia','Khair',2,'Solok','2000-08-31','Jalan simp. Gadut, kecamatan lubuk kilangan',85363546936,23,2,1),('2020-01-20 23:30:56',1810233005,'viraputriindriani16@gmail.com','Vira putri indriani','Vira',2,'Padang','1999-12-27','jln bypass km12 kabun indah rt03 rw 01',82344704386,23,2,1),('2020-01-06 17:07:34',1810233010,'nachdasalsabilaa@yahoo.com','Nachda Salsabila','Nachda',2,'Batusangkar','2020-01-09','Jl moh hatta',81364386599,23,2,1),('2020-01-13 23:25:54',1810332011,'rezkiamelliaputri@gmail.com','Rezki amellia putri','Kiki',2,'Solok','2020-03-10','Jalan dobi simpang enam pondok padang barat',82276734259,33,3,0),('2020-01-14 19:07:44',1810422005,'yokaafriska@gmail.com','Afriska Yoka Parista','Afriska',2,'Batam','2000-04-04','Jl moh hatta',82112927040,42,4,1),('2020-01-19 22:32:35',1810423018,'raremarket07@gmail.com','Hafizhah Rahmayani ','Hafi ',2,'Padang ','2000-10-04','Jalan Delima 9 No. 319 Blok A Perumnas Belimbing Kuranji',89514829264,42,4,0),('2019-12-31 20:01:08',1810431009,'salmihabibatus@gmail.com','Habibatus Salmi','Amik',2,'Sungai Sariak','2000-06-14','Simpang pasia',81310969266,43,4,1),('2019-12-31 14:35:49',1810441005,'auliafirma@gmail.com','Aulia Firma','Firma',2,'Jakarta ','2000-10-04','Pasar baru',81389152198,44,4,1),('2020-01-23 00:12:25',1810441032,'luthfiaaqilaabrar@gmail.com','Luthfia Aqila Abrar','Lulu',2,'Palembang','2000-07-14','Jl. Cunadak Indah V Perum Arianta Blok B No.02 Anduring Kuranji ',82172904561,44,4,1),('2020-01-02 15:55:15',1810442048,'dhiyahaqila14@gmail.com','Dhiyah aqila putri','Dhiyah',2,'Padang','2001-07-14','Perum. Bumi pisang jl pisang rajo no.40 padang',82173578975,44,4,1),('2020-01-21 10:51:00',1810512003,'shoniaindirigani10@gmail.com','Shonia Indiri Gani','Niaa',2,'Padang','2000-09-10','Kp. Jua',81364568793,51,5,0),('2020-01-20 22:58:06',1810512031,'ypntakrn@gmail.com','Yupinta kurnia','Pinta',2,'Bukittinggi','2000-09-02','Jl. M. Hatta no 25, pauh , padang',83182243286,51,5,1),('2020-01-19 22:02:27',1810521028,'indahsarirahmadani6@gmail.com','Indah sahri ramadani','Indah',2,'Pauh kambar','1999-12-09','Pauh kapalo koto',82288345131,52,5,0),('2020-01-17 22:40:12',1810522028,'arza5th@gmail.com','Hidayatur Rahman Arza','Rahman',1,'Bukittinggi','2001-04-05','Kampung Duri',81259695944,52,5,1),('2020-01-14 00:02:43',1810522032,'ekap3473@gmail.com','Eka Putri Reanita Mastur','Putri',2,'Lampung','1999-01-21','Jalan kesehatan, komp. depkes, Gadut',82288777915,52,5,0),('2020-01-17 23:16:35',1810522038,'mfakhrinaufal99@gmail.com','Muhammad fakhri naufal','Nopal',1,'Bukittinggi','1999-02-19','Kp. Duri',85216797919,52,5,1),('2020-01-07 21:36:27',1810522044,'r.novriyanti25@gmail.com','Rima Novriyanti','Rima',2,'Padang Panjang','1999-10-01','Jl. M. Hatta. No 25, pauh, padang',895618294683,52,5,1),('2020-01-18 10:40:40',1810522063,'alsyahzihamdal@gmail.com','Hamdal Al-Syahzi','Hamdal',1,'Pariaman','2000-09-27','Jln.M.Hatta,Pasa Baru, Cupak tangah, Pauh, Padang. ',82391271990,52,5,1),('2020-01-01 14:33:45',1810531010,'egiagre@gmail.com','Regita Annisa Agre','Egi',2,'Padang','2000-12-19','Komplek Cendana Thp II Blok A/2 Mata Air, Padang',81947898409,53,5,1),('2020-01-14 01:05:57',1810531037,'luckyperdanahakim12@gmail.com','Lucky Perdana Hakim ','Lucky ',1,'Biaro','2000-03-13','Pasar baru ',81352591542,53,5,1),('2020-01-06 21:39:48',1810532062,'afridafatiaa@gmail.com','Afrida Fatiha Azani','Afrida',2,'Jakarta','2000-04-14','Jln.dr.moh hatta',85319291523,53,5,0),('2020-01-20 01:31:29',1810612032,'dina.yprstka21@gmail.com','Dina Yuprastika','Dina',2,'Cingkaring','1999-12-05','Jl.Moh Hatta No.29 Pasar Baru, Pauh',82384849858,61,6,1),('2020-01-25 16:16:14',1810613006,'najlanaufal8@gmail.com','Naufal Najla','Naufal',1,'Jakarta','1999-12-08','Komplek Palimo Indah',81270587548,61,6,1),('2020-01-19 18:04:50',1810711003,'futriasyary24@gmail.com','FUTRI ASYARY','FUTRI',2,'BUKITTINGGI','2020-01-24','JALAN MOH.HATTA NO 05, KAPALO KOTO',85213946573,71,7,1),('2020-01-01 19:37:54',1810711016,'fzndptra@gmail.com','Fauzan Dwiputra Alwi','Fauzan',1,'Padang','1998-10-26','Komplek lubul gading permai 6',82287748505,71,7,1),('2020-01-13 12:43:07',1810722001,'billafahci@gmail.com','Nabilla Hanifah Suci','Suci',2,'Jakarta','2000-12-15','Kos Putri Dinda, jl. Dr. Moh. Hatta No.92 Rt.03/Rw.02, Kapalo Koto, Pauh, Padang ',81267978166,72,7,0),('2019-12-31 16:27:58',1810722015,'aldiuswansyaf@gmail.com','M Aldhi Uswansyaf','Aldi',1,'Bukittinggi','2000-03-04','Pasar Baru',82288790131,72,7,1),('2020-01-22 20:59:21',1810722023,'uhfizhah@gmail.com','Ummul Hafizhah','Fizhah',2,'Solok','2000-07-30','Pasar Baru,Limau Manis,Padang',81261295570,72,7,1),('2020-01-13 23:51:48',1810722024,'delfiyarahayu16@gmail.com','Delfiya Rahayu','Piya',2,'Bukittinggi','1998-12-16','Pasar baru',89601313782,72,7,0),('2020-01-18 09:49:01',1810722038,'annisa.r2199@gmail.com','Annisa rahmadani','Annisa',2,'Jakarta','1999-01-02','Pasar baru, belakang talago sari ',82177850140,72,7,1),('2020-01-22 21:00:17',1810732017,'dindaokza23@gmail.com','Dinda Okza Dera','Dinda',2,'Muaro Sijunjung','1999-10-20','Rawang Ketaping, kelurahan pasar ambacang, Kuranji',82285750500,73,7,1),('2020-01-20 20:30:38',1810732048,'haikal.yudhistira160@gmail.com','Haikal Yudhistira','Haikal ',1,'Pasar Usang','2000-01-16','-',81374285370,73,7,1),('2020-01-15 23:13:14',1810741003,'enjelinovitasari24@gmail.com','Enjeli Novita Sari','Enjel',2,'Sawahlunto Sijunjung','2000-02-13','Simpang malintang, pasar baru',82285251923,74,7,1),('2020-01-23 14:27:18',1810742005,'nadyawardana04@gmail.com','Nadya Wardana','Yaya',2,'Kayutanam','2000-02-04','Binuang kampung dalam Pauh',82293762224,74,7,1),('2020-01-23 17:10:15',1810742008,'viziasepnitasaputri@gmail.com','Vizia Sepnita Saputri','Vizia',2,'Kayu Kalek','2000-02-28','Kapalo koto, kec.pauh',82283394756,74,7,1),('2020-01-22 18:31:53',1810742009,'reni4972@gmail.com','Renita','Ren',2,'Padang','1999-12-20','Komp.martha indah, air pacah.',83182556817,74,7,0),('2020-01-28 13:04:09',1810742022,'sarahmuthia856@gmail.com','Sarah Muthia Fatmi','Sarah',2,'Koto Gadang','1999-07-22','Jln Kapalo Koto, No 11 Rt 01 RW 01 Kec Pauh Padang ',82284649307,74,7,1),('2020-01-19 14:12:09',1810751002,'mutiaeliza9c@gmail.com','Mutia Nova Eliza','Eiza',2,'Solok','2000-04-30','Pasar baru',89650969133,75,7,1),('2019-12-31 14:56:56',1810752007,'riandyderiza@gmail.com','Riandy Deriza','Rendy',1,'Bukit tinggi ','1999-09-30','Jln.lubuk gajah, Pisang',81275320700,75,7,1),('2019-12-31 14:48:16',1810752028,'adinda.nda24@gmail.com','Adinda Putri Salsabila','Dinda',2,'Alai, Pasaman','2000-02-07','Jl. Kapalo Koto no.81 (kos ayah 1)',81268970781,75,7,1),('2020-01-14 18:35:06',1810832009,'nabila.annisa.nurhadi@gmail.com','Nabila Annisa Nurhadi','Bibil',2,'Padang Panjang','2000-02-05','Komplek Taman Sakinah Blok D.18 Lubuk Buaya',81267389784,83,8,1),('2019-12-31 14:05:30',1810832013,'akbarasyari985@gmail.com','Akbar Asyari','Akbar',1,'Bukittinggi ','2019-05-26','Jln Moh Hatta no 4, Pauh, Padang',81262439797,83,8,1),('2020-01-20 17:27:06',1810832021,'senoseno411@gmail.com','Pringgolakseno Pangestu','Seno',1,'Muara Bungo','1998-12-12','Jl. Dj Wak Ketok, Pisang, Pauh',82268939378,83,8,1),('2020-01-06 14:05:51',1810832039,'arialdilucky@gmail.com','Arialdi Kaspari','Aldi',1,'Padang','2000-07-12','Komplek Unand Blok D, Gadut, Kel. Bandar Buat, Kec. Lubuk Kilangan.',895360733459,83,8,1),('2020-01-23 22:04:44',1810832045,'adekbusradua@gmail.com','Adek Albusra','Deka',1,'Padang','1999-01-02','J. Kapalo koto No. 46',82384041853,83,8,0),('2020-01-19 21:54:14',1810841019,'felliaferinandes@gmail.com','Fellia Rahmadhani Ferinandes','Fellia',2,'Tiku','2000-01-01','Kapalo koto',82178522792,84,8,0),('2020-01-16 23:21:47',1810842024,'viqdavinalucyiana24@gmail.com','Viqda vina lucyiana','Nana',2,'Dharmasraya','2000-01-24','Jl dr. Moh hatta',82384649526,84,8,0),('2020-01-28 02:18:24',1810842031,'mesarahfi3@gmail.com','Mesa suraya rahfi','Mesa',2,'Padang','1999-04-25','Perumahan taruko 3 blok f 13 kecamatan kuranji kelurahan gunung sarik',82386458182,84,8,0),('2020-01-19 13:50:50',1810843026,'fauzan200001@gmail.com','Fauzan Ash Shidiq','Fauzan',1,'Sawahlunto','2020-01-01','Kampuang duri',8126152401,84,8,1),('2019-12-31 21:00:12',1810851001,'salsabilaaulia0703@gmail.com','Salsabila Aulia','Bila',2,'Medan','2020-07-03','Jl. Sdn no.15 ',895603336897,85,8,1),('2020-01-21 17:34:40',1810851014,'aurasalsabilabentara@gmail.com','Aura Salsabila','Aura',2,'Pandrah Kandeh, Aceh','2000-11-03','Koto Tuo, Padang',82273971616,85,8,1),('2020-01-19 18:35:37',1810852025,'dindasuryafauzi7@gmail.com','Dinda SF','dinda',2,'Tongar','2000-02-29','Kapalo koto',88271660206,85,8,0),('2020-01-27 15:43:15',1810861005,'tiaraaaulia0@gmail.com','Tiara Aulia','Tiara',2,'Taram','2000-12-06','Kos 44 putri Jl. Dr. Moh. Hatta No.6, Limau Manis, Pauh, Kota Padang, Sumatera Barat',82285746665,86,8,1),('2020-01-13 21:28:33',1810861015,'iftitahrmdhni@gmail.com','Iftitah Ramadhani','Iif',2,'Lubuk Sikaping','1999-12-10','Kos Tursina, Irigasi',85830299629,86,8,0),('2020-01-02 23:01:25',1810862004,'n.besmah.pro@gmail.com','Naufaldo Besmah','Besmah',1,'Pasaman Barat','2000-08-13','Limau manih dekat sma 15 padang',82287814317,86,8,0),('2020-01-25 21:13:22',1810863013,'WDrafenza@gmail.com','Witari Drafenza','Tari',2,'Solok ','2000-06-19','Simpang Pasia No.05 A, RT 02/RW 02, Kapalo Koto, Pauh, Padang',85357065168,86,8,1),('2020-01-29 02:32:57',1810863017,'naufalzafran17@gmail.com','m naufal zafran','zafran',1,'padang panjang','2000-06-17','jl kampung duri',82258640705,86,8,1),('2020-01-29 02:40:41',1810863025,'adrustuan23@gmail.com','ADRUS TUANDA EDRY','ADRUS',1,'Bukittinggi','2000-10-23','Jln. Pasia no. 16 A Rt 2 Rw 2 Kel. Kapalo Koto Kec. Pauh sebelah masjid tadjul arifin',895636855937,86,8,1),('2020-01-18 04:14:57',1810913022,'ferdihabib11@gmail.com','FERDI HABIB MAULANA','HABIB',1,'BUKITTINGGI','2000-08-24','Kampung Duri, Kapalo Koto, Pauh',8995599852,91,9,0),('2020-01-26 22:49:46',1810952033,'mhdfrhn1302@gmail.com','M. Farhan','Farhan',1,'Bukittinggi','2000-02-13','Kapalo koto',81372960146,95,9,0),('2020-01-25 23:22:13',1810952041,'farhanfadil47@gmail.com','Farhan fadil irsan','Farhan',1,'Padang','2000-08-02','Jl. Ranah Binuang no 22',81268956469,95,9,1),('2020-01-25 23:09:56',1810953003,'fahranuladeri@gmail.com','Fahranul Aderi','Aan',1,'BUKITTINGGI','2000-10-05','Komplek griya insani ambacang no.74',85363443207,95,9,1),('2020-01-25 23:39:09',1810953036,'adhannazief@gmail.com','Mohammad Adhan Nazief','adhan',1,'Payakumbuh','2000-03-17','jl.pasir no 8',81315663779,95,9,1),('2020-01-09 22:33:08',1811121005,'aliffauzan486@gmail.com','Alif Fauzan','Alif',1,'Depok','2000-11-16','Kampung Dalam',8975206148,112,11,1),('2020-01-23 02:15:19',1811123014,'syauti15@gmail.com','Syauti alisa rahma','Syauti',2,'Padang','2000-04-10','Sawah laing Rt 01, Rw 05 Kel. Gunung Sarik ',895619214501,112,11,1),('2020-01-04 23:17:40',1811222005,'rispanelilensi@gmail.com','Lensi Rispaneli','Lensi',2,'Sungai Penuh','2000-12-16','Jl. Moh Hatta, Pauh, padang',82261176604,122,12,0),('2020-01-20 01:31:26',1811312029,'ameliananda879@gmail.com','Nanda Amelia','Amel',2,'Balingka','2000-07-28','Jl. Moh. Hatta no. 29 pasar baru, Pauh',85765999349,131,13,1),('2020-01-13 23:51:48',1811512022,'muh.rahmat0011@gmail.com','Muhammad Rahmat ','Rahmat ',1,'Padang','1998-04-04','Jl. Kolam indah 1 no 3a cendana mata air',89512701882,151,15,0),('2020-01-18 16:16:15',1811512026,'mwafa21@gmail.com','Muhammad Wafa Alhanif','Wafa',1,'Padang','2000-06-24','Tanjung Saba',895602732573,151,15,1),('2020-01-24 12:51:07',1900512019,'pio.gavio26@gmail.com','Gavio Fryhananda Putra','Gavio',1,'Pekanbaru','2001-07-26','JL.DR.M HATTA, Kel. Pasar Ambacang Kec. Kuranji, Padang',82243338331,510,5,0),('2020-01-24 12:47:41',1900512031,'dimasfajjar56@gmail.com','DIMAS FAJJAR ANGGIDJAKTI','DIMAS',1,'Perawang','2001-05-29','Komp kampung baru indah blok ee no 6',81267069626,510,5,0),('2019-12-31 14:42:23',1900512034,'sarahchikaibrina2001@gmail.com','sarah chika ibrina','chika',2,'payakumbuh','2001-02-15','palm house',87890001109,510,5,1),('2020-01-06 03:14:39',1900532003,'tiarabrilliani01@gmail.com','Tiara Brilliani','Tiara ',2,'Tanjung Alam, Kab.Agam','2001-08-07','Komp. Perum. Citra Almara, Korong Gadang, Kec. Kuranji',81533385602,530,5,1),('2020-01-29 20:21:04',1900532037,'lailaturr852@gmail.com','LAILATUR RAHMI ','Rahmi',2,'Padang panjang ','2001-03-12','Jln moh. Hatta no 30a',81363331014,530,5,1),('2020-01-24 23:12:32',1900532041,'jiaraisy@gmail.com','Jihan Rihhadatul Aisy','Jia',2,'Bukittinggi','2001-08-31','Pondokan khasih, jalan Koto Tuo,RT1, RW4, Kelurahan Kapalo Koto, Kecamatan Pauh',81211164414,530,5,0),('2020-01-22 15:56:46',1900532048,'farhanfahril25@gmail.com','Farhan Fahril','Farhan',1,'Bukittinggi','2000-03-25','Jalan Koto Tuo',895414647631,530,5,1),('2020-01-26 16:28:13',1900532050,'fajarandicapratama28@gmail.com','Fajar Andica Pratama','Fajar',1,'Geragahan','2000-01-28','Pasar ambacang, kuranji',81275202320,530,5,1),('2020-01-22 16:24:31',1900532052,'muhammadadamtabrani@gmail.com','Muhammad adam tabrani','Adam',1,'Sungai tarab','2001-01-31','Simpang pasia',82285876372,530,5,1),('2020-01-26 15:36:49',1900532058,'putrigalarangs@gmail.com','Putri Galarang Sari','Lala',2,'Batu sangkar ','2020-11-16','Komplek Villa Bunga Mas No. 03 Kecamatan Padang Utara',85363832180,530,5,1),('2020-01-13 19:09:39',1900542003,'afifamabil.valentino48@gmail.com','Afif Amabil','Abil',1,'Bukittinggi','2000-01-21','Limau manis',81261624455,540,5,1),('2020-01-19 02:02:21',1900542104,'matmasyhur151100@gmail.com','Muhammad masyhur','Mamat',1,'Bukittinggi','2020-11-15','Pasar baru',82210372878,540,5,1),('2019-12-31 20:12:01',1910111045,'ridwanefendisiregar13@gmail.com','Ridwan Efendi Siregar','Ridwan',1,'Medan','2001-09-13','Jl. Kapalo koto',83801864454,11,1,1),('2020-01-12 18:34:44',1910112091,'rayhanrahmadii@gmail.com','Rayhan Rahmadi','Rayhan',1,'Jambi','2001-01-02','Jalan Drs. Moh. Hatta no. 54',85240964575,11,1,0),('2020-01-18 23:51:11',1910112118,'radenimam25@gmail.com','raden Muhamad Maulana Imam','Imam',1,'Bogor','2001-04-09','Jl. Ripan 2 No. 23 Lubuk Buaya',82261088408,11,1,1),('2020-01-06 19:43:34',1910113014,'arimirza5@gmail.com','MIFTHAHUL KHAIRIL MIRZA ','Ari',1,'Kamang hilir ','2001-06-21','Kapalo koto, pasa baru',87872082855,11,1,1),('2020-01-27 16:50:50',1910113022,'gibraneurico@gmail.com','Eurico Gibran Suherman','Gibran',1,'padang','2001-02-23','jalan. bhakti abri, kpik, lubuk minturun',81395865869,11,1,1),('2020-01-16 13:10:30',1910113068,'lisayuliyanti76@gmail.com','Lisa Yuliyanti','Lisa',2,'Sariak Alahan Tigo','2000-05-10','Kos putri bunda, jl. Dr moh hatta no 5 kpl.koto pauh',82268874195,11,1,1),('2020-01-22 09:29:23',1910221002,'fauzandzakwan80@gmail.com','fauzan dzakwan','Fauzan',1,'Sungai penuh','2001-12-03','Asrama UNAND',82375550071,22,2,0),('2020-01-02 15:12:42',1910222025,'ulfaazizah621@gmail.com','Ulfa Azizah Febryzalita','Ulfa ',2,'Solok','2020-02-20','Jl. Moh Hatta No.2 (jln irigasi kos abu-abu belakang pondokan nena)',81270842940,22,2,1),('2020-01-02 11:32:44',1910222031,'na16112000@gmail.com','Nadia azzahra','Nadia',2,'Padang','2000-11-16','Jl. Khatib sulaiman',85264645779,22,2,1),('2019-12-31 16:43:26',1910223034,'fikryrizki65@gmail.com','Dzulfikri Rizki','Fikri',1,'Payakumbuh','2001-08-02','Jl. Moh.Hatta No. 30',82178997997,22,2,1),('2020-01-07 00:18:16',1910231005,'dwiputrikesi13@gmail.com','KESI DWI PUTRI','Kesi',2,'Payakumbuh','2001-01-13','Limau manih',82295619414,23,2,0),('2020-01-02 17:28:06',1910233021,'Hardisotyanzega09@gmail.com','Hardi sofyan zega','Sofyan',1,'Gunungsitoli','2001-06-09','Jln. Moh. Hatta No. 32',81376034467,23,2,0),('2020-01-01 11:34:00',1910251033,'rahmafadhila0@gmail.com','Rahma fadhila','Rahma',2,'Bukit tinggi ','2000-09-15','Pasar baru ',81267635541,25,2,0),('2020-01-05 13:30:12',1910251051,'bulan9132@gmail.com','Siti Nur Bulandari','BULAN',2,'Psr. Durian Manggopoh, Kec. Lubuk Basung,  Kab. Agam, Prov. Sumbar','2000-11-24','Asrama Unand (rpx) ',83193452400,25,2,0),('2020-01-11 19:33:26',1910321002,'rahmanitaarmon1@gmail.com','Rahmanita armon','Nita',2,'Padang','2001-08-08','Jl. Parak jambu gang v no.1 dadok tunggul hitam',82288720366,32,3,1),('2020-01-21 22:52:48',1910321036,'meylindatiffany@gmail.com','Meylinda Tiffany Raihan Putri','Myn',2,'Jayapura, Papua','2001-05-28','Jl. Dr. Muh. Hatta no 69',82198250626,32,3,0),('2020-01-29 19:18:01',1910411029,'ahmad.shauky29@gmail.com','Ahmad Shauky','Oky',1,'Pekanbaru','2001-03-24','Jl. Limau manis',83841814983,41,4,1),('2020-01-22 00:00:50',1910412031,'sintiacaniago12@gmail.com','Sintia Caniago','Sintia',2,'Jambi','2001-08-18','Asrama RPX UNAND',85213279954,41,4,1),('2020-01-21 10:26:48',1910422012,'asyifaulhusna20@gmail.com','Asyifa Ul Husna','Syifa',2,'BUKITTINGGI ','2001-01-20','Jl. Dr. Moh. Hatta No. 92 Pondokan Putri Dinda ',85282546808,42,4,1),('2019-12-31 21:25:55',1910422025,'muh.ryanmaulana@gmail.com','M. Ryan Maulana','Ryan',1,'Dumai','2002-02-18','jalan drs.moh. hatta dekat kantor lurah kapalo koto',81378605037,42,4,1),('2020-01-06 17:46:21',1910422038,'tetizul@gmail.com','Haniyatul Huda','Aya',2,'Dumai','2001-04-04','Asrama Unand, Jl. Universitas Andalas, Limau Manis, Kec. Pauh, Kota Padang',82289463522,42,4,1),('2020-01-29 23:24:45',1910423019,'wardanilaini@gmail.com','Wardatul Aini','Wardah',2,'Payakumbuh','2000-04-20','Kost Yuri Residen, Jalan Moh. Hatta, Limau Manis, Pauh, Padang',85213950530,42,4,0),('2020-01-15 01:01:28',1910441001,'Ahmadfaaa02@gmail.com','Ahmad Fadillah','Fadil',1,'Bekasi','2001-08-02','Jl.Dr. Moh Hatta Kost Raffly ',81247411838,44,4,1),('2019-12-31 15:43:55',1910512014,'indahfebriani166@gmail.com','Indah febriani','indah',2,'padang','2001-02-24','Jalan parak karakah no 20 ',83182304538,51,5,1),('2019-12-31 06:10:44',1910521046,'alifpriya07@gmail.com','Alif priya sulthon','Alif',1,'Muara bungo,jambi','2001-07-21','Anduring',82346339200,52,5,1),('2019-12-31 23:58:13',1910522015,'dnull02@gmail.com','Dinnul Kholis','Dinnul',1,'Batam','2000-06-02','Koto tuo',82285945300,52,5,0),('2020-01-17 12:30:49',1910522022,'tvrberry@gmail.com','Berry Alpinando','Berry',1,'Bengkulu','2001-05-13','Koto panjang',85669966765,52,5,1),('2020-01-06 19:59:10',1910522029,'hfah5355@gmail.com','Hanifah','Ipeh',2,'Bukittinggi','2001-05-02','Pasar baru',81363371742,52,5,1),('2020-01-01 11:11:45',1910522049,'akmalindra23@gmail.com','Akmal Indra','Akmal',1,'Padang','2000-09-07','Lubuk buaya ',81328660260,52,5,1),('2020-01-05 12:15:23',1910522061,'patma.tari@gmail.com','Patma asyathari','Tari',2,'Koto tuo','2001-02-12','Jati rawang',81533119295,52,5,1),('2019-12-31 16:45:11',1910532006,'fadilla71r@gmail.com','Fadhila Rahma Mulya','Kaem',2,'Solok','2001-01-08','Taruko 1',82391447809,53,5,0),('2020-01-25 21:11:54',1910532062,'aininurulizah@gmail.com','Aini Nurul Izah','Ami',2,'Bukittinggi','2020-01-06','Anduring',81276685445,53,5,1),('2020-01-25 12:51:10',1910533025,'fifindwirizkiemdi@gmail.com','Fifin Dwi Rizki Emdi','Kiki',2,'Padang','2000-07-24','Jl Perak No 2 Padang',81286897408,53,5,1),('2020-01-13 12:48:06',1910611032,'rizkaazzura29@gmail.com','Rizka azzuradiva afkha sirait','Rizka',2,'Medan','2001-04-29','Jl.moh hatta',85342141459,61,6,0),('2020-01-13 23:35:43',1910611034,'maestrocatt@gmail.com','Mohammad Dwivano Zeriko','Vano',1,'Bandung','2001-04-13','Komp. Belanti Permai II Block G/8',82170365786,61,6,0),('2020-01-22 15:15:45',1910611128,'rahmanfadly9@gmail.com','Fadly rahman','Fadly',1,'Saentis','2001-12-13','Asrama menpera unand',89636828642,61,6,0),('2020-01-26 19:40:29',1910613051,'khalidhafiz800@gmail.com','Khalid hafizh sulaiman','Khalid',1,'Padang','2000-07-30','Simpang gia tabiang',85365403799,61,6,0),('2020-01-02 14:47:20',1910613063,'ariansyahcaniago07@gmail.com','FAHMI ARIANSYAH CANIAGO ','Fahmi',1,'Desa Humene ','2001-04-12','Pasar baru, gang sendik Campus BRI ',82250287968,61,6,1),('2020-01-25 23:47:37',1910613070,'fadiljusrin46@gmail.com','Muhammad Fadil Firdaus  ','Fadil',1,'Cupak','2001-08-17','Pasar Ambacang,gang kandang padati',85335145992,61,6,1),('2020-01-14 16:14:15',1910711033,'mutiarasani387@gmail.com','Mutiara Sani','Tia',2,'Gaung','2001-06-10','Pasar baru unand',85272953848,71,7,0),('2020-01-18 09:06:46',1910721021,'lusiekaprativi@gmail.com','Lusi Eka Prativi',' Lusi ',2,'Aur malintang','2000-11-13','Asrama unand',81310672949,72,7,1),('2020-01-17 14:20:57',1910721035,'Muftiathul_huda@gmail.com','Muftiathul Huda','Bulan',2,'Kota madya Sawahlunto','2000-12-16','Kapalo koto',82389149365,72,7,0),('2020-01-01 18:59:28',1910722010,'viraviafad27@gmail.com','Vira Via Fadhilla','Vira',1,'Payakumbuh','2001-03-27','Asrama Orange Unand',81371861312,72,7,0),('2020-01-16 02:16:06',1910722011,'nuzulilmiawan15@gmail.com','Nuzul Ilmiawan','Nuzul',1,'Bireun','2001-10-19','Jl. Dr. Moh. Hatta No.87, Kapala Koto, kecamatan Kapalo koto, Kota Padang, Sumatera Barat',89562107783,72,7,0),('2020-01-01 21:06:13',1910722027,'ladyyrada@gmail.com','Rada Lady Shara','Rada',2,'campago','2000-10-18','asrama RPX UNAND',82286777762,72,7,1),('2020-01-25 00:07:41',1910723005,'salmanabila13.sn@gmail.com','Salma Nabilla Maharani ','Salma',2,'Bukittinggi','2001-03-13','Jl. Tunggang (Gang Pakih Nurdin) No 2B , Pasar Ambacang, kota Padang',8975101208,72,7,0),('2020-01-23 14:48:51',1910723021,'nilaputriadenda@gmail.com','Nila putri adenda','Putri',2,'Bandar Lampung','2001-05-12','Jln. Ampera komp cacat veteran No.16 Bandar Buat,lubuk kilangan',82385883266,72,7,1),('2019-12-31 21:31:05',1910732006,'farhan.rozadi@gmail.com','Farhan Rozadi ','Aam',1,'Padang','2001-06-02','Komp. Andalas Makmur Blok D no 8',81267351224,73,7,1),('2020-01-16 02:42:35',1910741015,'mhdkhairull0@gmail.com','Muhammad Khairul','Sky',1,'Lawang, 9 November 2000','2000-11-09','Jl. Koto Tuo No.3, Kapala Koto, Kec. Pauh, Kota Padang, Sumatera Barat 25176, Indonesia',81256018866,74,7,1),('2020-01-20 12:20:07',1910742007,'mesiagustiara11@gmail.com','Mesi Agus Tiara','Mesi',2,'Pasir Tiku, kecamatan Tanjung mutiara kabupaten Agam','2001-08-20','Simpang pasir jalan Muhammad hatta',83180968667,74,7,1),('2020-01-26 15:33:28',1910742012,'gaprideliankar@gmail.com','Geby Aprideliankar','Geby',2,'Padang','2000-04-27','Lambung bukit',82388040630,74,7,1),('2020-01-20 12:28:44',1910742013,'mayangsari.ms588@gmail.com','Mayangsari','Mayang',2,'Padang','2000-01-23','Lambung bukit',85263001054,74,7,1),('2019-12-31 16:33:50',1910751013,'adeliarizkiazizah@gmail.com','Adelia Rizki Azizah Lubis','Adel',2,'Padangsidimpuan','2001-07-17','jln. kapalo koto, belakang bengkel las dua putra',82267020963,75,7,1),('2019-12-31 14:53:46',1910752003,'azzelsalsha@gmail.com','Azalia Salshabila Putri','Ajel',2,'Padang','2002-09-19','Jl. Parak Gadang VI No 21M',8116601960,75,7,1),('2020-01-01 21:22:54',1910752005,'fachrulrozi0900@gmail.com','Fachrul Rozi','Rozi ',1,'Batusangkar','2020-01-02','Aurduri indah',82280871395,75,7,0),('2020-01-07 22:57:48',1910752016,'andjasputri12@gmail.com','Farensky Andjasputri','Faren / Farensky',2,'Jakarta','2001-07-12','Airin House, Jl. Dr. Moh, Hatta No. 78, Rt.03/Rw.02, Kapala Koto, Kec. Pauh, Kota Padang, Sumatera Barat 25163',81222018010,75,7,1),('2020-01-14 15:41:09',1910752024,'widyachee@gmail.com','Widya Anggraeni','Widya',2,'Tanjung Pinang','1999-12-13','Wisma FIB UNAND',82390822088,75,7,1),('2020-01-23 17:33:55',1910752025,'ynaniiii26@gmail.com','Yusuf Sidiq Chahyo Nugroho','Chan',1,'Padang','2000-10-26','Jln. Banjir kanal kp. Pinang no.15 kel. Alai parak kopi',85213005531,75,7,0),('2020-01-15 23:36:16',1910812008,'atasyanurulzukri01@gmail.com','Atasya Nurul Zukri ','Tasya ',2,'Pekanbaru ','2001-05-10','Asrama Universitas Andalas ',85375130530,81,8,1),('2020-01-05 03:31:06',1910813006,'winiagnia@gmail.com','Wini agnia','Wini',2,'Solok','2001-04-11','Kos shafa studio',82172912004,81,8,0),('2020-01-05 15:45:20',1910821002,'khairifauzatul@gmail.com','Fauzatul khairi','Uja',2,'Jambi','2001-07-05','Jalan irigasi no.29A',81279905655,82,8,1),('2020-01-11 11:34:55',1910821005,'fauziahnadia3@gmail.com','Recita Nadia Fauziah','Recita',2,'Meranti,kec.Renah Pamenang,kab.Merangin,Jambi','2001-09-01','Asrama Unand',82269383172,82,8,1),('2019-12-31 16:29:36',1910822011,'luthfiperdana.lp@gmail.com','Luthfi Perdana Respati','Prdana.',1,'Bekasi','2001-02-22','Asrama UNAND',87885328978,82,8,1),('2020-01-17 15:03:50',1910822015,'fadillahtsary@yahoo.co.id','Tsary Fadillah','Tsary',2,'Padang','2001-04-22','Komplek Mega Asri blok A no 11 Olo Nanggalo, Padang (blkg spbu gn pangilun)',81378137059,82,8,0),('2020-01-13 17:21:29',1910822025,'renaldireno68@gmail.com','Reno ','Reno Renaldi ',1,'Curup,Bengkulu ','1999-10-02','Indarung ',89621128694,82,8,1),('2020-01-14 16:42:02',1910831006,'midhaorgiveni35@gmail.com','Midha Orgiveni','Midha',2,'Tambang','2001-08-07','Asrama hijau unand',82284867496,83,8,1),('2020-01-19 14:48:19',1910831016,'icamardiana20@gmail.com','Icamardiana','Ica',2,'Lubuk basung','2001-08-20','Asrama unand',83181786611,83,8,1),('2020-01-25 14:31:05',1910831018,'Pp7597167@gmail.com','Putri kumala sari','Putri',2,'Padang','2001-09-30','Pasar baru',81267515432,83,8,0),('2020-01-06 00:14:32',1910831024,'rahmim802@gmail.com','Mustika Rahmi','Muih',2,'Payakumbuh','2001-02-14','Pasar baru,kampung dalam',82391012834,83,8,1),('2020-01-26 02:18:42',1910832022,'monagirl535@gmail.com','Ramona','Mona',2,'Padang Magek','2000-04-28','Asrama hijau unand',85363809228,83,8,1),('2020-01-29 02:07:01',1910832023,'fitramuhamadafandi@gmail.com','Muhamad Afandi Fitra','Fandi',1,'Tabek patah ','2000-12-28','Simpang pasia',82190359532,83,8,0),('2019-12-31 14:43:35',1910842021,'vandellarizka27@gmail.com','Vandella Rizka Alda','Vandel',2,'Painan, pesisir Selatan ','2001-11-27','Jl. Dr. Moh. Hatta no. 89 kec. Pauh',85364588576,84,8,0),('2020-01-23 18:52:53',1910843027,'nadiyalarosa@gmail.com','Nadiya Agivia Larosa','Nadiya',2,'Padang Panjang','2000-08-26','Kost Baiti Jannati Residence, Jl. Koto Panjang No.10 A, Limau Manis',82268838892,84,8,0),('2020-01-26 00:26:54',1910851008,'pautanakbar2002@gmail.com','Pautan Akbar','Pautan',1,'Muara Bungo, Jambi','2002-01-25','Jalan Koto Tuo',81382645083,85,8,1),('2020-01-22 20:17:10',1910851010,'makiloverz@gmail.com','Debi Satria','Debi',1,'Tambang Emas','2020-10-19','Kec. Kuranji',82288529229,85,8,1),('2020-01-13 21:48:43',1910851025,'reskianatri@gmail.com','Reski Ananda Putri','Reski/iki',2,'Jambi','2001-07-30','Kalumbuk kuranji ',82127307519,85,8,1),('2020-01-22 00:44:35',1910852016,'oksamagusman13@gmail.com','Oksama Gusman','Sam',1,'Jakarta','2001-10-13','Jl. Jawa Gadut, RT2/RW1 Limau Manis, Pauh, Padang',81365488859,85,8,1),('2020-01-20 18:38:05',1910852023,'prabuharli1818@gmail.com','Prabu Haryo Pamungkas','Prabu',1,'Batu Sangkar','2000-12-10','Limau manis',85215019629,85,8,1),('2020-01-14 00:55:45',1910852029,'dlvpanel@gmail.com','Fadil Imanuddin Delvis ','Fadil',1,'Bukittinggi','2001-07-15','Asrama UNAND',82198857013,85,8,1),('2020-01-01 16:51:55',1910852048,'fajarfikri040999@gmail.com','Fajar Fikri','Fajar',1,'Pariaman','1999-09-04','Pasar baru',82288746388,85,8,1),('2020-01-20 18:44:39',1910853011,'yefrialdi@gmail.com','Yefri aldi','Yefri',1,'Panyakalan','2001-04-10','Durian tarung',81265115103,85,8,1),('2020-01-14 20:39:53',1910861004,'muhammadaldian2106@gmail.com','MUHAMMAD ALDIAN SAPUTRA.','Aldian.',1,'Kel Pauh.kab sarolangun.','2001-06-23','ASRAMA MENPERA(UNAND)',82213014583,86,8,1),('2020-01-19 00:46:53',1910861016,'sandrasyarliyenita@gmail.com','Sandra syarli yenita','Sandra',2,'Padang','2002-05-08','Asrama unand',81374619100,86,8,1),('2020-01-14 21:38:11',1910861028,'arizalzedtry@gmail.com','Arizal Zedtry','Zedtry',1,'Pasar Kambang','2001-04-14','Limau manis',82288331627,86,8,0),('2020-01-23 14:52:14',1910862008,'ridhooalifra@gmail.com','Ridho Alifra Donsky','Ridho',1,'Semurup','2001-11-04','Asrama UNAND',82284093154,86,8,1),('2020-01-19 02:51:12',1910862015,'hfsrfn10@gmail.com','Hafisz Irfan','Hafis',1,'Tanjung Jati','2000-06-08','Jl. Pisang no.184',82284967663,86,8,0),('2020-01-21 16:07:10',1910862032,'wibowoputri34@gmail.com','PUTRI ORCHID WIRA WIBOWO','ORCHID',2,'SOLO','2000-09-20','Jl. MOH HATTA 29, LIMAU MANIS, PAUH, PADANG',82226892457,86,8,1),('2020-01-14 22:06:21',1910863014,'rendyferlita@gmail.com','Rendi Ferlita','Ren',1,'Padang','2001-02-12','Jl.bandes koto panjang kos wd',82169618926,86,8,1),('2019-12-31 16:46:45',1910863017,'m.irsyadil.aulia80@gmail.com','M. Irsyadil Aulia','Ade',1,'Bukittinggi','2019-11-06','Jl.moh.hatta.no 12 . Pasar Baru',82386638567,86,8,1),('2020-01-22 16:49:48',1910863030,'doqiiahani@gmail.com','Sania Aqiila Adhani','Hani',2,'Jakarta','2001-03-05','Perumahan palimo indah blok Q.1',85892455840,86,8,1),('2020-01-06 17:14:18',1910921004,'syahril241201@gmail.com','Alfi Syahril','Alfi',1,'Blangpidie','2001-12-24','Jl, dr moh hatta no 110, kepalo koto, pauh, padang ',82292073065,92,9,1),('2020-01-17 10:40:09',1910943018,'alifiamardhia@gmail.com','Alifia mardhia andini','Nanda',2,'Bengkulu','2020-05-02','Komplek bumi lareh permai blok a/14 sungai lareh',82268470168,94,9,0),('2020-01-24 20:30:50',1910943024,'leonmaghribi02@gmail.com','Leonie Maghribi Sekar Oyono','Leonie',2,'Padang','2001-06-13','Jl. Pinang bungkuk ujung No.2 lubuk buaya',81266586122,94,9,0),('2020-01-19 05:54:36',1910951032,'abdi.mardhatillah01@gmail.com','Abdi Mardhatillah','Abdi',1,'Pariaman','2001-03-26','Pauh',81267784468,95,9,0),('2020-01-19 05:52:29',1910953034,'aldohap77@gmail.com','Aldo saputra','Aldo hap',1,'Swl sijunjung','2000-03-11','Sebelah sd 19 batu busuak',82286019172,95,9,0),('2020-01-12 20:54:17',1911112014,'junmezz16@gmail.com','Annisa Lestari Simanjuntak','Icha',2,'Solok','2000-10-22','Asrama Unand',82391101220,111,11,0),('2020-01-22 12:28:01',1911112023,'mirfann1206@gmail.com','M.Irfan','Irfan',1,'Bukittinggi','2000-06-12','Kapalo koto',82158494633,111,11,1),('2020-01-21 09:11:07',1911121037,'afkarmubarroq09@gmail.com','Aafkar Irbah Mubarroq','Afkar',1,'Bengkulu','2001-07-30','Jl.kampung duri',82285621229,112,11,1),('2020-01-03 02:08:59',1911133006,'rahmatunnisaica26@gmail.com','Rahmatun Nisa','Icha',2,'Lurah','2001-03-26','Kampung duri',82284612239,113,11,1),('2020-01-23 15:22:24',1911211016,'danitirtajaya624@gmail.com','Dani Tirtajaya Pramana','Dani',1,'Adi Jaya','2001-03-09','Seberang Padang Selatan',85896555932,121,12,0),('2020-01-26 00:14:16',1911212003,'arnimelati09@gmail.com','Arni Melati','Ani',2,'Talawi','2001-04-09','Asrama Hijau Unand',82171993045,121,12,0),('2020-01-25 23:18:53',1911212004,'ayupurnama157@gmail.com','Ayu Purnama','Ayu',2,'Lunto Barat','2000-07-15','Asrama Hijau Unand',85364630025,121,12,0),('2020-01-08 16:36:35',1911213024,'mutiarasakhinah0302@gmail.com','Mutiara sakhinah ','Mutsak ',2,'Jakarta ','2001-02-03','Jln kapalo koto No 1 ',85882067163,121,12,1),('2020-01-03 23:15:12',1911222002,'mutia_annisafadila@yahoo.co.id','Mutia Annisa Fadila','Ale',2,'Bukittinggi','2001-08-10','Jl. Moh. Hatta no. 69',81533330751,122,12,1),('2020-01-05 01:40:42',1911223018,'chaochahaha@gmail.com','DIFIA ATHARI IRSHADILLA','OCHA',2,'Batusangkar','2000-11-23','Di dekat sma 9',82371780627,122,12,0),('2020-01-01 11:09:20',1911312048,'Rizkasyesharini@gmail.com','Rizka Aulia Syesharini ','Ika ',2,'Padang Panjang ','2001-02-19','Kapalo Koto,  Pauh, Padang ',82386677655,131,13,0),('2019-12-31 20:04:09',1911312057,'rifdatunnisa@gmail.com','Aisyah Rifdatunnisa','Rifda',2,'Yogyakarta, 30 Juni 2001','2001-06-30','Kos Baiti Jannati, Jl. Koto Panjang No. 10 Limau Manis, Pauh, Padang',81367773608,131,13,0),('2020-01-13 21:54:08',1911512024,'harqilanjay@gmail.com','Harqil Amiga','Aqil',1,'Kuala Tungkal','2001-03-09','Jl. Alai Pauh',82269572272,151,15,0),('2020-01-01 16:28:52',1911522003,'radhianwahyu3012@gmail.com','Radhian Wahyu Elhaq','Radhian',1,'Padang Panjang','2000-12-30','Jl. Gn. Sago No. 29, Gn. Pangilun, Kec. Padang Utara, Kota Padang',81266345151,152,15,1),('2020-01-28 16:56:25',1911522020,'zakiahmaulana15@gmail.com','Zakiah Maulana','kia',2,'BUKITTINGGI ','2001-06-02','Limau Manis',85264775380,152,15,1),('2020-01-13 22:52:37',1911523005,'williamwahyu30@gmail.com','William Wahyu','Will/William',1,'Padang','2001-09-30','Koto panjang, limau manis',82386464069,152,15,1);
/*!40000 ALTER TABLE `pendaftar` ENABLE KEYS */;

--
-- Table structure for table `pendaftars`
--

DROP TABLE IF EXISTS `pendaftars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pendaftars` (
  `tgl_daftar` timestamp NOT NULL,
  `nim` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `panggilan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_g` int NOT NULL,
  `tempat_lahir` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_lahir` date NOT NULL,
  `alamat_pdg` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` bigint NOT NULL,
  `id_j` int NOT NULL,
  `id_f` int NOT NULL,
  `daftar_ulang` tinyint NOT NULL,
  PRIMARY KEY (`nim`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pendaftars`
--

/*!40000 ALTER TABLE `pendaftars` DISABLE KEYS */;
/*!40000 ALTER TABLE `pendaftars` ENABLE KEYS */;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;

--
-- Table structure for table `peserta_t1`
--

DROP TABLE IF EXISTS `peserta_t1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `peserta_t1` (
  `nim` int NOT NULL,
  `lulus` tinyint(1) NOT NULL,
  PRIMARY KEY (`nim`),
  CONSTRAINT `peserta_t1_ibfk_1` FOREIGN KEY (`nim`) REFERENCES `pendaftar` (`nim`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peserta_t1`
--

/*!40000 ALTER TABLE `peserta_t1` DISABLE KEYS */;
INSERT INTO `peserta_t1` VALUES (1810442048,1),(1810522063,1),(1810711016,0),(1810953036,1),(1900532050,0),(1910321002,1),(1910522049,1),(1910752003,1),(1910752024,1),(1911522003,1);
/*!40000 ALTER TABLE `peserta_t1` ENABLE KEYS */;

--
-- Table structure for table `peserta_t2`
--

DROP TABLE IF EXISTS `peserta_t2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `peserta_t2` (
  `nim` int NOT NULL,
  `lulus` tinyint(1) NOT NULL,
  PRIMARY KEY (`nim`),
  CONSTRAINT `peserta_t2_ibfk_1` FOREIGN KEY (`nim`) REFERENCES `peserta_t1` (`nim`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peserta_t2`
--

/*!40000 ALTER TABLE `peserta_t2` DISABLE KEYS */;
INSERT INTO `peserta_t2` VALUES (1810442048,1),(1810522063,1),(1810953036,1),(1910321002,1),(1910522049,1),(1910752003,1),(1910752024,0),(1911522003,1);
/*!40000 ALTER TABLE `peserta_t2` ENABLE KEYS */;

--
-- Table structure for table `peserta_t3`
--

DROP TABLE IF EXISTS `peserta_t3`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `peserta_t3` (
  `nim` int NOT NULL,
  `lulus` tinyint(1) NOT NULL,
  PRIMARY KEY (`nim`),
  CONSTRAINT `peserta_t3_ibfk_1` FOREIGN KEY (`nim`) REFERENCES `peserta_t2` (`nim`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peserta_t3`
--

/*!40000 ALTER TABLE `peserta_t3` DISABLE KEYS */;
INSERT INTO `peserta_t3` VALUES (1810442048,0),(1810522063,0),(1810953036,0),(1910321002,0),(1910522049,0),(1910752003,0),(1911522003,0);
/*!40000 ALTER TABLE `peserta_t3` ENABLE KEYS */;

--
-- Table structure for table `sub_kriteria_t1`
--

DROP TABLE IF EXISTS `sub_kriteria_t1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sub_kriteria_t1` (
  `id_sk1` int NOT NULL,
  `id_k1` int NOT NULL,
  `sub_kriteria` varchar(25) NOT NULL,
  `bobot` float NOT NULL,
  `sk_sc` varchar(25) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `kode` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id_sk1`),
  KEY `id_k1` (`id_k1`),
  CONSTRAINT `sub_kriteria_t1_ibfk_1` FOREIGN KEY (`id_k1`) REFERENCES `kriteria_t1` (`id_k1`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sub_kriteria_t1`
--

/*!40000 ALTER TABLE `sub_kriteria_t1` DISABLE KEYS */;
INSERT INTO `sub_kriteria_t1` VALUES (11,1,'Tanggung Jawab',0.4,'tanggung_jawab',NULL,'2022-08-08 13:09:16','TJ'),(12,1,'Keaktifan',0.3,'keaktifan',NULL,'2022-08-08 13:09:34','AT'),(13,1,'Teamwork',0.3,'teamwork',NULL,'2022-08-08 13:09:47','TW'),(21,2,'Wawancara',0.333,'wawancara',NULL,'2022-08-08 13:09:54','W'),(31,3,'Tes Bakat',0.333,'tes_bakat',NULL,'2022-08-08 13:10:02','TB');
/*!40000 ALTER TABLE `sub_kriteria_t1` ENABLE KEYS */;

--
-- Table structure for table `sub_kriteria_t2`
--

DROP TABLE IF EXISTS `sub_kriteria_t2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sub_kriteria_t2` (
  `id_sk2` int NOT NULL,
  `id_k2` int NOT NULL,
  `sub_kriteria` varchar(25) NOT NULL,
  `bobot` float NOT NULL,
  `sk_sc` varchar(25) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `kode` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id_sk2`),
  KEY `id_k2` (`id_k2`),
  CONSTRAINT `sub_kriteria_t2_ibfk_1` FOREIGN KEY (`id_k2`) REFERENCES `kriteria_t2` (`id_k2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sub_kriteria_t2`
--

/*!40000 ALTER TABLE `sub_kriteria_t2` DISABLE KEYS */;
INSERT INTO `sub_kriteria_t2` VALUES (11,1,'Keaktifan',0.333,'keaktifan','2022-06-28 23:12:33','2022-08-21 08:04:13','KA'),(12,1,'Kehadiran',0.333,'kehadiran','2022-06-28 23:28:35','2022-08-21 08:04:22','KH'),(13,1,'Studi Kasus',0.333,'studi_kasus','2022-07-17 03:00:50','2022-08-21 08:04:31','SK'),(21,2,'Kehadiran',0.25,'kehadiran','2022-06-28 23:39:19','2022-08-21 08:04:39','KH'),(22,2,'Kreativitas',0.25,'kreativitas','2022-06-28 23:40:08','2022-08-21 08:04:46','KR'),(23,2,'Keaktifan',0.25,'keaktifan','2022-07-17 03:00:07','2022-08-21 08:04:52','KA'),(24,2,'Presentasi',0.25,'presentasi','2022-07-17 03:00:29','2022-08-21 08:05:01','PR'),(31,3,'Kehadiran',0.5,'kehadiran','2022-06-28 23:39:39','2022-08-21 08:05:09','KH'),(32,3,'Keaktifan',0.1,'keaktifan','2022-07-02 04:12:34','2022-08-21 08:05:16','KA'),(33,3,'Kreatifitas',0.1,'kreatifitas','2022-07-17 02:58:13','2022-08-21 08:05:23','KR'),(34,3,'Kerjasama Tim',0.1,'kerjasama_tim','2022-07-17 02:58:26','2022-08-21 08:05:30','KT'),(35,3,'Teknik Penggunaan Alat',0.1,'teknik_penggunaan_alat','2022-07-17 02:58:39','2022-08-21 08:05:37','TPA'),(36,3,'Hasil Shooting',0.1,'hasil_shooting','2022-07-17 02:58:52','2022-08-21 08:05:44','HS');
/*!40000 ALTER TABLE `sub_kriteria_t2` ENABLE KEYS */;

--
-- Table structure for table `sub_kriteria_t3`
--

DROP TABLE IF EXISTS `sub_kriteria_t3`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sub_kriteria_t3` (
  `id_sk3` int NOT NULL,
  `id_k3` int NOT NULL,
  `sub_kriteria` varchar(100) DEFAULT NULL,
  `bobot` float NOT NULL,
  `sk_sc` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `kode` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id_sk3`),
  KEY `id_k3` (`id_k3`),
  CONSTRAINT `sub_kriteria_t3_ibfk_1` FOREIGN KEY (`id_k3`) REFERENCES `kriteria_t3` (`id_k3`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sub_kriteria_t3`
--

/*!40000 ALTER TABLE `sub_kriteria_t3` DISABLE KEYS */;
INSERT INTO `sub_kriteria_t3` VALUES (11,1,'Konten',0.25,'konten','2022-07-17 01:54:35','2022-08-25 11:16:32','KO'),(12,1,'Kreatifitas',0.25,'kreatifitas','2022-08-25 11:20:40','2022-08-25 11:20:40','KR'),(13,1,'Penyampaian',0.25,'penyampaian','2022-08-25 11:20:56','2022-08-25 11:20:56','PY'),(14,1,'Sistematika Penulisan',0.25,'sistematika_penulisan','2022-08-25 11:21:14','2022-08-25 11:21:14','SP'),(21,2,'Kesinambungan Rancangan dengan Karya',0.4,'kesinambungan_rancangan_dengan_karya','2022-08-25 09:46:03','2022-08-25 11:25:00','KRK'),(22,2,'Kreatifitas',0.3,'kreatifitas','2022-08-25 11:24:17','2022-08-25 11:24:47','KR'),(23,2,'Pengetahuan Fotografi',0.3,'pengetahuan_fotografi','2022-08-25 11:25:18','2022-08-25 11:25:18','PF'),(31,3,'Kesinambungan Ide dengan Karya',0.3,'kesinambungan_ide_dengan_karya','2022-08-25 09:46:13','2022-08-25 11:25:53','KIK'),(32,3,'Kreatifitas',0.25,'kreatifitas','2022-08-25 11:25:36','2022-08-25 11:25:36','KR'),(33,3,'Pra-Produksi',0.15,'pra-_produksi','2022-08-25 11:26:13','2022-08-25 11:26:13','PRP'),(34,3,'Produksi',0.15,'produksi','2022-08-25 11:26:28','2022-08-25 11:26:28','PRO'),(35,3,'Pasca-Produksi',0.15,'pasca-_produksi','2022-08-25 11:26:41','2022-08-25 11:26:41','PSP');
/*!40000 ALTER TABLE `sub_kriteria_t3` ENABLE KEYS */;

--
-- Table structure for table `telescope_entries`
--

DROP TABLE IF EXISTS `telescope_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telescope_entries` (
  `sequence` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `family_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `should_display_on_index` tinyint(1) NOT NULL DEFAULT '1',
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sequence`),
  UNIQUE KEY `telescope_entries_uuid_unique` (`uuid`),
  KEY `telescope_entries_batch_id_index` (`batch_id`),
  KEY `telescope_entries_family_hash_index` (`family_hash`),
  KEY `telescope_entries_created_at_index` (`created_at`),
  KEY `telescope_entries_type_should_display_on_index_index` (`type`,`should_display_on_index`)
) ENGINE=InnoDB AUTO_INCREMENT=756156 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `telescope_entries`
--

/*!40000 ALTER TABLE `telescope_entries` DISABLE KEYS */;
/*!40000 ALTER TABLE `telescope_entries` ENABLE KEYS */;

--
-- Table structure for table `telescope_entries_tags`
--

DROP TABLE IF EXISTS `telescope_entries_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telescope_entries_tags` (
  `entry_uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `telescope_entries_tags_entry_uuid_tag_index` (`entry_uuid`,`tag`),
  KEY `telescope_entries_tags_tag_index` (`tag`),
  CONSTRAINT `telescope_entries_tags_entry_uuid_foreign` FOREIGN KEY (`entry_uuid`) REFERENCES `telescope_entries` (`uuid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `telescope_entries_tags`
--

/*!40000 ALTER TABLE `telescope_entries_tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `telescope_entries_tags` ENABLE KEYS */;

--
-- Table structure for table `telescope_monitoring`
--

DROP TABLE IF EXISTS `telescope_monitoring`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telescope_monitoring` (
  `tag` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `telescope_monitoring`
--

/*!40000 ALTER TABLE `telescope_monitoring` DISABLE KEYS */;
/*!40000 ALTER TABLE `telescope_monitoring` ENABLE KEYS */;

--
-- Table structure for table `user_role`
--

DROP TABLE IF EXISTS `user_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_role` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_role`
--

/*!40000 ALTER TABLE `user_role` DISABLE KEYS */;
INSERT INTO `user_role` VALUES (1,'admin',NULL,NULL),(2,'panitia',NULL,NULL),(3,'penilai',NULL,NULL);
/*!40000 ALTER TABLE `user_role` ENABLE KEYS */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles_id` bigint unsigned NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_FK` (`roles_id`),
  CONSTRAINT `users_FK` FOREIGN KEY (`roles_id`) REFERENCES `user_role` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','admin@mail.com',NULL,'$2y$10$UWeTSUd7UgK05zUCNTqs6u8HAXF9XR7YD6r/8KI4uTGkXcFAj1Fd.',1,NULL,'2022-10-11 01:14:09','2022-10-12 10:48:38'),(3,'mimin','mimin@mail.com',NULL,'$2y$10$Y9ZajtrTChiIZLGt5G5Z8OnaPm2PtL46yL81u7PZvTi1MrF.pm9Gq',1,NULL,'2022-10-11 05:52:33','2022-10-11 05:52:33'),(4,'panitia','panitia@mail.com',NULL,'$2y$10$SxpopIibITdFaeqDXiUoRuom5w0J8JhU9/ERYQWS8Kfbm0COc1BBa',2,NULL,'2022-10-11 05:53:15','2022-10-11 05:53:15'),(5,'penilai','penilai@mail.com',NULL,'$2y$10$MUOsNMBbFJuCgHf/SXbzYeTKBNULC1F5srt82qZJ7fBAlINSWP5Yq',3,NULL,'2022-10-11 18:02:25','2022-10-11 18:02:25'),(7,'sara','sara@mail.com',NULL,'$2y$10$mN3ttmz5nFdrVq.daskfVON7GSF6IlMP25UjifXgl7kS9XTeXimym',3,NULL,'2022-10-12 11:04:45','2022-10-12 11:04:45');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-10-17 12:04:54
