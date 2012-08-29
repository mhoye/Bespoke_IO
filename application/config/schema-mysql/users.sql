-- MySQL dump 10.11
--
-- Host: localhost    Database: besds
-- ------------------------------------------------------
-- Server version	5.0.77-log

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
-- Table structure for table `login_email_verification_tokens`
--

DROP TABLE IF EXISTS `login_email_verification_tokens`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `login_email_verification_tokens` (
  `id` int(11) NOT NULL auto_increment,
  `login_id` int(11) default NULL,
  `token` varchar(32) default NULL,
  `value` varchar(255) default NULL,
  PRIMARY KEY  (`id`),
  KEY `login_email_verification_tokens_ibfk_2` (`login_id`),
  CONSTRAINT `login_email_verification_tokens_ibfk_2` FOREIGN KEY (`login_id`) REFERENCES `logins` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `login_password_reset_tokens`
--

DROP TABLE IF EXISTS `login_password_reset_tokens`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `login_password_reset_tokens` (
  `id` int(11) NOT NULL auto_increment,
  `login_id` int(11) default NULL,
  `token` varchar(32) default NULL,
  PRIMARY KEY  (`id`),
  KEY `login_password_reset_tokens_ibfk_2` (`login_id`),
  CONSTRAINT `login_password_reset_tokens_ibfk_2` FOREIGN KEY (`login_id`) REFERENCES `logins` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `logins`
--

DROP TABLE IF EXISTS `logins`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `logins` (
  `id` int(11) NOT NULL auto_increment,
  `login_name` varchar(64) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(32) NOT NULL,
  `created` datetime default NULL,
  `last_login` datetime default NULL,
  `active` tinyint(2) NOT NULL default '1',
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `login_name` (`login_name`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `logins_profiles`
--

DROP TABLE IF EXISTS `logins_profiles`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `logins_profiles` (
  `id` int(11) NOT NULL auto_increment,
  `login_id` int(11) NOT NULL,
  `profile_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `login_id_profile_id` (`login_id`,`profile_id`),
  KEY `logins_profiles_ibfk_2` (`profile_id`),
  CONSTRAINT `logins_profiles_ibfk_1` FOREIGN KEY (`login_id`) REFERENCES `logins` (`id`) ON DELETE CASCADE,
  CONSTRAINT `logins_profiles_ibfk_2` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;



--
-- Table structure for table `profile_attributes`
--

DROP TABLE IF EXISTS `profile_attributes`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `profile_attributes` (
  `id` int(11) NOT NULL auto_increment,
  `profile_id` int(11) NOT NULL,
  `name` varchar(255) default NULL,
  `value` text,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `profile_id_name` (`profile_id`,`name`),
  CONSTRAINT `profile_attributes_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `profiles`
--

DROP TABLE IF EXISTS `profiles`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `profiles` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` varchar(40) NOT NULL,
  `screen_name` varchar(64) NOT NULL,
  `first_name` varchar(128) NOT NULL,
  `last_name` varchar(128) NOT NULL,
  `website` varchar(255) NOT NULL,
  `phone` varchar(24) default NULL,
  `fax` varchar(24) default NULL,
  `is_personal` tinyint(2) NOT NULL default '1',
  `org_name` varchar(255) default NULL,
  `org_type` varchar(32) default NULL,
  `org_type_other` varchar(32) default NULL,
  `address_1` varchar(255),
  `address_2` varchar(255),
  `city` varchar(255),
  `state` varchar(32),
  `zip` varchar(32),
  `country` varchar(255),
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uuid` (`uuid`),
  UNIQUE KEY `screen_name` (`screen_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `profiles_roles`
--

DROP TABLE IF EXISTS `profiles_roles`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `profiles_roles` (
  `id` int(11) NOT NULL auto_increment,
  `role_id` int(11) NOT NULL,
  `profile_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `role_id_profile_id` (`role_id`,`profile_id`),
  KEY `profiles_roles_ibfk_2` (`profile_id`),
  CONSTRAINT `profiles_roles_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `profiles_roles_ibfk_2` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(32) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2009-06-26  3:33:57
