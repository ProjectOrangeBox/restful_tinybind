<?php  function lcr5a735365068b1encq($cx, $var) {
  if ($var instanceof LS) {
   return (string)$var;
  }

  return str_replace(array('=', '`', '&#039;'), array('&#x3D;', '&#x60;', '&#x27;'), htmlspecialchars(lcr5a735365068b1raw($cx, $var), ENT_QUOTES, 'UTF-8'));
 }

 function lcr5a735365068b1hbch($cx, $ch, $vars, $op, &$_this) {
  if (isset($cx['blparam'][0][$ch])) {
   return $cx['blparam'][0][$ch];
  }

  $options = array(
   'name' => $ch,
   'hash' => $vars[1],
   'contexts' => count($cx['scopes']) ? $cx['scopes'] : array(null),
   'fn.blockParams' => 0,
   '_this' => &$_this
  );

  if ($cx['flags']['spvar']) {
   $options['data'] = $cx['sp_vars'];
  }

  return lcr5a735365068b1exch($cx, $ch, $vars, $options);
 }

 function lcr5a735365068b1sec($cx, $v, $bp, $in, $each, $cb, $else = null) {
  $push = ($in !== $v) || $each;

  $isAry = is_array($v) || ($v instanceof \ArrayObject);
  $isTrav = $v instanceof \Traversable;
  $loop = $each;
  $keys = null;
  $last = null;
  $isObj = false;

  if ($isAry && $else !== null && count($v) === 0) {
   $ret = $else($cx, $in);
   return $ret;
  }

  // #var, detect input type is object or not
  if (!$loop && $isAry) {
   $keys = array_keys($v);
   $loop = (count(array_diff_key($v, array_keys($keys))) == 0);
   $isObj = !$loop;
  }

  if (($loop && $isAry) || $isTrav) {
   if ($each && !$isTrav) {
    // Detect input type is object or not when never done once
    if ($keys == null) {
     $keys = array_keys($v);
     $isObj = (count(array_diff_key($v, array_keys($keys))) > 0);
    }
   }
   $ret = array();
   if ($push) {
    $cx['scopes'][] = $in;
   }
   $i = 0;
   if ($cx['flags']['spvar']) {
    $old_spvar = $cx['sp_vars'];
    $cx['sp_vars'] = array_merge(array('root' => $old_spvar['root']), $old_spvar, array('_parent' => $old_spvar));
    if (!$isTrav) {
     $last = count($keys) - 1;
    }
   }

   $isSparceArray = $isObj && (count(array_filter(array_keys($v), 'is_string')) == 0);
   foreach ($v as $index => $raw) {
    if ($cx['flags']['spvar']) {
     $cx['sp_vars']['first'] = ($i === 0);
     $cx['sp_vars']['last'] = ($i == $last);
     $cx['sp_vars']['key'] = $index;
     $cx['sp_vars']['index'] = $isSparceArray ? $index : $i;
     $i++;
    }
    if (isset($bp[0])) {
     $raw = lcr5a735365068b1m($cx, $raw, array($bp[0] => $raw));
    }
    if (isset($bp[1])) {
     $raw = lcr5a735365068b1m($cx, $raw, array($bp[1] => $cx['sp_vars']['index']));
    }
    $ret[] = $cb($cx, $raw);
   }
   if ($cx['flags']['spvar']) {
    if ($isObj) {
     unset($cx['sp_vars']['key']);
    } else {
     unset($cx['sp_vars']['last']);
    }
    unset($cx['sp_vars']['index']);
    unset($cx['sp_vars']['first']);
    $cx['sp_vars'] = $old_spvar;
   }
   if ($push) {
    array_pop($cx['scopes']);
   }
   return join('', $ret);
  }
  if ($each) {
   if ($else !== null) {
    $ret = $else($cx, $v);
    return $ret;
   }
   return '';
  }
  if ($isAry) {
   if ($push) {
    $cx['scopes'][] = $in;
   }
   $ret = $cb($cx, $v);
   if ($push) {
    array_pop($cx['scopes']);
   }
   return $ret;
  }

  if ($cx['flags']['mustsec']) {
   return $v ? $cb($cx, $in) : '';
  }

  if ($v === true) {
   return $cb($cx, $in);
  }

  if (($v !== null) && ($v !== false)) {
   return $cb($cx, $v);
  }

  if ($else !== null) {
   $ret = $else($cx, $in);
   return $ret;
  }

  return '';
 }

 function lcr5a735365068b1hbbch($cx, $ch, $vars, &$_this, $inverted, $cb, $else = null) {
  $options = array(
   'name' => $ch,
   'hash' => $vars[1],
   'contexts' => count($cx['scopes']) ? $cx['scopes'] : array(null),
   'fn.blockParams' => 0,
   '_this' => &$_this,
  );

  if ($cx['flags']['spvar']) {
   $options['data'] = $cx['sp_vars'];
  }

  if (isset($vars[2])) {
   $options['fn.blockParams'] = count($vars[2]);
  }

  // $invert the logic
  if ($inverted) {
   $tmp = $else;
   $else = $cb;
   $cb = $tmp;
  }

  $options['fn'] = function ($context = '_NO_INPUT_HERE_', $data = null) use ($cx, &$_this, $cb, $options, $vars) {
   if ($cx['flags']['echo']) {
    ob_start();
   }
   if (isset($data['data'])) {
    $old_spvar = $cx['sp_vars'];
    $cx['sp_vars'] = array_merge(array('root' => $old_spvar['root']), $data['data'], array('_parent' => $old_spvar));
   }
   $ex = false;
   if (isset($data['blockParams']) && isset($vars[2])) {
    $ex = array_combine($vars[2], array_slice($data['blockParams'], 0, count($vars[2])));
    array_unshift($cx['blparam'], $ex);
   } else if (isset($cx['blparam'][0])) {
    $ex = $cx['blparam'][0];
   }
   if (($context === '_NO_INPUT_HERE_') || ($context === $_this)) {
    $ret = $cb($cx, is_array($ex) ? lcr5a735365068b1m($cx, $_this, $ex) : $_this);
   } else {
    $cx['scopes'][] = $_this;
    $ret = $cb($cx, is_array($ex) ? lcr5a735365068b1m($cx, $context, $ex) : $context);
    array_pop($cx['scopes']);
   }
   if (isset($data['data'])) {
    $cx['sp_vars'] = $old_spvar;
   }
   return $cx['flags']['echo'] ? ob_get_clean() : $ret;
  };

  if ($else) {
   $options['inverse'] = function ($context = '_NO_INPUT_HERE_') use ($cx, $_this, $else) {
    if ($cx['flags']['echo']) {
     ob_start();
    }
    if ($context === '_NO_INPUT_HERE_') {
     $ret = $else($cx, $_this);
    } else {
     $cx['scopes'][] = $_this;
     $ret = $else($cx, $context);
     array_pop($cx['scopes']);
    }
    return $cx['flags']['echo'] ? ob_get_clean() : $ret;
   };
  } else {
   $options['inverse'] = function () {
    return '';
   };
  }

  return lcr5a735365068b1exch($cx, $ch, $vars, $options);
 }

 function lcr5a735365068b1ifvar($cx, $v, $zero) {
  return ($v !== null) && ($v !== false) && ($zero || ($v !== 0) && ($v !== 0.0)) && ($v !== '') && (is_array($v) ? (count($v) > 0) : true);
 }

 function lcr5a735365068b1raw($cx, $v, $ex = 0) {
  if ($ex) {
   return $v;
  }

  if ($v === true) {
   if ($cx['flags']['jstrue']) {
    return 'true';
   }
  }

  if (($v === false)) {
   if ($cx['flags']['jstrue']) {
    return 'false';
   }
  }

  if (is_array($v)) {
   if ($cx['flags']['jsobj']) {
    if (count(array_diff_key($v, array_keys(array_keys($v)))) > 0) {
     return '[object Object]';
    } else {
     $ret = array();
     foreach ($v as $k => $vv) {
      $ret[] = lcr5a735365068b1raw($cx, $vv);
     }
     return join(',', $ret);
    }
   } else {
    return 'Array';
   }
  }

  return "$v";
 }

 function lcr5a735365068b1exch($cx, $ch, $vars, &$options) {
  $args = $vars[0];
  $args[] = $options;
  $e = null;
  $r = true;

  try {
   $r = call_user_func_array($cx['helpers'][$ch], $args);
  } catch (\Exception $E) {
   $e = "Runtime: call custom helper '$ch' error: " . $E->getMessage();
  }

  if($e !== null) {
   lcr5a735365068b1err($cx, $e);
  }

  return $r;
 }

 function lcr5a735365068b1m($cx, $a, $b) {
  if (is_array($b)) {
   if ($a === null) {
    return $b;
   } else if (is_array($a)) {
    return array_merge($a, $b);
   } else if (($cx['flags']['method'] || $cx['flags']['prop']) && is_object($a)) {
    foreach ($b as $i => $v) {
     $a->$i = $v;
    }
   }
  }
  return $a;
 }

 function lcr5a735365068b1err($cx, $err) {
  if ($cx['flags']['debug'] & $cx['constants']['DEBUG_ERROR_LOG']) {
   error_log($err);
   return;
  }
  if ($cx['flags']['debug'] & $cx['constants']['DEBUG_ERROR_EXCEPTION']) {
   throw new \Exception($err);
  }
 }

