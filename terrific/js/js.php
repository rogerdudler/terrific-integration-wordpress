<?php

/**
 * Terrific Javascript Merger.
 *
 * Include all javascripts in the following order
 *  - base files
 *  - plugins
 *  - elements
 *  - layouts
 *  - modules
 *
 * @since Terrific 1.0
 */

define('TERRIFIC_DIR', dirname(__FILE__) . '/..');

//$_REQUEST['flush'] = 1;

$cache_dir = TERRIFIC_DIR . '/../cache/';
$version = isset($_REQUEST['ver']) ? $_REQUEST['ver'] : '1.0.0';
$file = $cache_dir . 'terrific-' . $version . '.js';
if (is_file($file) && !isset($_REQUEST['flush'])) {
	$last_modified_time = filemtime($file); 
	$etag = md5_file($file); 
	header("Last-Modified: ".gmdate("D, d M Y H:i:s", $last_modified_time)." GMT"); 
	header("Etag: $etag"); 
	if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $last_modified_time || 
	    @trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag) {
	    header("HTTP/1.1 304 Not Modified"); 
	    exit; 
	}
	header("Content-Type: text/javascript; charset=utf-8");
	echo file_get_contents($file);
	exit();
}

// load terrificjs
$output = '';
$output .= file_get_contents(TERRIFIC_DIR . '/js/core/jquery-1.6.2.min.js');
$output .= file_get_contents(TERRIFIC_DIR . '/js/core/terrific-1.0.0.min.js');

// load libraries
foreach (glob(TERRIFIC_DIR . '/js/libraries/static/*.js') as $entry) {
	if (is_file($entry)) {
		$output .= file_get_contents($entry);
	}
}

// load plugins
foreach (glob(TERRIFIC_DIR . '/js/plugins/static/*.js') as $entry) {
	if (is_file($entry)) {
		$output .= file_get_contents($entry);
	}
}

// load connectors
foreach (glob(TERRIFIC_DIR . '/js/connectors/static/*.js') as $entry) {
	if (is_file($entry)) {
		$output .= file_get_contents($entry);
	}
}

// load modules
foreach (glob(TERRIFIC_DIR . '/modules/*', GLOB_ONLYDIR) as $dir) {
	$module = basename($dir);
	$js = $dir . '/js/Tc.Module.' . $module . '.js';
	if (is_file($js)) {
		$output .= file_get_contents($js);
	}
	foreach (glob($dir . '/js/skin/*.js') as $entry) {
		if (is_file($entry)) {
			$output .= file_get_contents($entry);
		}
	}
}

require_once '../min/jsmin-1.1.1.php';
$output = JSMin::minify($output);
file_put_contents($cache_dir . 'terrific-' . $version . '.js', $output);
header("Content-Type: text/javascript; charset=utf-8");
echo $output;

?>
