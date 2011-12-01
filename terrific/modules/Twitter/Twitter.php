<?php

/**
 * Makes a custom Widget for displaying tweets.
 * 
 * http://api.twitter.com/1/statuses/user_timeline.json?screen_name=rogerdudler
 *
 * @author Roger Dudler
 * @package WordPress
 * @subpackage Terrific
 * @since Terrific 1.0
 */
class Terrific_Module_Twitter extends Terrific_Module {
	
	function __construct() {
		parent::__construct('Twitter', array());
		$this->ajax('tweets');
	}

	/**
	 * Loads a bunch of tweets via Twitter API. (only for AJAX use)
	 */
	function tweets($function) {
		$data = self::_loadTweets($_REQUEST['screen_name'], $_REQUEST['count']);
		header('Content-Type: application/json');
		echo json_encode($data);
		exit();
	}
	
	private static function _loadTweets($screen_name, $count) {
		$url = 'http://api.twitter.com/1/statuses/user_timeline.json?screen_name=' . $screen_name . '&include_entities=true&exclude_replies=true&count=' . $count;
		$hash = md5($url);
		if (false === ($data = get_transient($hash))) {
			$data = file_get_contents($url);
			set_transient($hash, $data, 1800); // cache entries for 30 minutes
		}
		$data = json_decode($data);
		return $data;
	}

	function widget($args, $instance) {
		$data = array();
		$data['title'] = $instance['title'];
		$data['screen_name'] = $instance['screen_name'];
		$data['count'] = intval($instance['count']);
		$data['skin'] = $instance['skin'];
		$data['tweets'] = self::_loadTweets($data['screen_name'], $data['count']);
		foreach ($data['tweets'] as $key => $tweet) {
			$search = array();
			$replace = array();
			if (property_exists($tweet->entities, 'urls')) {
				foreach ($tweet->entities->urls as $entity) {
					$search[] = $entity->url;
					$replace[] = '<a href="' . $entity->url . '">' . $entity->url . '</a>';
				}
			}
			if (property_exists($tweet->entities, 'hashtags')) {
				foreach ($tweet->entities->hashtags as $entity) {
			        $search[] = '#' . $entity->text;
			        $replace[] = '<a href="http://twitter.com/#!/search?q=%23' . $entity->text . '">#' . $entity->text . '</a>';
				}
			}
			if (property_exists($tweet->entities, 'user_mentions')) {
				foreach ($tweet->entities->user_mentions as $entity) {
					$search[] = '@' . $entity->screen_name;
					$replace[] = '<a href="http://twitter.com/' . $entity->screen_name . '">@' . $entity->screen_name . '</a>';
				}
			}
			$data['tweets'][$key]->text = str_ireplace($search, $replace, $tweet->text);
		}
		$this->display($instance, $data);
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['skin'] = strip_tags($new_instance['skin']);
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['count'] = strip_tags($new_instance['count']);
		$instance['screen_name'] = strip_tags($new_instance['screen_name']);
		return $instance;
	}
	
	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array('title' => 'Tweets', 'count' => 5, 'screen_name' => 'wordpress') );
		$title = strip_tags($instance['title']);
		$skin = strip_tags($instance['skin']);
		$count = strip_tags($instance['count']);
		$screen_name = strip_tags($instance['screen_name']);
?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('screen_name'); ?>"><?php _e('Screen Name:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('screen_name'); ?>" name="<?php echo $this->get_field_name('screen_name'); ?>" type="text" value="<?php echo esc_attr($screen_name); ?>" />
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

}

?>