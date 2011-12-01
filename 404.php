<?php

/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage Terrific
 * @since Terrific 1.0
 */

get_header(); ?>
<div class="content" role="main">
<div class="content-inner">
 <h1 class="base"><?php _e('Page not found') ?></h1>
 <p class="base"><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching, or one of the links below, can help.', 'terrific' ); ?></p>
 <br />
 <?php get_search_form(); ?>
 <br />
 <?php the_widget('WP_Widget_Tag_Cloud'); ?>
</div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>