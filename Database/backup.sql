-- MySQL dump 10.13  Distrib 8.0.39, for Linux (x86_64)
--
-- Host: localhost    Database: carwash_user
-- ------------------------------------------------------
-- Server version	8.0.39-0ubuntu0.20.04.1

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
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `expenses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `expense_name` varchar(50) DEFAULT NULL,
  `expense_cost` int DEFAULT NULL,
  `expense_description` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expenses`
--

LOCK TABLES `expenses` WRITE;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
INSERT INTO `expenses` VALUES (1,'Soap',1500,'car washing soap 3l','2024-08-07 17:30:11'),(2,'Soap',1500,'car washing soap 3l','2024-08-07 17:36:24');
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `notification` varchar(100) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `notification` (`notification`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (8,'Now you can see your car wash status online.','2021-06-06 19:19:30'),(2,'We are open till 07.00PM from next week','2021-06-02 15:10:40');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `queue`
--

DROP TABLE IF EXISTS `queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `queue` (
  `id` int NOT NULL AUTO_INCREMENT,
  `owner_email` varchar(200) NOT NULL,
  `owner_name` varchar(200) NOT NULL,
  `owner_phone` varchar(20) NOT NULL,
  `owner_address` varchar(250) NOT NULL,
  `staff` int NOT NULL,
  `vehicle_number` varchar(20) NOT NULL,
  `service_type` int NOT NULL,
  `in_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `out_time` timestamp NULL DEFAULT NULL,
  `status_type` int NOT NULL DEFAULT '1',
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vehicle_number_2` (`vehicle_number`,`status_type`),
  KEY `vehicle_number` (`vehicle_number`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `queue`
--

LOCK TABLES `queue` WRITE;
/*!40000 ALTER TABLE `queue` DISABLE KEYS */;
INSERT INTO `queue` VALUES (1,'asiriofficial@gmail.com','Asiri H','94786141343','23 Avenue, Kandy Srilanka',1,'CAK6298322',1,'2021-05-19 10:34:14','2021-06-15 03:00:27',4,'2021-06-06 19:08:10'),(2,'john@gmail.com','John C.','34786141343','23 Avenue, Kandy Armenia',1,'CAK25398344',2,'2021-06-02 10:34:04',NULL,1,'2021-06-06 19:04:13'),(3,'asiriofficial@gmail.com','Asiri H','94786141343','23 Avenue, Kandy Srilanka',2,'CAK6253983',2,'2021-06-02 06:35:37',NULL,2,'2021-06-03 15:07:31'),(4,'john@gmail.com','John C.','34786141343','23 Avenue, Kandy Armenia',4,'DE45436',4,'2021-06-02 06:35:41',NULL,4,'2021-06-03 15:07:31'),(5,'asiriofficial@gmail.com','Asiri H','94786141343','23 Avenue, Kandy Srilanka',3,'CAK62539833',3,'2021-06-02 18:25:31',NULL,0,'2021-06-03 15:07:31'),(6,'john@gmail.com','John C.','34786141343','23 Avenue, Kandy Armenia',1,'CAK253983',3,'2021-06-02 06:21:07',NULL,4,'2021-06-06 18:03:26'),(7,'asiriofficial@gmail.com','Asiri H','94786141343','23 Avenue, Kandy Srilanka',2,'CAK62539835',1,'2021-02-02 06:35:44',NULL,3,'2021-06-06 19:01:47'),(8,'john@gmail.com','John C.','34786141343','23 Avenue, Kandy Armenia',2,'CAK2539835',3,'2021-06-02 06:21:04',NULL,3,'2021-06-07 02:16:41'),(9,'john@gmail.com','John C.','34786141343','23 Avenue, Kandy Armenia',1,'CAK2539839',3,'2021-06-02 06:21:04',NULL,1,'2021-06-04 23:22:51'),(12,'foolmashi@gmail.com','John K','+94786141343','Australia',1,'AS3435',1,'2021-06-05 09:14:31',NULL,2,'2021-06-07 16:22:44'),(14,'foolmashi@gmail.com','Asiri H','+94786141343','No:1/48, Colombo',2,'AS34354',2,'2021-06-05 09:26:18',NULL,2,'2021-06-07 16:23:43'),(15,'foolmashi@gmail.com','Asiri H','+94786141343','No:1/48, Haputhale-Egodagama\r\nThalathuoya',1,'AS343533',2,'2021-06-07 22:48:43',NULL,1,'2021-06-07 12:48:43'),(16,'foolmashi@gmail.com','Asiri H','+94786141343','No:1/48, Haputhale-Egodagama\r\nThalathuoya',1,'AS3435333',2,'2021-06-07 22:55:12',NULL,1,'2021-06-07 12:55:12'),(17,'muokapwambua@gmail.com','TEST','2546799','Kenya House, 20100, 16912 Monrovia Street, Nairobi, Kenya',5,'45236789',5,'2024-08-06 14:24:26',NULL,3,'2024-08-06 16:01:33'),(18,'muokapwambua@gmail.com','New','0795826356','Kenya House, 20100, 16912 Monrovia Street, Nairobi, Kenya',5,'46763',5,'2024-08-06 19:44:17',NULL,1,'2024-08-06 19:44:17'),(19,'muokapwambua@gmail.com','New','0795826354','Kenya House, 20100, 16912 Monrovia Street, Nairobi, Kenya',5,'5678',5,'2024-08-07 14:44:16',NULL,3,'2024-08-07 15:36:00'),(20,'muokapwambua@gmail.com','test','0795826354','Kenya House, 20100, 16912 Monrovia Street, Nairobi, Kenya',5,'4567',6,'2024-08-07 14:46:00',NULL,3,'2024-08-07 15:36:27'),(21,'muokapwambua@gmail.com','james','45342323','test address',6,'231',6,'2024-08-07 14:48:54',NULL,3,'2024-08-07 15:36:14');
/*!40000 ALTER TABLE `queue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_type`
--

DROP TABLE IF EXISTS `service_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(200) NOT NULL,
  `service_cost` int DEFAULT '0',
  `service_commission` float DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_type`
--

LOCK TABLES `service_type` WRITE;
/*!40000 ALTER TABLE `service_type` DISABLE KEYS */;
INSERT INTO `service_type` VALUES (1,'Car wash only',0,0),(2,'Car wash and waxing',0,0),(3,'Car wash only',0,0),(4,'Car wash and waxing',0,0),(5,'Buffing',3000,20),(6,'Car Wash',500,20);
/*!40000 ALTER TABLE `service_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `staff` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `employee_contact` varchar(15) DEFAULT NULL,
  `employee_status` varchar(7) DEFAULT 'idle',
  `employee_email` tinytext,
  `employee_address` tinytext,
  `employee_created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff`
--

LOCK TABLES `staff` WRITE;
/*!40000 ALTER TABLE `staff` DISABLE KEYS */;
INSERT INTO `staff` VALUES (1,'Car',NULL,'idle',NULL,NULL,'2024-08-06 11:32:55'),(2,'Van',NULL,'idle',NULL,NULL,'2024-08-06 11:32:55'),(3,'Taxi',NULL,'idle',NULL,NULL,'2024-08-06 11:32:55'),(4,'Bus',NULL,'idle',NULL,NULL,'2024-08-06 11:32:55'),(5,'John Doe','0795826354','idle','johndoe@carwash.co.ke','Kenya House, 20100, 16912 Monrovia Street, Nairobi, Kenya','2024-08-06 12:29:37'),(6,'Kim','0795826354','idle','kim@carwash.co.ke','Kenya House, 20100, 16912 Monrovia Street, Nairobi, Kenya','2024-08-07 17:47:51');
/*!40000 ALTER TABLE `staff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `status_type`
--

DROP TABLE IF EXISTS `status_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `status_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status_type`
--

LOCK TABLES `status_type` WRITE;
/*!40000 ALTER TABLE `status_type` DISABLE KEYS */;
INSERT INTO `status_type` VALUES (1,'Initialized'),(2,'In Progress'),(3,'Completed'),(4,'Dispatched'),(0,'On Hold');
/*!40000 ALTER TABLE `status_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `status_updates`
--

DROP TABLE IF EXISTS `status_updates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `status_updates` (
  `queue_id` int NOT NULL,
  `status_id` int NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` varchar(500) NOT NULL,
  PRIMARY KEY (`queue_id`,`status_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status_updates`
--

LOCK TABLES `status_updates` WRITE;
/*!40000 ALTER TABLE `status_updates` DISABLE KEYS */;
INSERT INTO `status_updates` VALUES (1,4,'2021-06-03 14:50:42','Your car wash is completed'),(1,2,'2021-06-03 14:50:42','Your car wash is in progress. Please refresh back in few minutes for updates.'),(2,1,'2021-06-03 14:50:42','Your car wash is initialized. Please refresh back in few minutes for updates.'),(2,2,'2021-06-03 14:50:42','Your car wash is in progress. Please refresh back in few minutes for updates.'),(3,1,'2021-06-03 14:50:42','Your car wash is initialized. Please refresh back in few minutes for updates.'),(3,2,'2021-06-03 14:50:42','Your car wash is in progress. Please refresh back in few minutes for updates.');
/*!40000 ALTER TABLE `status_updates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','admin@example.com','password');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-08-07 20:38:48
