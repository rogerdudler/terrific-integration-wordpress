<?php

/**
 * Makes a custom Widget for displaying information about a post author.
 *
 * @package WordPress
 * @subpackage Terrific
 * @since Terrific 1.0
 */
class Terrific_Module_AuthorInfo extends Terrific_Module {
	
	function __construct() {
		parent::__construct('AuthorInfo', array());
	}
	
	function widget($args, $instance) {
		$this->display($instance, $data);
	}

}

?>