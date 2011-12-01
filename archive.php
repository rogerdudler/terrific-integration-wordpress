<?php

/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Terrific
 * @since Terrific 1.0
 */

get_header(); ?>
<div class="content" role="main">
 <div class="content-inner">
 <?php if ( have_posts() ) : ?>
 <header class="page-header">
  <h1 class="page-title">
  <?php if (is_day()) : ?>
      <?php printf( __( 'Daily Archives: %s', 'terrific' ), '<span>' . get_the_date() . '</span>' ); ?>
  <?php elseif ( is_month() ) : ?>
      <?php printf( __( 'Monthly Archives: %s', 'terrific' ), '<span>' . get_the_date( 'F Y' ) . '</span>' ); ?>
  <?php elseif ( is_year() ) : ?>
      <?php printf( __( 'Yearly Archives: %s', 'terrific' ), '<span>' . get_the_date( 'Y' ) . '</span>' ); ?>
  <?php else : ?>
      <?php _e( 'Blog Archives', 'terrific' ); ?>
  <?php endif; ?>
  </h1>
 </header>
 <?php while (have_posts()) : the_post(); ?>
     <?php terrific_module('Content', array('template' => get_post_format())) ?>
 <?php endwhile; ?>
     <?php terrific_module('ContentNavigation', array('nav_id' => 'nav-below')); ?>
 <?php else : ?>
 <article id="post-0" class="post no-results not-found">
  <header class="entry-header">
   <h1 class="entry-title"><?php _e( 'Nothing Found', 'terrific' ); ?></h1>
  </header><!-- .entry-header -->
  <div class="entry-content">
   <p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'terrific' ); ?></p>
   <?php get_search_form(); ?>
  </div><!-- .entry-content -->
 </article><!-- #post-0 -->
 <?php endif; ?>
 </div>
</div><!-- .content -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>