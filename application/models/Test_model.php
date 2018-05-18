<?php

class Test_model extends Database_model {

	public function get_json($variable_name) {
		//$sql = "SELECT CONCAT('{".$variable_name.":[',GROUP_CONCAT(json_object('id',id,'text',text,'url',url,'color',color,'icon',icon)),']}') json FROM orange_nav";

		$sql = $this->create_json_select($variable_name,'id,text,url,color,icon','orange_nav');

		//$sql = $this->create_json_select($variable_name,'id,created,item_no_core,entry,is_a,by','quad_backorder_mgr_history');

		$row = $this->_as_row($this->_database->query($sql));

		return $row->json;
	}

	public function create_json_select($variable_name,$columns,$tablename) {
		$s1 = "SELECT CONCAT('{".$variable_name.":[',GROUP_CONCAT(json_object(";

		$columns = explode(',',$columns);

		foreach ($columns as $column) {
			$s2 .= "'".$column."',`".$column.'`,';
		}

		$s3 .= ")),']}') json FROM `".$tablename."`";

		return $s1.trim($s2,',').$s3;
	}

} /* end class */
