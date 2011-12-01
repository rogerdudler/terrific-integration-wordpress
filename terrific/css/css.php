<?php

/**
 * Terrific Stylesheet Merger.
 *
 * Include all stylesheets in the following order
 *	- base files
 *	- elements
 *	- layout
 *	- modules
 *
 * @since Terrific 1.0
 */

define('TERRIFIC_DIR', dirname(__FILE__) . '/..');

//$_REQUEST['flush'] = 1;

$cache_dir = TERRIFIC_DIR . '/../cache/';
$version = isset($_REQUEST['ver']) ? $_REQUEST['ver'] : '1.0.0';
$file = $cache_dir . 'terrific-' . $version . '.css';
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
	header("Content-Type: text/css");
	echo file_get_contents($file);
	exit();
}

$output = '';

// load elements css
$output .= file_get_contents(TERRIFIC_DIR . '/elements/css/elements.css');

// load layout css including skins
$output .= file_get_contents(TERRIFIC_DIR . '/layout/css/default.css');
foreach (glob(TERRIFIC_DIR . '/layout/css/skin/*') as $entry) {
	if (is_file($entry)) {
		$output .= file_get_contents($entry);
	}
}

// load libraries css
foreach (glob(TERRIFIC_DIR . '/css/libraries/*.css') as $entry) {
	if (is_file($entry)) {
		$output .= file_get_contents($entry);
	}
}

// load plugin css
foreach (glob(TERRIFIC_DIR . '/css/plugins/*.css') as $entry) {
	if (is_file($entry)) {
		$output .= file_get_contents($entry);
	}
}

// load module css including skins
foreach (glob(TERRIFIC_DIR . '/modules/*', GLOB_ONLYDIR) as $dir) {
	$module = basename($dir);
	$css = $dir . '/css/' . strtolower($module) . '.css';
	if (is_file($css)) {
		$output .= file_get_contents($css);
	}
	foreach (glob($dir . '/css/skin/*') as $entry) {
		if (is_file($entry)) {
			$output .= file_get_contents($entry);
		}
	}
}

require_once '../min/cssmin-v1.0.1.b3.php';
$output = cssmin::minify($output);
file_put_contents($cache_dir . 'terrific-' . $version . '.css', $output);
header("Content-Type: text/css");
echo $output;

?>
