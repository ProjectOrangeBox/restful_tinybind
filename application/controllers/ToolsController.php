<?php

class ToolsController extends MY_Controller {

	public function indexAction() {
		$mysqli = new mysqli('172.16.62.199', 'backorder_www_user', 'wnx4snFEsuk5jUrb', 'ecometry_udalink_batch');

		$b1 = date('U');

		$mysqli->query('select * from backorder_mgr');

		$b2 = date('U');

		echo ($b2-$b1).'<br>';

		$mysqli->query('select * from backorder_mgr_open_pos');

		$b3 = date('U');

		echo ($b3-$b2).'<br>';

		$mysqli->close();
	}

} /* end class */
