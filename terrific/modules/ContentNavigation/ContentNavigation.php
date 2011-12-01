<?php

/**
 * Makes a custom Widget for displaying post navigation
 *
 * @package WordPress
 * @subpackage Terrific
 * @since Terrific 1.0
 */
class Terrific_Module_ContentNavigation extends Terrific_Module {
	
	function __construct() {
		$widget_ops = array(
			'classname' => 'Widget_Terrific_ContentNavigation', 
			'description' => 'Displays post navigation'
		);
		parent::__construct('ContentNavigation', $widget_ops);
	}

	function widget($args, $instance) {
		$data['nav_id'] = $instance['nav_id'];
		$this->display($instance, $data);
	}

}

?>