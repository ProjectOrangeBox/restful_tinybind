<?php

class Nav_model extends MY_Model {
	protected $table = 'nav';

	public function get(int $parentId = 0,int $level = 0,bool $removeEmptyParents = true) : array
	{
		$navigationMenu = [];

		foreach ($this->db->order_by('sort')->get_where($this->table,['parent_id'=>$parentId])->result_array() as $record) {
			$record['level'] = $level;

			$children = $this->get((int)$record['id'], $level + 1, $removeEmptyParents);

			$record['children'] = (!empty($children)) ? $children : false;

			if ($removeEmptyParents) {
				if (!(empty($record['url']) && !is_array($record['children']))) {
					$navigationMenu[] = $record;
				}
			} else {
				$navigationMenu[] = $record;
			}
		}

		return $navigationMenu;
	}

} /* end class */