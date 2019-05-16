#
# SQL Export
# Created by Querious (201054)
# Created: May 16, 2019 at 2:26:28 PM EDT
# Encoding: Unicode (UTF-8)
#


SET @PREVIOUS_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS;
SET FOREIGN_KEY_CHECKS = 0;


DROP TABLE IF EXISTS `robot_catalog`;


CREATE TABLE `robot_catalog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;




SET FOREIGN_KEY_CHECKS = @PREVIOUS_FOREIGN_KEY_CHECKS;


SET @PREVIOUS_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS;
SET FOREIGN_KEY_CHECKS = 0;


LOCK TABLES `robot_catalog` WRITE;
TRUNCATE `robot_catalog`;
ALTER TABLE `robot_catalog` DISABLE KEYS;
INSERT INTO `robot_catalog` (`id`, `value`) VALUES 
	(1,'jam'),
	(2,'cookie'),
	(3,'cat'),
	(4,'dog'),
	(5,'cake'),
	(6,'orange');
ALTER TABLE `robot_catalog` ENABLE KEYS;
UNLOCK TABLES;




SET FOREIGN_KEY_CHECKS = @PREVIOUS_FOREIGN_KEY_CHECKS;


