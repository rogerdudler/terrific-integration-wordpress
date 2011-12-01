<?php

/**
 * The template for displaying the index page.
 *
 * @package WordPress
 * @subpackage Terrific
 * @since Terrific 1.0
 */

get_header(); ?>
<div class="content" role="main">
 <div class="content-inner">
 <?php while (have_posts()) : the_post(); ?>
 <?php terrific_module('Content', array('template' => get_post_format())) ?>
 <?php endwhile; ?>
 <?php terrific_module('ContentNavigation', array('nav_id' => 'nav-below')); ?>
 </div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>