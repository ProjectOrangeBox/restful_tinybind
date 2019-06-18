<?php

class Zipcodes_model extends MY_Model {
	protected $table = 'zipcodes';
	public $empty = ['id'=>'','zipcode'=>''];
	public $required = 'id,zipcode';
} /* end class */

/*
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recordnumber` varchar(128) DEFAULT NULL,
  `zipcode` varchar(128) DEFAULT NULL,
  `zipcodetype` varchar(128) DEFAULT NULL,
  `city` varchar(128) DEFAULT NULL,
  `state` varchar(128) DEFAULT NULL,
  `locationtype` varchar(128) DEFAULT NULL,
  `lat` varchar(128) DEFAULT NULL,
  `long` varchar(128) DEFAULT NULL,
  `xaxis` varchar(128) DEFAULT NULL,
  `yaxis` varchar(128) DEFAULT NULL,
  `zaxis` varchar(128) DEFAULT NULL,
  `worldregion` varchar(128) DEFAULT NULL,
  `country` varchar(128) DEFAULT NULL,
  `locationtext` varchar(128) DEFAULT NULL,
  `location` varchar(128) DEFAULT NULL,
  `decommisioned` varchar(128) DEFAULT NULL,
  `taxreturnsfiled` varchar(128) DEFAULT NULL,
  `estimatedpopulation` varchar(128) DEFAULT NULL,
  `totalwages` varchar(128) DEFAULT NULL,
  `notes` varchar(128) DEFAULT NULL,
*/