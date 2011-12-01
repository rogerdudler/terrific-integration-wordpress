<?php

/**
 * Example module.
 *
 * @package WordPress
 * @subpackage Terrific
 * @since Terrific 1.0
 */
class Terrific_Module_Example extends Terrific_Module {
	
	function __construct() {
		parent::__construct('Example', array());
	}

	function widget($args, $instance) {
		$data = array();
		$this->display($instance, $data);
	}

}

?>