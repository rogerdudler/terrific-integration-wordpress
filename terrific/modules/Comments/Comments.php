<?php

/**
 * Makes a custom Widget for displaying comments.
 *
 * @package WordPress
 * @subpackage Terrific
 * @since Terrific 1.0
 */
class Terrific_Module_Comments extends Terrific_Module {
	
	function __construct() {
		$widget_ops = array(
			'description' => 'Displays Comments (e.g. in Posts)'
		);
		parent::__construct('Comments', $widget_ops);
	}

	function widget($args, $instance) {
		$data = array();
		$this->display($instance, $data);
	}

}

?>