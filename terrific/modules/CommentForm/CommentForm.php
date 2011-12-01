<?php

/**
 * Makes a custom Widget for displaying the comment form.
 *
 * @package WordPress
 * @subpackage Terrific
 * @since Terrific 1.0
 */
class Terrific_Module_CommentForm extends Terrific_Module {
	
	function __construct() {
		parent::__construct('CommentForm', array());
	}

	function widget($args, $instance) {
	
		global $user_identity, $id;
		$post_id = $instance['post_id'];
		if ( null === $post_id )
			$post_id = $id;
		else
			$id = $post_id;

		$data = array(
			'must_log_in' => sprintf(__('You must be <a href="%s">logged in</a> to post a comment.'), wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id)))),
			'logged_in_as' => sprintf(__('Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink($post_id)))),
		);
		$data['post_id'] = $post_id;
		$data['commenter'] = wp_get_current_commenter();
		$data['req'] = get_option('require_name_email');
		$data['aria_req'] = ($data['req'] ? " aria-required='true'" : '');

		$this->display($instance, $data);
	}

}

?>