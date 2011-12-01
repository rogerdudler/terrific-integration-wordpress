<?php

/**
 * Makes a custom Widget for displaying a logo.
 *
 * @package WordPress
 * @subpackage Terrific
 * @since Terrific 1.0
 */
class Terrific_Module_Logo extends Terrific_Module {
	
	function __construct() {
		parent::__construct('Logo', array());
	}

	function widget($args, $instance) {
		$this->display($instance, $data);
	}

}

?>