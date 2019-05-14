<?php
/**
 *
 * CREATE TABLE `robots` (
 * `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 * `name` varchar(15) NOT NULL,
 * `type` varchar(15) NOT NULL,
 * `year` smallint(4) unsigned NOT NULL,
 * `enabled` tinyint(1) unsigned DEFAULT 0,
 * `options` tinyint(3) unsigned DEFAULT 0,
 * `select` tinyint(3) unsigned DEFAULT 0,
 * PRIMARY KEY (`id`)
 * ) ENGINE=InnoDB AUTO_INCREMENT=2301 DEFAULT CHARSET=latin1;
 *
 */
class Robots_model extends Database_Model {
	protected $table = 'robots';
	protected $rules = [
		'id'=>['field'=>'id','label'=>'Id','rules'=>'required|integer|max_length[20]|less_than[4294967295]|filter_int[20]'],
		'name'=>['field'=>'name','label'=>'Name','rules'=>'required|filter_input'],
		'type'=>['field'=>'type','label'=>'Type','rules'=>'allow_empty|filter_textarea'],
		'year'=>['field'=>'year','label'=>'Year','rules'=>'required|filter_input'],
		'enabled'=>['field'=>'enabled','label'=>'Enabled','rules'=>''],
		'options'=>['field'=>'options','label'=>'Options','rules'=>''],
		'select'=>['field'=>'select','label'=>'Select','rules'=>''],
	];
	protected $rule_sets = [
		'insert'=>'name,type,year,enabled,options,select',
		'update'=>'id,name,type,year,enabled,options,select',
	];

	/**
	 * empty_record
	 *
	 * @return void
	 */
	public function empty_record()
	{
		$object = parent::empty_record();

		$object['enabled'] = 1;
		$object['options'] = 22;
		$object['select'] = 3;

		return $object;
	}

	/**
	 * seed
	 *
	 * @return void
	 */
	public function seed() : void
	{
		$faker = Faker\Factory::create();

		$types = ['droid','mechanical','electronic','ball'];

		$record = [
			'name'=>$faker->name,
			'type'=>$faker->randomElement($types),
			'year'=>$faker->numberBetween(1960,2020),
			'enabled'=>$faker->numberBetween(0,1),
			'options'=>$faker->randomElement([11,22,33]),
			'select'=>$faker->numberBetween(1,5),
		];

		$this->insert($record);
	}

} /* end class */