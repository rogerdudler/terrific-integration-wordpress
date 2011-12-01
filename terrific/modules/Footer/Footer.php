<?php

/**
 * Makes a custom Widget for displaying the Footer.
 *
 * @package WordPress
 * @subpackage Terrific
 * @since Terrific 1.0
 */
class Terrific_Module_Footer extends Terrific_Module {
	
	function __construct() {
		parent::__construct('Footer', array());
	}

	function widget($args, $instance) {
		$data = array();
		$this->display($instance, $data);
	}

}

?>