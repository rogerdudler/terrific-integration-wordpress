<?php

/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Terrific
 * @since Terrific 1.0
 */

get_header(); ?>
<div class="content" role="main">
<div class="content-inner">
 <?php the_post(); ?>
 <?php terrific_module('Content', array('template' => 'page')) ?>
 <?php comments_template() ?>
</div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>