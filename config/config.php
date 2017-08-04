<?php
define('WB_KEY', '2321227511');
define('WB_SEC', '24ca07c8fd50f74f194d6059d968c8fb');
define('CALLBACK', 'http://blog.lclclouds.com/index.php?c=index&a=callback');

function debug($var, $dump = false, $exit = true)
{
	if ($dump) {
		$func = 'var_dump';
	} else{
		$func = (is_array($var) || is_object($var)) ? 'print_r' : 'print_f';
	}
	header("Content-type: text/html; charset=utf-8");
	echo '<pre>debug output:<hr />';
	$func($var);
	echo '</pre>';
	if ($exit) exit;
}