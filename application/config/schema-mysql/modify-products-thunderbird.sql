--
-- Table structure for table `thunderbird_products`
--

DROP TABLE IF EXISTS `thunderbird_products`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `thunderbird_products` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(32) NOT NULL default 'Thunderbird',
  `version` varchar(32) NOT NULL default '',
  `version_root` varchar(32) NOT NULL default '',
  `build` int(11) default NULL,
  `locales` text,
  `disable_migration` tinyint(1) default '0',
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

LOCK TABLES `thunderbird_products` WRITE;
INSERT INTO `thunderbird_products` VALUES 

(1, 'Thunderbird', '10.0.1', '10.0.1', 1, 'ar be bn-BD br ca cs da de el en-GB en-US es-AR es-ES et eu fi fr fy-NL ga-IE gd gl he hu id is it ja ko lt nb-NO nl nn-NO pl pt-BR pt-PT rm ru si sk sl sq sv-SE ta-LK tr uk vi zh-CN zh-TW', 0, '2012-02-01 00:00:00', '2012-02-01 00:00:00'),
(2, 'Thunderbird', '10.0.7esr', '10.0.7esr', 1, 'ar be bn-BD br ca cs da de el en-GB en-US es-AR es-ES et eu fi fr fy-NL ga-IE gd gl he hu id is it ja ko lt nb-NO nl nn-NO pl pt-BR pt-PT rm ru si sk sl sq sv-SE ta-LK tr uk vi zh-CN zh-TW', 0, '2012-02-01 00:00:00', '2012-02-01 00:00:00'),
(3, 'Thunderbird', '15.0', '15.0', 1, 'ar be bn-BD br ca cs da de el en-GB en-US es-AR es-ES et eu fi fr fy-NL ga-IE gd gl he hu id is it ja ko lt nb-NO nl nn-NO pl pt-BR pt-PT rm ru si sk sl sq sv-SE ta-LK tr uk vi zh-CN zh-TW', 0, '2012-02-01 00:00:00', '2012-02-01 00:00:00');


UNLOCK TABLES;