if (!class_exists("LS")) {
class LS {
 public static $jsContext = array (
  'flags' => 
  array (
    'jstrue' => 1,
    'jsobj' => 1,
  ),
);
    public function __construct($str, $escape = false) {
        $this->string = $escape ? (($escape === 'encq') ? static::encq(static::$jsContext, $str) : static::enc(static::$jsContext, $str)) : $str;
    }
    public function __toString() {
        return $this->string;
    }
    public static function stripExtendedComments($template) {
        return preg_replace(static::EXTENDED_COMMENT_SEARCH, '{{! }}', $template);
    }
    public static function escapeTemplate($template) {
        return addcslashes(addcslashes($template, '\\'), "'");
    }
    public static function raw($cx, $v, $ex = 0) {
        if ($ex) {
            return $v;
        }

        if ($v === true) {
            if ($cx['flags']['jstrue']) {
                return 'true';
            }
        }

        if (($v === false)) {
            if ($cx['flags']['jstrue']) {
                return 'false';
            }
        }

        if (is_array($v)) {
            if ($cx['flags']['jsobj']) {
                if (count(array_diff_key($v, array_keys(array_keys($v)))) > 0) {
                    return '[object Object]';
                } else {
                    $ret = array();
                    foreach ($v as $k => $vv) {
                        $ret[] = static::raw($cx, $vv);
                    }
                    return join(',', $ret);
                }
            } else {
                return 'Array';
            }
        }

        return "$v";
    }
    public static function enc($cx, $var) {
        return htmlspecialchars(static::raw($cx, $var), ENT_QUOTES, 'UTF-8');
    }
    public static function encq($cx, $var) {
        return str_replace(array('=', '`', '&#039;'), array('&#x3D;', '&#x60;', '&#x27;'), htmlspecialchars(static::raw($cx, $var), ENT_QUOTES, 'UTF-8'));
    }
}
}
return function ($in = null, $options = null) {
    $helpers = array(            'exp:lowercase' => function($options) {
	/*
	if (!$output = ci()->handlebars->cache($options)) {
		$output = strtolower($options['fn']($options['_this']));

		ci()->handlebars->cache($options,$output);
	}
	*/

	return strtolower($options['fn']($options['_this']));
},
            'exp:uppercase' => function($options) {
	return strtoupper($options['fn']($options['_this']));
},
            'exp:channel:entries' => function($options) use (&$in) {
	// channel="news" limit="15" category="2" entry_id="147"
	$channel = $options['hash']['channel'];
	$limit = $options['hash']['limit'];
	$category = $options['hash']['category'];
	$entry_id = $options['hash']['entry_id'];

	$in['title'] = 'This is the title';
	$in['body'] = 'This is the body';
	$in['entry_date'] = date('U');

	return $options['fn']($in);
},
            'iif' => function($value1,$op,$value2,$options) {
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
},
            'is_even' => function($value,$options) {
	/* parse the "then" (fn) or the "else" (inverse) */
	$return = '';

	if (!($value % 2)) {
		$return = $options['fn']($options['_this']);
	} elseif ($options['inverse'] instanceof \Closure) {
		$return = $options['inverse']($options['_this']);
	}

	return $return;
},
            'is_odd' => function($value,$options) {
	/* parse the "then" (fn) or the "else" (inverse) */
	$return = '';

	if ($value % 2) {
		$return = $options['fn']($options['_this']);
	} elseif ($options['inverse'] instanceof \Closure) {
		$return = $options['inverse']($options['_this']);
	}

	return $return;
},
            'set' => function($options) use (&$in) {
	$in['html_'.$options['hash']['name']] = $options['hash']['value'];

	return '';
},
            'get' => function($options) {
	return $options['_this']['html_'.$options['hash']['name']];
},
            'exp:query' => function($options) {
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
},
            'q:cache_demo' => function($options) {
	/*
	if (!$output = ci()->handlebars->cache($options)) {
		$output = 'Cached on: '.date('Y-m-d H:i:s').'  until '.date('Y-m-d H:i:s',strtotime('+'.(int)$options['hash']['cache'].' minutes'));

		ci()->handlebars->cache($options,$output);
	}
	*/

	return $output;
},
            'format:date' => function($arg1,$options) {
	return date($options['hash']['format'],$arg1);
},
);
    $partials = array();
    $cx = array(
        'flags' => array(
            'jstrue' => true,
            'jsobj' => true,
            'jslen' => true,
            'spvar' => true,
            'prop' => false,
            'method' => false,
            'lambda' => false,
            'mustlok' => false,
            'mustlam' => false,
            'mustsec' => false,
            'echo' => true,
            'partnc' => false,
            'knohlp' => false,
            'debug' => isset($options['debug']) ? $options['debug'] : 1,
        ),
        'constants' =>  array(
            'DEBUG_ERROR_LOG' => 1,
            'DEBUG_ERROR_EXCEPTION' => 2,
            'DEBUG_TAGS' => 4,
            'DEBUG_TAGS_ANSI' => 12,
            'DEBUG_TAGS_HTML' => 20,
        ),
        'helpers' => isset($options['helpers']) ? array_merge($helpers, $options['helpers']) : $helpers,
        'partials' => isset($options['partials']) ? array_merge($partials, $options['partials']) : $partials,
        'scopes' => array(),
        'sp_vars' => isset($options['data']) ? array_merge(array('root' => $in), $options['data']) : array('root' => $in),
        'blparam' => array(),
        'partialid' => 0,
        'runtime' => '\LightnCandy\Runtime',
    );
    /* template-handlebars-demo-template compiled @ 2018-02-01 12:50:29 America/New_York */
    $inary=is_array($in);
    ob_start();echo '','<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Example</title>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">		
		<link href="/assets/site.css" rel="stylesheet">
	</head>
	<body>
      <div class="container">
				<h4>In Header page_title = ',lcr5a735365068b1encq($cx, (($inary && isset($in['page_title'])) ? $in['page_title'] : null)),'</h4>
','
<h4>page_title = ',lcr5a735365068b1encq($cx, (($inary && isset($in['page_title'])) ? $in['page_title'] : null)),'</h4>

',lcr5a735365068b1encq($cx, lcr5a735365068b1hbch($cx, 'q:cache_demo', array(array(),array('cache'=>'5')), 'encq', $in)),'

',lcr5a735365068b1encq($cx, lcr5a735365068b1hbch($cx, 'set', array(array(),array('name'=>'age','value'=>(($inary && isset($in['page_title'])) ? $in['page_title'] : null))), 'encq', $in)),'
',lcr5a735365068b1encq($cx, lcr5a735365068b1hbch($cx, 'set', array(array(),array('name'=>'foo','value'=>'bar')), 'encq', $in)),'

',lcr5a735365068b1sec($cx, (($inary && isset($in['projects'])) ? $in['projects'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);echo '	<h3>',lcr5a735365068b1encq($cx, (($inary && isset($in['name'])) ? $in['name'] : null)),'</h3>
	<h4>Assignees</h4>
	<ul>
',lcr5a735365068b1sec($cx, (($inary && isset($in['assignees'])) ? $in['assignees'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);echo '		<li>',lcr5a735365068b1encq($cx, (($inary && isset($in['name'])) ? $in['name'] : null)),' ',lcr5a735365068b1encq($cx, (($inary && isset($in['age'])) ? $in['age'] : null)),'</li>
';}),'	</ul>
';}),'
',lcr5a735365068b1hbbch($cx, 'iif', array(array((($inary && isset($in['page_title'])) ? $in['page_title'] : null),'=','Current Projects'),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '	True Do This
';}, function($cx, $in) {$inary=is_array($in);echo '	False Do This
';}),'
<p>
',lcr5a735365068b1hbbch($cx, 'iif', array(array(1,'=',1),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '	1 equals 1 with else
';}, function($cx, $in) {$inary=is_array($in);echo '	else 1 equals 1
';}),'</p>

<p>
',lcr5a735365068b1hbbch($cx, 'iif', array(array(1,'=',2),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '	1 equals 1
';}, function($cx, $in) {$inary=is_array($in);echo '	else 1 equals 1
';}),'</p>

<p>
',lcr5a735365068b1hbbch($cx, 'iif', array(array(1,'=',1),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '	single 1 equals 1
';}),'</p>

<p>
',lcr5a735365068b1hbbch($cx, 'iif', array(array(1,'!=',2),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '	1 does not equal 1
';}),'</p>

<p>
',lcr5a735365068b1hbbch($cx, 'is_even', array(array(2),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '	<b>I\'m even!</b>
';}),'<p>

<p>
',lcr5a735365068b1hbbch($cx, 'is_odd', array(array(1),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '	<b>I\'m a Odd Ball!</b>
';}),'<p>

<p>
',lcr5a735365068b1hbbch($cx, 'is_odd', array(array(2),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '	<b>I\'m a Odd Ball!</b>
';}, function($cx, $in) {$inary=is_array($in);echo '	<i>I\'m Even Steven!</i>
';}),'<p>

<p>
',lcr5a735365068b1hbbch($cx, 'exp:channel:entries', array(array(),array('channel'=>'news','limit'=>'15','category'=>'2','entry_id'=>'147')), $in, false, function($cx, $in) {$inary=is_array($in);echo '
<h3>The title is "',lcr5a735365068b1encq($cx, (($inary && isset($in['title'])) ? $in['title'] : null)),'"</h3>

<p>The body is "',lcr5a735365068b1encq($cx, (($inary && isset($in['body'])) ? $in['body'] : null)),'"</p>

<p><strong>get name="age" ',lcr5a735365068b1encq($cx, lcr5a735365068b1hbch($cx, 'get', array(array(),array('name'=>'age')), 'encq', $in)),'</strong></p>
<p><strong>get name="foo" ',lcr5a735365068b1encq($cx, lcr5a735365068b1hbch($cx, 'get', array(array(),array('name'=>'foo')), 'encq', $in)),'</strong></p>

<div class="date">Posted on ',lcr5a735365068b1encq($cx, lcr5a735365068b1hbch($cx, 'format:date', array(array((($inary && isset($in['entry_date'])) ? $in['entry_date'] : null)),array('format'=>'Y-m-d H:i:s')), 'encq', $in)),'</div>

';}),'</p>

<p>
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
',lcr5a735365068b1hbbch($cx, 'exp:query', array(array(),array('sql'=>'select * from orange_settings')), $in, false, function($cx, $in) {$inary=is_array($in);echo '		<tr class="';if (lcr5a735365068b1ifvar($cx, (($inary && isset($in['query_odd'])) ? $in['query_odd'] : null), false)){echo 'oddman';}else{echo 'evenman';}echo '">
			<td>',lcr5a735365068b1encq($cx, (($inary && isset($in['query_index_row'])) ? $in['query_index_row'] : null)),'</td>
			<td>',lcr5a735365068b1encq($cx, (($inary && isset($in['name'])) ? $in['name'] : null)),'</td>
			<td>',lcr5a735365068b1encq($cx, (($inary && isset($in['variable'])) ? $in['variable'] : null)),'</td>
			<td>',lcr5a735365068b1encq($cx, (($inary && isset($in['value'])) ? $in['value'] : null)),'</td>
			<td>',lcr5a735365068b1hbbch($cx, 'exp:uppercase', array(array(),array('cache'=>'60')), $in, false, function($cx, $in) {$inary=is_array($in);echo '';if (lcr5a735365068b1ifvar($cx, (($inary && isset($in['query_odd'])) ? $in['query_odd'] : null), false)){echo 'odd';}else{echo 'even';}echo '';}),'</td>
			<td>',lcr5a735365068b1encq($cx, (($inary && isset($in['query_first_row'])) ? $in['query_first_row'] : null)),'</td>
			<td>',lcr5a735365068b1encq($cx, (($inary && isset($in['query_last_row'])) ? $in['query_last_row'] : null)),'</td>
			<td><a href="/admin/content/snippets/edit/',lcr5a735365068b1encq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'">Edit</a></td>
		</tr>
';}),'	</table>
</p>

<p>',lcr5a735365068b1hbbch($cx, 'exp:lowercase', array(array(),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo 'UPPERCASE <- NOT! ';}),'</p>

<p>',lcr5a735365068b1hbbch($cx, 'exp:uppercase', array(array(),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '',lcr5a735365068b1encq($cx, (($inary && isset($in['page_title'])) ? $in['page_title'] : null)),'';}),'</p>

','','<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Example</title>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">		
		<link href="/assets/site.css" rel="stylesheet">
	</head>
	<body>
      <div class="container">
				<h4>In Header page_title = ',lcr5a735365068b1encq($cx, (($inary && isset($in['page_title'])) ? $in['page_title'] : null)),'</h4>
','
<h2>tester template file</h2>

',lcr5a735365068b1sec($cx, (($inary && isset($in['projects'])) ? $in['projects'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);echo '	<h3>',lcr5a735365068b1encq($cx, (($inary && isset($in['name'])) ? $in['name'] : null)),'</h3>
	<h4>Assignees</h4>
	<ul>
',lcr5a735365068b1sec($cx, (($inary && isset($in['assignees'])) ? $in['assignees'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);echo '		<li>',lcr5a735365068b1encq($cx, (($inary && isset($in['name'])) ? $in['name'] : null)),' ',lcr5a735365068b1encq($cx, (($inary && isset($in['age'])) ? $in['age'] : null)),'</li>
';}),'	</ul>
';}),'
','			<h4>In Footer page_title = ',lcr5a735365068b1encq($cx, (($inary && isset($in['page_title'])) ? $in['page_title'] : null)),'</h4>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
		<script src="/assets/site.js"></script>
	</body>
</html>','','
','			<h4>In Footer page_title = ',lcr5a735365068b1encq($cx, (($inary && isset($in['page_title'])) ? $in['page_title'] : null)),'</h4>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
		<script src="/assets/site.js"></script>
	</body>
</html>','';return ob_get_clean();
}; ?>