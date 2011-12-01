<?php

/**
 * Search widget class
 *
 * @package WordPress
 * @subpackage Terrific
 * @since Terrific 1.0
 */
class Terrific_Module_Search extends Terrific_Module {

	function __construct() {
		$widget_ops = array(
			'description' => __( "A search form for your site")
		);
		parent::__construct('Search', $widget_ops);
	}

	function widget( $args, $instance ) {
		$title = isset($instance['title']) ? $instance['title'] : 'Search';
		$data['title'] = apply_filters('widget_title', $title, $instance, $this->id_base);
		$this->display($instance, $data);
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = $instance['title'];
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args((array) $new_instance, array( 'title' => ''));
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}

}