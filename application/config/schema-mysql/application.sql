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
-- Table structure for table `logevents`
--

DROP TABLE IF EXISTS `logevents`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `logevents` (
  `id` int(11) NOT NULL auto_increment,
  `uuid` char(64) default NULL,
  `profile_id` int(11) default NULL,
  `action` varchar(255) default NULL,
  `details` text,
  `data` text,
  `created` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `uuid` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `message_queue`
--

DROP TABLE IF EXISTS `message_queue`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `message_queue` (
  `uuid` varchar(40) NOT NULL,
  `owner` varchar(255) default NULL,
  `batch_uuid` varchar(40) default NULL,
  `batch_seq` int(11) default '0',
  `created` datetime default NULL,
  `modified` datetime default NULL,
  `scheduled_for` datetime default NULL,
  `reserved_at` datetime default NULL,
  `reserved_until` datetime default NULL,
  `finished_at` datetime default NULL,
  `priority` int(11) default '0',
  `topic` varchar(255) default NULL,
  `object` varchar(255) default NULL,
  `method` varchar(255) default NULL,
  `context` text,
  `body` text,
  `signature` char(32) default NULL,
  PRIMARY KEY  (`uuid`),
  KEY `created` (`created`),
  KEY `priority` (`priority`),
  KEY `batch_seq` (`batch_seq`),
  KEY `signature` (`signature`),
  KEY `reserved_at` (`reserved_at`),
  KEY `finished_at` (`finished_at`),
  KEY `scheduled_for` (`scheduled_for`),
  KEY `batch_uuid` (`batch_uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;


--
-- Table structure for table `repacks`
--

DROP TABLE IF EXISTS `repacks`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `repacks` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uuid` char(64) NOT NULL default '0',
  `created` datetime default NULL,
  `modified` datetime default NULL,
  `profile_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `short_name` varchar(128) default NULL,
  `title` varchar(255) default NULL,
  `description` text,
  `state` int(11) default '0',
  `is_public` tinyint(2) NOT NULL default '0',
  `json_data` text,
  PRIMARY KEY  (`id`),
  KEY `created_by` (`profile_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `thunderbird_repacks`
--

DROP TABLE IF EXISTS `thunderbird_repacks`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `thunderbird_repacks` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uuid` char(64) NOT NULL default '0',
  `created` datetime default NULL,
  `modified` datetime default NULL,
  `profile_id` int(10) unsigned NOT NULL,
  `thunderbird_product_id` int(10) unsigned NOT NULL,
  `short_name` varchar(128) default NULL,
  `title` varchar(255) default NULL,
  `description` text,
  `state` int(11) default '0',
  `is_public` tinyint(2) NOT NULL default '0',
  `json_data` text,
  PRIMARY KEY  (`id`),
  KEY `created_by` (`profile_id`)
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
