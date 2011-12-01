<?php

/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Terrific
 * @since Terrific 1.0
 */

get_header(); ?>
<div class="content" role="main">
<div class="content-inner">
 <?php while (have_posts()) : the_post(); ?>
 <?php terrific_module('Content', array('template' => 'single')) ?>
 <?php comments_template() ?>
 <?php endwhile; ?>
</div>
</div><!-- .content -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>