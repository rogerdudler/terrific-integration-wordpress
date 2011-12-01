<?php

/**
 * Makes a custom Widget for displaying a feed.
 *
 * @package WordPress
 * @subpackage Terrific
 * @since Terrific 1.0
 */
class Terrific_Module_Feed extends Terrific_Module {
	
	function __construct() {
		parent::__construct('Feed', array());
	}
	
	

	function widget($args, $instance) {
		
		$data = array();
		$data['title'] = apply_filters('widget_title', empty($instance['title'] ) ? __('Feed') : $instance['title'], $instance, $this->id_base);
		
		// fetch rss/atom feed
		$rss = $instance['feed'];
		$rss = fetch_feed($rss);
		
		// check for errors
		if (!$rss->get_item_quantity()) {
			echo '<ul><li>' . __( 'An error has occurred; the feed is probably down. Try again later.' ) . '</li></ul>';
			$rss->__destruct();
			unset($rss);
			return;
		}
		$item_count = 5;
        $data['feed'] = $instance['feed'];
		$data['items'] = array();
		foreach ($rss->get_items(0, $item_count) as $item) {
			$link = $item->get_link();
			while ( stristr($link, 'http') != $link )
				$link = substr($link, 1);
			$link = esc_url(strip_tags($link));
			$title = esc_attr(strip_tags($item->get_title()));
			$desc = str_replace( array("\n", "\r"), ' ', esc_attr( strip_tags( @html_entity_decode( $item->get_description(), ENT_QUOTES, get_option('blog_charset') ) ) ) );
			$desc = wp_html_excerpt( $desc, 360 );
			$desc = esc_html($desc);
			$date = $item->get_date('U');
			$author = $item->get_author();
			if (is_object($author)) {
				$author = $author->get_name();
				$author = esc_html(strip_tags( $author ));
			} else {
				$author = null;
			}
			$data['items'][] = array(
				'title' => $title,
				'link' => $link,
				'description' => $desc,
				'date' => date_i18n(get_option('date_format'), $date),
				'author' => $author
			);
		}
		$rss->__destruct();
		unset($rss);
		
		$this->display($instance, $data);
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['feed'] = strip_tags($new_instance['feed']);
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args((array) $instance, array('title' => '', 'feed' => ''));
		$title = esc_attr($instance['title']); 
		$feed = esc_attr($instance['feed']);
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('feed'); ?>"><?php _e( 'Feed:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('feed'); ?>" name="<?php echo $this->get_field_name('feed'); ?>" type="text" value="<?php echo $feed; ?>" />
		</p>
		<?php
	}

}

?>