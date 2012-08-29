--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `products` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(32) NOT NULL default 'Firefox',
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

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;

INSERT INTO `products` VALUES 

(1, 'Firefox', '4.0.1', '4.0.1', 1, 'af ak ar ast be bg bn-BD bn-IN br bs ca cs cy da de el en-GB en-US en-ZA eo es-AR es-CL es-ES es-MX et eu fa fi fr fy-NL ga-IE gd gl gu-IN he hi-IN hr hu hy-AM id is it ja kk kn ko ku lg lt lv mai mk ml mr nb-NO nl nn-NO nso or pa-IN pl pt-BR pt-PT rm ro ru si sk sl son sq sr sv-SE ta-LK ta te th tr uk vi zh-CN zh-TW zu', 0, '2011-03-22 10:00:00', NULL),
(2, 'Firefox', '10.0.7esr', '10.0.7esr', 1, 'af ak ar ast be bg bn-BD bn-IN br bs ca cs cy da de el en-GB en-US en-ZA eo es-AR es-CL es-ES es-MX et eu fa fi fr fy-NL ga-IE gd gl gu-IN he hi-IN hr hu hy-AM id is it ja kk kn ko ku lg lt lv mai mk ml mr nb-NO nl nn-NO nso or pa-IN pl pt-BR pt-PT rm ro ru si sk sl son sq sr sv-SE ta-LK ta te th tr uk vi zh-CN zh-TW zu', 0, '2012-03-01 10:00:00', NULL),
(3, 'Firefox', '15.0', '15.0', 1, 'af ak ar ast be bg bn-BD bn-IN br bs ca cs cy da de el en-GB en-US en-ZA eo es-AR es-CL es-ES es-MX et eu fa fi fr fy-NL ga-IE gd gl gu-IN he hi-IN hr hu hy-AM id is it ja kk kn ko ku lg lt lv mai mk ml mr nb-NO nl nn-NO nso or pa-IN pl pt-BR pt-PT rm ro ru si sk sl son sq sr sv-SE ta-LK ta te th tr uk vi zh-CN zh-TW zu', 0, '2012-03-01 10:00:00', NULL);


/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;


