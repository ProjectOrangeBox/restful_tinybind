#
# SQL Export
# Created by Querious (201054)
# Created: May 16, 2019 at 2:26:39 PM EDT
# Encoding: Unicode (UTF-8)
#


SET @PREVIOUS_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS;
SET FOREIGN_KEY_CHECKS = 0;


DROP TABLE IF EXISTS `robots`;


CREATE TABLE `robots` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(15) NOT NULL,
  `type` varchar(15) NOT NULL,
  `year` smallint(4) unsigned NOT NULL,
  `enabled` tinyint(1) unsigned DEFAULT 0,
  `options` tinyint(3) unsigned DEFAULT 0,
  `select` tinyint(3) unsigned DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2311 DEFAULT CHARSET=latin1;




SET FOREIGN_KEY_CHECKS = @PREVIOUS_FOREIGN_KEY_CHECKS;


SET @PREVIOUS_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS;
SET FOREIGN_KEY_CHECKS = 0;


LOCK TABLES `robots` WRITE;
TRUNCATE `robots`;
ALTER TABLE `robots` DISABLE KEYS;
INSERT INTO `robots` (`id`, `name`, `type`, `year`, `enabled`, `options`, `select`) VALUES 
	(2,'Ms. Angie','electronic',1995,1,33,2),
	(3,'Belle Mosciski','ball',1976,1,11,4),
	(4,'Sarah Rodriguez','ball',2004,1,33,2),
	(5,'Amy Myers','electronic',1970,1,11,5),
	(6,'Darien Fritsch','droid',2019,1,11,4),
	(7,'Margot Grimes I','ball',1963,1,11,4),
	(8,'Jeanette Hayes','droid',1996,1,11,5),
	(9,'Dr. Marcel Bahr','mechanical',2001,0,11,5),
	(10,'Prof. Tyree Bre','droid',1962,0,22,4),
	(11,'Joanne Ortiz','ball',1998,1,22,4),
	(12,'Rasheed Denesik','droid',1990,0,33,1),
	(13,'Lionel Wyman','ball',2001,1,33,1),
	(14,'Erna Wunsch','ball',1975,1,33,4),
	(15,'Rodrigo Bartell','mechanical',2006,1,22,4),
	(16,'Yadira Trantow ','electronic',1979,0,33,5),
	(17,'Miss Myah Hilpe','ball',2000,0,11,2),
	(18,'Meaghan Schumm','droid',1994,1,33,5),
	(19,'Mr. Nolan Heane','mechanical',1965,1,22,5),
	(20,'Mrs. Theresa St','ball',1993,1,22,4),
	(21,'Katrina Connell','mechanical',1985,1,33,2),
	(22,'Vickie Nikolaus','ball',1966,1,33,1),
	(23,'Susie Paucek I','ball',2015,0,22,2),
	(24,'Prof. Peyton Ha','droid',1970,0,22,3),
	(25,'Austyn Casper','ball',2018,1,33,3),
	(26,'Prof. Neva Herm','ball',1995,1,22,2),
	(27,'Zelma Kirlin V','ball',2004,1,11,3),
	(28,'Roberta Auer','mechanical',1964,0,33,3),
	(29,'Tevin Kutch','ball',1969,1,33,3),
	(30,'Weston Parker','electronic',2011,0,11,1),
	(31,'Neal Gutmann DD','droid',2002,0,11,1),
	(32,'Brett Lesch','mechanical',1964,1,33,5),
	(33,'Leila Schulist','electronic',1976,0,11,5),
	(34,'Velma Botsford','ball',2019,1,22,3),
	(35,'Myles Lindgren','electronic',2009,0,22,3),
	(36,'Davon Wehner','mechanical',1964,1,33,5),
	(37,'Neva Wilkinson','mechanical',1997,1,33,2),
	(38,'Bryana Bernier ','mechanical',1998,0,11,2),
	(39,'Karine Kirlin','electronic',1986,1,22,5),
	(40,'Loma Eichmann','droid',1961,1,33,2),
	(41,'Jerald Schmeler','electronic',1960,0,33,4),
	(44,'Keira Stroman','droid',2008,1,33,1),
	(45,'Dolly Kertzmann','droid',1994,1,11,2),
	(46,'Miss Roberta Ma','droid',1976,1,22,2),
	(47,'Mortimer Schumm','electronic',1988,1,11,2),
	(49,'Mr. Hazel Chris','droid',1998,0,22,5),
	(50,'Daron Orn','ball',1963,1,33,4),
	(51,'Paxton Sporer','electronic',1994,1,22,3),
	(52,'Carlotta Bernha','mechanical',1986,0,33,4),
	(53,'Abagail McGlynn','mechanical',1963,1,33,4),
	(54,'Foo Bar','ball',2019,1,11,1),
	(55,'Damian Metz','droid',1966,1,11,1),
	(56,'Ms. Janessa Col','droid',1983,0,11,5),
	(57,'Mr. Raphael Pfe','electronic',2020,1,11,2),
	(58,'Mrs. Effie Stre','mechanical',1978,0,11,3),
	(59,'Billie Block','ball',1997,1,22,2),
	(60,'Prof. Darien Th','electronic',1994,1,22,5),
	(61,'Lavinia McKenzi','electronic',1997,0,11,4),
	(62,'Kirstin Stanton','mechanical',1980,0,22,4),
	(63,'Wade Prosacco','droid',2011,0,11,5),
	(64,'Theodore Shield','ball',1970,1,11,1),
	(65,'Cleta Hand MD','mechanical',1987,1,33,5),
	(66,'Janelle Marks','electronic',1983,1,22,2),
	(67,'Lucinda Wisozk','ball',2012,0,33,3),
	(68,'Tressa Kuhn PhD','mechanical',2006,0,11,4),
	(69,'Zackary Watsica','mechanical',1975,0,11,3),
	(70,'Dejuan Roberts','electronic',1987,1,22,1),
	(71,'Dr. Alexander C','electronic',1973,0,11,4),
	(72,'Zelma Schmidt S','mechanical',2004,0,33,5),
	(73,'Jaren Haley','mechanical',1962,1,33,1),
	(75,'Angel Marquardt','ball',2000,0,33,5),
	(76,'Schuyler Treute','droid',1997,0,11,3),
	(77,'Dr. Jaydon Brue','mechanical',2020,0,33,2),
	(78,'Ms. Keira Mosci','ball',1987,1,11,3),
	(79,'Kristy Lakin','electronic',2018,1,22,5),
	(80,'Eloy McDermott','electronic',1993,1,33,5),
	(81,'Turner Sporer','ball',2005,0,22,2),
	(82,'Joelle Deckow','ball',1999,1,33,3),
	(83,'Dr. Karelle Nic','ball',1966,1,11,4),
	(84,'Moshe Bruen','mechanical',1997,1,33,3),
	(85,'Gladyce Hickle','ball',1970,1,11,3),
	(86,'Vance Leannon','electronic',1974,0,33,4),
	(87,'Nigel Daugherty','mechanical',1990,1,11,3),
	(88,'Elmira Marks','ball',1979,1,11,2),
	(89,'Florida Keeling','ball',2000,1,33,3),
	(90,'Theresa Halvors','mechanical',2001,0,33,4),
	(91,'Prof. Frederiqu','droid',1997,0,11,4),
	(92,'Gladyce Quitzon','droid',2007,1,22,2),
	(93,'Ms. Dakota Stie','mechanical',1970,0,11,4),
	(94,'Van Gulgowski','electronic',1963,1,33,2),
	(95,'Freda Hettinger','droid',1992,0,22,4),
	(96,'Tommie Ledner','droid',2018,1,22,4),
	(97,'Dessie Olson','mechanical',1967,0,11,4),
	(98,'Parker Thiel','mechanical',1973,1,33,3),
	(99,'Margarett Price','electronic',1965,1,11,1),
	(101,'Roxanne Buckrid','ball',2009,1,33,1),
	(102,'Alanis Terry I','mechanical',2013,0,33,2),
	(103,'Raina Thiel','electronic',1964,0,22,3),
	(104,'Kennedi Schimme','droid',1966,1,11,1),
	(105,'Einar Braun DDS','droid',1972,0,22,3),
	(106,'Lenna Schamberg','mechanical',1998,1,22,2),
	(2308,'Don Myers','Robot',2019,1,22,3),
	(2309,'Foo Bar','ball',1988,1,22,4),
	(2310,'Don Myers','Computer',1988,1,22,0);
ALTER TABLE `robots` ENABLE KEYS;
UNLOCK TABLES;




SET FOREIGN_KEY_CHECKS = @PREVIOUS_FOREIGN_KEY_CHECKS;


