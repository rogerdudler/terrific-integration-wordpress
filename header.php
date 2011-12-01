<?php

/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Terrific
 * @since Terrific 1.0
 */

?>
<?php get_header('empty'); ?>
<div class="page-container">
 <div class="head">
	<div class="left"><?php terrific_module('Logo') ?></div>
	<div class="right">
		<div class="inner">
	    <?php terrific_module('Search', array('template' => 'head', 'skin' => 'Head')) ?>
		</div>
	</div>
 </div>
 <div class="body">