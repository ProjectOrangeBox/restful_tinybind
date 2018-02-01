<?php
/*
DO NOT MODIFY THIS FILE
THIS FILE IS MANAGED COMPLETELY BY THE FRAMEWORK
This file is all of the handlebar helpers in a single file to make attaching them a single action
This file is automatically rebuild by handlebars as needed
Written: 2018-02-01 12:45:52 EST
*/
/* --> /packages/projectorangebox/handlebars/libraries/Hbh_example_helpers.php <-- */
/*
Helpers:

$helpers['foobar'] = function($options) {};

$options =>
	[name] => lex_lowercase # helper name
	[hash] => Array # key value pair
		[size] => 123
		[fullname] => Don Myers
	[contexts] => ... # full context as object
	[_this] => Array # current loop context
		[name] => John
		[phone] => 933.1232
		[age] => 21
	['fn']($options['_this']) # if ??? - don't forget to send in the context
	['inverse']($options['_this']) # else ???- don't forget to send in the context
*/



/*
	{{#exp:lowercase}} ... {{/exp:lowercase}}
*/
$helpers['exp:lowercase'] = function($options) {
	/*
	if (!$output = ci()->handlebars->cache($options)) {
		$output = strtolower($options['fn']($options['_this']));

		ci()->handlebars->cache($options,$output);
	}
	*/

	return strtolower($options['fn']($options['_this']));
};

/*
	{{#exp:uppercase}} ... {{/exp:uppercase}}
*/
$helpers['exp:uppercase'] = function($options) {
	return strtoupper($options['fn']($options['_this']));
};

/*
	{{#exp:uppercase_example}} ... {{/exp:uppercase_example}}
*/
$helpers['exp:uppercase_example'] = function($options) {
	return 'UPPERCASE['.strtoupper($options['fn']($options['_this'])).']';
};

/*
	{{#exp:blocker}} ... {{/exp:blocker}}
*/
$helpers['exp:blocker'] = function($options) {
	return $options['fn']($options['_this']); /* parse inter block content */
};


/*
	{{#exp:channel:entries channel="news" limit="15" category="2" entry_id="147"}}
*/
$helpers['exp:channel:entries'] = function($options) {
	return '['.$options['hash']['channel'].']';
};

/*
{{#if_gt page_title "Current Projects"}}
	True Do This
{{else}}
	False Do This
{{/iif}}
*/
$helpers['if_gt'] = function($value1,$value2,$options) {
	if ($value1 > $value2) {
		$return = $options['fn']();
	} elseif ($options['inverse'] instanceof \Closure) {
		$return = $options['inverse']();
	}
};

$helpers['if_lt'] = function($value1,$value2,$options) {
	if ($value1 < $value2) {
		$return = $options['fn']();
	} elseif ($options['inverse'] instanceof \Closure) {
		$return = $options['inverse']();
	}
};

$helpers['if_ne'] = function($value1,$value2,$options) {
	if ($value1 != $value2) {
		$return = $options['fn']();
	} elseif ($options['inverse'] instanceof \Closure) {
		$return = $options['inverse']();
	}
};

$helpers['if_eq'] = function($value1,$value2,$options) {
	if ($value1 == $value2) {
		$return = $options['fn']();
	} elseif ($options['inverse'] instanceof \Closure) {
		$return = $options['inverse']();
	}
};

/*
{{#iif page_title "=" "Current Projects"}}
	True Do This
{{else}}
	False Do This
{{/iif}}
*/
$helpers['iif'] = function($value1,$op,$value2,$options) {
	$return = '';

	switch ($op) {
		case '=';
			if ($value1 == $value2) {
				$return = $options['fn']();
			} elseif ($options['inverse'] instanceof \Closure) {
				$return = $options['inverse']();
			}
		break;
		case '>';
			if ($value1 > $value2) {
				$return = $options['fn']();
			} elseif ($options['inverse'] instanceof \Closure) {
				$return = $options['inverse']();
			}
		break;
		case '<';
			if ($value1 < $value2) {
				$return = $options['fn']();
			} elseif ($options['inverse'] instanceof \Closure) {
				$return = $options['inverse']();
			}
		break;
		case '!=';
		case '<>';
			if ($value1 != $value2) {
				$return = $options['fn']();
			} elseif ($options['inverse'] instanceof \Closure) {
				$return = $options['inverse']();
			}
		break;
		case '>=';
		case '=>';
			if ($value1 >= $value2) {
				$return = $options['fn']();
			} elseif ($options['inverse'] instanceof \Closure) {
				$return = $options['inverse']();
			}
		break;
		case '<=';
		case '=<';
			if ($value1 <= $value2) {
				$return = $options['fn']();
			} elseif ($options['inverse'] instanceof \Closure) {
				$return = $options['inverse']();
			}
		break;
	}

	return $return;
};

/*
{{#is_even variable}}
	is even!
{{/is_even}}

{{#is_even variable}}
	is even!
{{else}}
	is not even!
{{/is_even}}
*/
$helpers['is_even'] = function($value,$options) {
	/* parse the "then" (fn) or the "else" (inverse) */
	$return = '';

	if (!($value % 2)) {
		$return = $options['fn']($options['_this']);
	} elseif ($options['inverse'] instanceof \Closure) {
		$return = $options['inverse']($options['_this']);
	}

	return $return;
};

