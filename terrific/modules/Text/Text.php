<?php

/**
 * Makes a custom Widget for displaying text or html.
 *
 * @package WordPress
 * @subpackage Terrific
 * @since Terrific 1.0
 */
class Terrific_Module_Text extends Terrific_Module {
	
	function __construct() {
		$widget_ops = array('description' => __('Arbitrary text or HTML'));
		$control_ops = array('width' => 400, 'height' => 350);
		parent::__construct('Text', $widget_ops, $control_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$data['title'] = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$text = apply_filters( 'widget_text', $instance['text'], $instance);
		$data['text'] = $instance['filter'] ? wpautop($text) : $text;
		$data['skin'] = isset($instance['skin']) && $instance['skin'] != '' ? $instance['skin'] : null;
		$this->display($instance, $data);
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['skin'] = strip_tags($new_instance['skin']);
		$instance['title'] = strip_tags($new_instance['title']);
		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		else
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
		$instance['filter'] = isset($new_instance['filter']);
		return $instance;
	}
	
	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ) );
		$title = strip_tags($instance['title']);
		$skin = strip_tags($instance['skin']);
		$text = esc_textarea($instance['text']);
?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('skin'); ?>"><?php _e('Skin:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('skin'); ?>" name="<?php echo $this->get_field_name('skin'); ?>" type="text" value="<?php echo esc_attr($skin); ?>" />
		</p>
		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
		<p>
			<input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs'); ?></label>
		</p>
<?php
	}

}

?>