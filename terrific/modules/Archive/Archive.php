<?php

/**
 * Makes a custom Widget for displaying the archives.
 *
 * @package WordPress
 * @subpackage Terrific
 * @since Terrific 1.0
 */
class Terrific_Module_Archive extends Terrific_Module {
	
	function __construct() {
		$widget_ops = array( 
			'description' => 'A monthly archive of your site&#8217;s posts'
		);
		parent::__construct('Archive', $widget_ops);
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'count' => 0, 'dropdown' => '') );
		$title = strip_tags($instance['title']);
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
		<?php
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '', 'count' => 0, 'dropdown' => '') );
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['count'] = $new_instance['count'] ? 1 : 0;
		$instance['dropdown'] = $new_instance['dropdown'] ? 1 : 0;
		return $instance;
	}

	function widget($args, $instance) {
		$data['title'] = apply_filters('widget_title', empty($instance['title']) ? __('Archives') : $instance['title'], $instance, $this->id_base);
		$data['archives'] = wp_get_archives(
			apply_filters('widget_terrific_archive_args', 
				array(
					'type' => 'yearly', 
					'show_post_count' => 1,
					'echo' => 0,
				)
			)
		);
		$this->display($instance, $data);
	}

}

?>