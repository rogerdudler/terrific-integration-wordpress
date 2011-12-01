<?php

/**
 * Recent_Comments widget class
 *
 * @package WordPress
 * @subpackage Terrific
 * @since Terrific 1.0
 */
class Terrific_Module_RecentComments extends Terrific_Module {

	function __construct() {
		$widget_ops = array(
			'description' => __('The most recent comments')
		);
		parent::__construct('RecentComments', $widget_ops);
		add_action('comment_post', array(&$this, 'flush_widget_cache'));
		add_action('transition_comment_status', array(&$this, 'flush_widget_cache'));
	}

	function flush_widget_cache() {
		wp_cache_delete('widget_terrific_recent_comments', 'widget');
	}

	function widget( $args, $instance ) {
		global $comments, $comment;

		$cache = wp_cache_get('widget_terrific_recent_comments', 'widget');

		if ( ! is_array( $cache ) )
			$cache = array();

		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
 		extract($args, EXTR_SKIP);

 		$data['title'] = apply_filters('widget_title', empty($instance['title']) ? __('Recent Comments') : $instance['title']);

		if ( ! $number = absint( $instance['number'] ) )
 			$number = 5;

		$data['comments'] = get_comments(array('number' => $number, 'status' => 'approve', 'post_status' => 'publish'));
		$data['feed'] = get_bloginfo('comments_rss2_url');
		
		$this->display($instance, $data);
		
		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('widget_terrific_recent_comments', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = absint( $new_instance['number'] );
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_terrific_recent_comments']) )
			delete_option('widget_terrific_recent_comments');

		return $instance;
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 5;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of comments to show:'); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
<?php
	}
}