#
# SQL Export
# Created by Querious (201069)
# Created: November 21, 2019 at 11:07:34 AM EST
# Encoding: Unicode (UTF-8)
#


SET @PREVIOUS_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS;
SET FOREIGN_KEY_CHECKS = 0;


CREATE TABLE `nav` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  `parent_id` int(11) unsigned NOT NULL DEFAULT 0,
  `sort` int(11) unsigned NOT NULL DEFAULT 0,
  `target` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_parent_id` (`parent_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=148 DEFAULT CHARSET=utf8;




SET FOREIGN_KEY_CHECKS = @PREVIOUS_FOREIGN_KEY_CHECKS;


SET @PREVIOUS_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS;
SET FOREIGN_KEY_CHECKS = 0;


LOCK TABLES `nav` WRITE;
TRUNCATE `nav`;
ALTER TABLE `nav` DISABLE KEYS;
INSERT INTO `nav` (`id`, `url`, `text`, `parent_id`, `sort`, `target`) VALUES 
	(2,'','Left Menu',1,12,NULL),
	(3,'','{username}',46,144,NULL),
	(5,'','Admin',2,90,NULL),
	(6,'/admin/dashboard','Dashboard',4,153,NULL),
	(7,'/admin/nav','Nav',5,105,NULL),
	(8,'/admin/permissions','Permissions',5,99,NULL),
	(9,'/admin/roles','Roles',5,96,NULL),
	(10,'/admin/settings','Settings',5,102,NULL),
	(11,'/admin/users','Users',5,93,NULL),
	(46,'','Right Menu Protected',1,141,NULL),
	(47,'','Right Menu Public',1,135,NULL),
	(48,'/login','Login',47,138,NULL),
	(49,'/login/inverted','Logout',3,147,NULL),
	(141,'','RESTful',2,1,NULL),
	(142,'/robot','Robots',141,10,NULL),
	(143,'/catalog','Catalogs',141,20,NULL),
	(144,'/people','People',141,30,NULL),
	(145,'/multi','Mutiple',141,40,NULL),
	(146,'/zipcodes','Zip Codes',141,50,NULL),
	(147,'/food','Food',141,60,'_top');
ALTER TABLE `nav` ENABLE KEYS;
UNLOCK TABLES;




SET FOREIGN_KEY_CHECKS = @PREVIOUS_FOREIGN_KEY_CHECKS;


