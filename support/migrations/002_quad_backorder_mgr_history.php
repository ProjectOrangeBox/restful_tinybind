<?php

/* 002_quad_backorder_mgr_history */

class Migration_Quad_backorder_mgr_history extends CI_Migration {

	public function up() {
$sql = <<<EOF
	CREATE TABLE `quad_backorder_mgr_history` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NULL DEFAULT current_timestamp(),
  `item_no_core` varchar(32) DEFAULT 'NULL',
  `entry` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
EOF;

		$this->db->query($sql);
	}

	public function down() {
		$this->dbforge->drop_table('quad_backorder_mgr_history');
	}

} /* end migration */