/*
{{#is_odd variable}}
	is odd!
{{/is_odd}}

{{#is_odd variable}}
	is odd!
{{else}}
	is not odd!
{{/is_odd}}
*/
$helpers['is_odd'] = function($value,$options) {
	/* parse the "then" (fn) or the "else" (inverse) */
	$return = '';

	if ($value % 2) {
		$return = $options['fn']($options['_this']);
	} elseif ($options['inverse'] instanceof \Closure) {
		$return = $options['inverse']($options['_this']);
	}

	return $return;
};

/*
in is a reference to the data array sent in

{{set name="age" value=page_title}}
{{set name="foo" value="bar"}}

*/
$helpers['set'] = function($options) use (&$in) {
	$in['html_'.$options['hash']['name']] = $options['hash']['value'];

	return '';
};

/*
_this is a the data array sent in

{{get name="age"}}
*/
$helpers['get'] = function($options) {
	return $options['_this']['html_'.$options['hash']['name']];
};

/*
query - looping block context

<table class="table table-striped table-bordered">
	<tr>
		<th>Index</th>
		<th>Name</th>
		<th>Variable</th>
		<th>Value</th>
		<th>Odd/Even</th>
		<th>First</th>
		<th>Last</th>
		<th>Action</th>
	</tr>
{{#exp:query sql="select * from orange_settings"}}
	<tr class="{{#if query_odd}}oddman{{else}}evenman{{/if}}">
		<td>{{query_index_row}}</td>
		<td>{{name}}</td>
		<td>{{variable}}</td>
		<td>{{value}}</td>
		<td>{{#exp:uppercase cache="60"}}{{#if query_odd}}odd{{else}}even{{/if}}{{/exp:uppercase}}</td>
		<td>{{query_first_row}}</td>
		<td>{{query_last_row}}</td>
		<td><a href="/admin/content/snippets/edit/{{id}}">Edit</a></td>
	</tr>
{{/exp:query}}
</table>

*/
$helpers['exp:query'] = function($options) {
	$output = '';
	$index = 1;

	$results = ci()->db->query($options['hash']['sql']);

	$number_rows = $results->num_rows();

	while ($row = $results->unbuffered_row('array')) {
		$row['query_num_rows'] = $number_rows;
		$row['query_first_row'] = ($index == 1);
		$row['query_last_row'] = ($index == $number_rows);
		$row['query_odd'] = !($index % 2 == 0);
		$row['query_even'] = ($index % 2 == 0);

		/* increases this last */
		$row['query_index_row'] = $index++;

		$output .= $options['fn']($row);
	}

	return $output;
};

$helpers['q:cache_demo'] = function($options) {
	/*
	if (!$output = ci()->handlebars->cache($options)) {
		$output = 'Cached on: '.date('Y-m-d H:i:s').'  until '.date('Y-m-d H:i:s',strtotime('+'.(int)$options['hash']['cache'].' minutes'));

		ci()->handlebars->cache($options,$output);
	}
	*/

	return $output;
};

/*
{{#exp:channel:entries channel="news" limit="15" category="2" entry_id="147"}}

<h3>The title is "{{title}}"</h3>

<p>The body is "{{body}}"</p>

<div class="date">Posted on {{format:date entry_date format="Y-m-d H:i:s"}}</div>

{{/exp:channel:entries}}
*/
$helpers['exp:channel:entries'] = function($options) use (&$in) {
	// channel="news" limit="15" category="2" entry_id="147"
	$channel = $options['hash']['channel'];
	$limit = $options['hash']['limit'];
	$category = $options['hash']['category'];
	$entry_id = $options['hash']['entry_id'];

	$in['title'] = 'This is the title';
	$in['body'] = 'This is the body';
	$in['entry_date'] = date('U');

	return $options['fn']($in);
};

/*
<div class="date">Posted on {{format:date entry_date format="Y-m-d H:i:s"}}</div>
*/
$helpers['format:date'] = function($arg1,$options) {
	return date($options['hash']['format'],$arg1);
};

$helpers['partial'] = function($options) {
	$file = $options['hash']['file'];

	return '++'.$file.'++';
};

/* --> /packages/projectorangebox/handlebars/libraries/Hbh_snippet.php <-- */

/* {{snippet name="foo"}} */
$helpers['snippet'] = function($options) {
	$name = $options['hash']['name'];

	$snippets_name = ci('c_snippet_model')->catalog('name','value');
	$snippets_variable = ci('c_snippet_model')->catalog('variable','value');

	$value = '';

	if (isset($snippets_name[$name])) {
		$value = $snippets_name[$name];
	} elseif (isset($snippets_variable[$name])) {
		$value = $snippets_variable[$name];
	}

	return $value;
};

/* {{snippet-a name="foo"}} */
$helpers['snippet-a'] = function($options) {
	$name = $options['hash']['name'];

	ci()->load->model('c_snippet_model');

	$records = ci()->c_snippet_model->catalog('variable','value');

	$target = (substr($records[$name],0,4) == 'http') ? ' target="_blank"' : '';

	return '<a href="'.$records[$name].'"'.$target.'>';
};

/* {{snippet-copyright name="foo"}} */
$helpers['snippet-copyright'] = function($options) {
	$name = (isset($options['hash']['name'])) ? $options['hash']['name'] : 'copyright';

	ci()->load->model('c_snippet_model');

	$records = ci()->c_snippet_model->catalog('variable','value');

	return str_replace('{year}',date('Y'),$records[$name]);
};