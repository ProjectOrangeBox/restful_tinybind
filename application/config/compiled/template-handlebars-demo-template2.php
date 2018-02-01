<?php  function lcr5a7354d8c5065encq($cx, $var) {
  if ($var instanceof LS) {
   return (string)$var;
  }

  return str_replace(array('=', '`', '&#039;'), array('&#x3D;', '&#x60;', '&#x27;'), htmlspecialchars(lcr5a7354d8c5065raw($cx, $var), ENT_QUOTES, 'UTF-8'));
 }

 function lcr5a7354d8c5065sec($cx, $v, $bp, $in, $each, $cb, $else = null) {
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
     $raw = lcr5a7354d8c5065m($cx, $raw, array($bp[0] => $raw));
    }
    if (isset($bp[1])) {
     $raw = lcr5a7354d8c5065m($cx, $raw, array($bp[1] => $cx['sp_vars']['index']));
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

 function lcr5a7354d8c5065hbch($cx, $ch, $vars, $op, &$_this) {
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

  return lcr5a7354d8c5065exch($cx, $ch, $vars, $options);
 }

 function lcr5a7354d8c5065raw($cx, $v, $ex = 0) {
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
      $ret[] = lcr5a7354d8c5065raw($cx, $vv);
     }
     return join(',', $ret);
    }
   } else {
    return 'Array';
   }
  }

  return "$v";
 }

 function lcr5a7354d8c5065m($cx, $a, $b) {
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

 function lcr5a7354d8c5065exch($cx, $ch, $vars, &$options) {
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
   lcr5a7354d8c5065err($cx, $e);
  }

  return $r;
 }

 function lcr5a7354d8c5065err($cx, $err) {
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
    $helpers = array(            'set' => function($options) use (&$in) {
	$in['html_'.$options['hash']['name']] = $options['hash']['value'];

	return '';
},
            'get' => function($options) {
	return $options['_this']['html_'.$options['hash']['name']];
},
            'snippet' => function($options) {
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
},
            'snippet-a' => function($options) {
	$name = $options['hash']['name'];

	ci()->load->model('c_snippet_model');

	$records = ci()->c_snippet_model->catalog('variable','value');

	$target = (substr($records[$name],0,4) == 'http') ? ' target="_blank"' : '';

	return '<a href="'.$records[$name].'"'.$target.'>';
},
            'snippet-copyright' => function($options) {
	$name = (isset($options['hash']['name'])) ? $options['hash']['name'] : 'copyright';

	ci()->load->model('c_snippet_model');

	$records = ci()->c_snippet_model->catalog('variable','value');

	return str_replace('{year}',date('Y'),$records[$name]);
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
    /* template-handlebars-demo-template2 compiled @ 2018-02-01 12:56:40 America/New_York */
    $inary=is_array($in);
    ob_start();echo '<h1>',lcr5a7354d8c5065encq($cx, (($inary && isset($in['title'])) ? $in['title'] : null)),'</h1>
',lcr5a7354d8c5065sec($cx, (($inary && isset($in['projects'])) ? $in['projects'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);echo '	<h3>',lcr5a7354d8c5065encq($cx, (($inary && isset($in['name'])) ? $in['name'] : null)),'</h3>
	<h4>Assignees</h4>
	<ul>
',lcr5a7354d8c5065sec($cx, (($inary && isset($in['assignees'])) ? $in['assignees'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);echo '		<li>',lcr5a7354d8c5065encq($cx, (($inary && isset($in['name'])) ? $in['name'] : null)),'</li>
';}),'	</ul>
';}),'
',lcr5a7354d8c5065sec($cx, (($inary && isset($in['projects'])) ? $in['projects'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);echo '	<h3>',lcr5a7354d8c5065encq($cx, (($inary && isset($in['name'])) ? $in['name'] : null)),'</h3>
	<h4>Assignees</h4>
	<ul>
',lcr5a7354d8c5065sec($cx, (($inary && isset($in['contributors'])) ? $in['contributors'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);echo '		<li>',lcr5a7354d8c5065encq($cx, (($inary && isset($in['name'])) ? $in['name'] : null)),' ',lcr5a7354d8c5065encq($cx, (($inary && isset($in['age'])) ? $in['age'] : null)),'</li>
';}),'	</ul>
';}),'
<p>setting!</p>
',lcr5a7354d8c5065encq($cx, lcr5a7354d8c5065hbch($cx, 'set', array(array(),array('name'=>'age','value'=>(($inary && isset($in['title'])) ? $in['title'] : null))), 'encq', $in)),'

<p>',lcr5a7354d8c5065encq($cx, lcr5a7354d8c5065hbch($cx, 'get', array(array(),array('name'=>'age')), 'encq', $in)),'</p>

<p>',lcr5a7354d8c5065sec($cx, (($inary && isset($in['uppercase'])) ? $in['uppercase'] : null), null, $in, false, function($cx, $in) {$inary=is_array($in);echo '',lcr5a7354d8c5065encq($cx, (($inary && isset($in['age'])) ? $in['age'] : null)),'';}),'</p>

<p>',lcr5a7354d8c5065sec($cx, (($inary && isset($in['blocker'])) ? $in['blocker'] : null), null, $in, false, function($cx, $in) {$inary=is_array($in);echo 'This is the blocker content';}),'</p>

<p>',lcr5a7354d8c5065encq($cx, (($inary && isset($in['lex_uppercase'])) ? $in['lex_uppercase'] : null)),'</p>

<p>Phone Number: ',lcr5a7354d8c5065encq($cx, lcr5a7354d8c5065hbch($cx, 'snippet', array(array(),array('name'=>'phone_number')), 'encq', $in)),'</p>

<p>',lcr5a7354d8c5065encq($cx, lcr5a7354d8c5065hbch($cx, 'snippet-copyright', array(array(),array()), 'encq', $in)),'</p>
<p>',lcr5a7354d8c5065raw($cx, lcr5a7354d8c5065hbch($cx, 'snippet-a', array(array(),array('name'=>'link')), 'raw', $in)),'link</a></p>



',lcr5a7354d8c5065sec($cx, (($inary && isset($in['projects'])) ? $in['projects'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);echo '	<h3>',lcr5a7354d8c5065encq($cx, (($inary && isset($in['name'])) ? $in['name'] : null)),'</h3>
	<h4>Assignees</h4>
	<ul>
',lcr5a7354d8c5065sec($cx, (($inary && isset($in['assignees'])) ? $in['assignees'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);echo '		<li>',lcr5a7354d8c5065encq($cx, (($inary && isset($in['name'])) ? $in['name'] : null)),' ',lcr5a7354d8c5065encq($cx, (($inary && isset($in['age'])) ? $in['age'] : null)),'</li>
';}),'	</ul>
';}),'
<p>setting!</p>
',lcr5a7354d8c5065encq($cx, lcr5a7354d8c5065hbch($cx, 'set', array(array(),array('name'=>'age','value'=>(($inary && isset($in['page_title'])) ? $in['page_title'] : null))), 'encq', $in)),'

<p>',lcr5a7354d8c5065encq($cx, lcr5a7354d8c5065hbch($cx, 'get', array(array(),array('name'=>'age')), 'encq', $in)),'</p>


<p>',lcr5a7354d8c5065sec($cx, (($inary && isset($in['blocker'])) ? $in['blocker'] : null), null, $in, false, function($cx, $in) {$inary=is_array($in);echo 'This is the blocker content';}),'</p>


<p>Phone Number: [',lcr5a7354d8c5065encq($cx, lcr5a7354d8c5065hbch($cx, 'snippet', array(array(),array('name'=>'phone_number')), 'encq', $in)),']</p>
';return ob_get_clean();
}; ?>