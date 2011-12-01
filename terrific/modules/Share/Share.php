<?php

/**
 * Makes a custom Widget for displaying the Social share buttons.
 *
 * @package WordPress
 * @subpackage Terrific
 * @since Terrific 1.0
 */
class Terrific_Module_Share extends Terrific_Module {
	
	function __construct() {
		parent::__construct('Share', array());
	}

	function widget($args, $instance) {
		$data = array();
		$data['via'] = '';
		$this->display($instance, $data);
	}

}

?>