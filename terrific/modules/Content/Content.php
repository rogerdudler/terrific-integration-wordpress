<?php

/**
 * Makes a custom Widget for displaying a post.
 *
 * @package WordPress
 * @subpackage Terrific
 * @since Terrific 1.0
 */
class Terrific_Module_Content extends Terrific_Module {
	
	function __construct() {
		$widget_ops = array(
			'classname' => 'Widget_Terrific_Content', 
			'description' => 'Displays content (e.g. Posts)'
		);
		parent::__construct('Content', $widget_ops);
	}

	function widget($args, $instance) {
		$instance['template'] = strlen($instance['template']) > 0 ? 'content-' . $instance['template'] : 'content';
		$this->display($instance, array());
	}

}

?>