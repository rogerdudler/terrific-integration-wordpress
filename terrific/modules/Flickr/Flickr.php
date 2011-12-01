<?php

/**
 * Makes a custom Widget for displaying Flickr images.
 *
 * @package WordPress
 * @subpackage Terrific
 * @since Terrific 1.0
 */
class Terrific_Module_Flickr extends Terrific_Module {
	
	private static $_defaults = array(
		'title' => 'Flickr',
		'url' => 'http://www.flickr.com/photos/tags/nature/',
		'count' => 9,
		'feed' => 'http://api.flickr.com/services/feeds/photos_public.gne?tags=nature&lang=en-us'
	);
	
	function __construct() {
		$widget_ops = array(
			'classname' => 'Terrific_Module_Flickr', 
			'description' => 'Show Flickr images'
		);
		parent::__construct('Flickr', $widget_ops);
		$this->ajax('latest');
	}

	function form($instance) {
		$instance = wp_parse_args((array) $instance, $this->_defaults);
		$title = strip_tags($instance['title']);
		$skin = strip_tags($instance['skin']);
		$count = strip_tags($instance['count']);
		$feed = strip_tags($instance['feed']);
		$url = strip_tags($instance['url']);
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('feed'); ?>"><?php _e('Feed:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('feed'); ?>" name="<?php echo $this->get_field_name('feed'); ?>" type="text" value="<?php echo esc_attr($feed); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('URL:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo esc_attr($url); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Count:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo esc_attr($count); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('skin'); ?>"><?php _e('Skin:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('skin'); ?>" name="<?php echo $this->get_field_name('skin'); ?>" type="text" value="<?php echo esc_attr($skin); ?>" />
		</p>
		<?php
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['skin'] = strip_tags($new_instance['skin']);
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['url'] = strip_tags($new_instance['url']);
		$instance['count'] = strip_tags($new_instance['count']);
		$instance['feed'] = strip_tags($new_instance['feed']);
		return $instance;
	}

	function widget($args, $instance) {
		$data = array();
		$data['title'] = $instance['title'];
		$data['feed'] = $instance['feed'];
		$data['items'] = isset($instance['items']) ? intval($instance['items']) : 9;
		$data['url'] = $instance['url'];
		$data['skin'] = $instance['skin'];

		$feed = self::_loadFeed($data['feed'], $data['items']);
		if (!$feed) {
			return;
		}
		$data['images'] = array();
		foreach ($feed->items as $key => $item) {
			if ($key >= $data['items']) {
				break;
			}
			$item->media->s = str_replace('_m.', '_s.', $item->media->m);
			$data['images'][] = $item;
		}
		$this->display($instance, $data);
	}
	
	private static function _loadFeed($feed, $items) {
		if (trim($feed) == '') {
			return array();
		}
		$feed = $feed . '&format=json&nojsoncallback=1';
		$hash = md5($feed);
		if (false === ($data = get_transient($hash))) {
			$data = file_get_contents($feed);
			$data = str_replace("\\'", '', $data);
			set_transient($hash, $data, 3600); // cache entries for 1 hour
		}
		$data = json_decode($data);
		return $data;
	}
	
	function latest() {
		
		$items = $_REQUEST['count'] > 0 ? $_REQUEST['count'] : self::$_defaults['count'];
		$feed = $_REQUEST['feed'] != '' ? $_REQUEST['feed'] : self::$_defaults['feed'];
		$hash = md5($feed);
		
		// check for cache entries
		if (false === ($data = get_transient($hash))) {
			$feed_data = file_get_contents($feed);
			$xml = simplexml_load_string($feed_data);
			$children = $xml->children();
			$data = array();
			foreach ($children as $child) {
				foreach ($child->item as $item) {
					preg_match_all("/<IMG.+?SRC=[\"']([^\"']+)/si", (string) $item->description, $matches, PREG_SET_ORDER);
					$photo_url = str_replace("_m.jpg", "_s.jpg", $matches[0][1]);
					$data[] = array(
						'link' => (string) $item->link,
						'image' => $photo_url
					);
				}
			}
			$data = json_encode($data);
			set_transient($hash, $data, 3600); // cache entries for 1 hour
		}
		
		// output data as json
		$data = json_decode($data);
		shuffle($data);
		$data = json_encode($data);
		header('Content-Type: application/json');
		echo $data;
		exit();
		
	}

}

?>