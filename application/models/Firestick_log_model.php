<?php

class Firestick_log_model extends Database_model {
	protected $table = '{tablename}';
	protected $primary_key = '{primary}';
	protected $auto_generated_primary = 0;
	protected $rules = [
{complete_rules}	];
	protected $rule_sets = [
		'insert'=>'{insert_required_rules}',
	];

} /* end Firestick_log_model */
