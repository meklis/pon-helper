-- MySQL dump 10.13  Distrib 5.7.33, for Linux (x86_64)
--
-- Host: localhost    Database: switcher_core
-- ------------------------------------------------------
-- Server version	5.7.33-0ubuntu0.18.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `device_accesses`
--

DROP TABLE IF EXISTS `device_accesses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `device_accesses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `community` varchar(50) NOT NULL,
  `login` varchar(50) DEFAULT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `device_accesses_name_uindex` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_accesses`
--

LOCK TABLES `device_accesses` WRITE;
/*!40000 ALTER TABLE `device_accesses` DISABLE KEYS */;
INSERT INTO `device_accesses` VALUES (1,'Access L2','public','billing','billing');
/*!40000 ALTER TABLE `device_accesses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `device_models`
--

DROP TABLE IF EXISTS `device_models`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `device_models` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `params` json DEFAULT NULL,
  `vendor` varchar(50) DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  `type` enum('SWITCH','OLT','ONU','ROUTER') NOT NULL DEFAULT 'SWITCH',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_models`
--

LOCK TABLES `device_models` WRITE;
/*!40000 ALTER TABLE `device_models` DISABLE KEYS */;
INSERT INTO `device_models` VALUES (1,'ZTE C320','{\"telnet_port\": {\"name\": \"Telnet port\", \"value\": 23}, \"snmp_repeats\": {\"name\": \"Snmp repeats\", \"value\": 2}, \"snmp_timeout\": {\"name\": \"Snmp timeout sec\", \"value\": 2}, \"telnet_timeout\": {\"name\": \"Telnet timeout sec\", \"value\": 2}, \"mikrotik_api_port\": {\"name\": \"API port(only for RouterOS)\", \"value\": 8976}}','ZTE','C320','SWITCH');
/*!40000 ALTER TABLE `device_models` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `devices`
--

DROP TABLE IF EXISTS `devices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(50) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `description` varchar(255) NOT NULL DEFAULT '',
  `access_id` int(11) DEFAULT NULL,
  `model_id` int(11) DEFAULT NULL,
  `params` json DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `mac` varchar(50) DEFAULT '',
  `serial` varchar(50) DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `devices_ip_uindex` (`ip`),
  KEY `devices_device_models_id_fk` (`model_id`),
  KEY `devices_device_accesses_id_fk` (`access_id`),
  CONSTRAINT `devices_device_accesses_id_fk` FOREIGN KEY (`access_id`) REFERENCES `device_accesses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `devices_device_models_id_fk` FOREIGN KEY (`model_id`) REFERENCES `device_models` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `devices`
--

LOCK TABLES `devices` WRITE;
/*!40000 ALTER TABLE `devices` DISABLE KEYS */;
INSERT INTO `devices` VALUES (1,'10.0.10.10','OLT','Установлен на крыше, ИБП на сутки',1,1,NULL,'2021-02-11 15:04:48','2021-02-11 15:17:52','','SN110011'),(2,'10.0.10.11','OLT 2','',1,1,NULL,'2021-02-11 15:23:09','2021-02-11 15:23:09','AA:BB:CC:DD:EE:FF','');
/*!40000 ALTER TABLE `devices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_auth_keys`
--

DROP TABLE IF EXISTS `user_auth_keys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_auth_keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `key` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `expired_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_auth_keys_key_uindex` (`key`),
  KEY `user_auth_keys_users_id_fk` (`user_id`),
  CONSTRAINT `user_auth_keys_users_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_auth_keys`
--

LOCK TABLES `user_auth_keys` WRITE;
/*!40000 ALTER TABLE `user_auth_keys` DISABLE KEYS */;
INSERT INTO `user_auth_keys` VALUES (32,13,'0aecd395-6054-4fc4-9c86-909fdedf952a','2021-02-11 15:08:00','2021-02-12 15:08:00'),(33,13,'3d6aeb1e-a851-4149-a059-8fdd1d7d4259','2021-02-11 15:08:08','2021-05-12 16:08:08');
/*!40000 ALTER TABLE `user_auth_keys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_groups`
--

DROP TABLE IF EXISTS `user_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `display` varchar(255) DEFAULT NULL,
  `permissions` json DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_groups`
--

LOCK TABLES `user_groups` WRITE;
/*!40000 ALTER TABLE `user_groups` DISABLE KEYS */;
INSERT INTO `user_groups` VALUES (5,'Admin','1','[\"system_info\", \"user_control\", \"user_display\", \"user_group_control\", \"user_group_show\", \"device_access_control\", \"device_model_control\", \"device_show\", \"device_control\"]');
/*!40000 ALTER TABLE `user_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `group_id` int(11) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_login_uindex` (`login`),
  KEY `users_user_groups_id_fk` (`group_id`),
  CONSTRAINT `users_user_groups_id_fk` FOREIGN KEY (`group_id`) REFERENCES `user_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (13,'admin','Admin','2021-02-11 15:06:07','2021-02-11 15:06:07',5,'d033e22ae348aeb5660fc2140aec35850c4da997');
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

-- Dump completed on 2021-02-11 16:53:47
