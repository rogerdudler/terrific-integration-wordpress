<?php

/**
 * Makes a custom Widget for displaying a link list.
 *
 * @package WordPress
 * @subpackage Terrific
 * @since Terrific 1.0
 */
class Terrific_Module_Links extends Terrific_Module {
	
	function __construct() {
		$widget_ops = array(
			'description' => 'Your links list'
		);
		parent::__construct('Links', $widget_ops);
	}

	function form($instance) {
		
		$instance = wp_parse_args((array) $instance, array('title' => 'Links', 'images' => true, 'name' => true, 'description' => false, 'rating' => false, 'category' => false));
		$title = strip_tags($instance['title']);
		$category = isset($instance['category']) ? $instance['category'] : false;
		$link_cats = get_terms('link_category');
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
		<p>
		<label for="<?php echo $this->get_field_id('category'); ?>" class="screen-reader-text"><?php _e('Select Link Category'); ?></label>
		<select class="widefat" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>">
		<option value=""><?php _e('All Links'); ?></option>
		<?php
		foreach ($link_cats as $link_cat) {
			echo '<option value="' . intval($link_cat->term_id) . '"'
				. ( $link_cat->term_id == $instance['category'] ? ' selected="selected"' : '' )
				. '>' . $link_cat->name . "</option>\n";
		}
		?>
		</select></p>
		<p>
		<?php
		
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$new_instance = wp_parse_args((array) $new_instance, array('title' => '') );
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['category'] = intval($new_instance['category']);
		return $instance;
	}

	function widget($args, $instance) {
		
		$data['title'] = apply_filters('widget_title', empty($instance['title']) ? __('Links') : $instance['title'], $instance, $this->id_base);
		$category = isset($instance['category']) ? $instance['category'] : false;
		
		// load bookmarks
		$data['links'] = get_bookmarks(array(
			'orderby'        => 'name',
			'order'          => 'ASC',
			'category_name'  => $category
		));
		
		$this->display($instance, $data);
	}

}

?>