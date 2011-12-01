<?php

/**
 * The template for displaying Author Archive pages.
 *
 * @package WordPress
 * @subpackage Terrific
 * @since Terrific 1.0
 */

get_header(); ?>
<div class="content" role="main">
 <div class="content-inner">
 <?php if ( have_posts() ) : ?>
 <?php the_post(); ?>
 <header class="page-header">
  <h1 class="page-title author"><?php printf( __( 'Author Archives: %s', 'terrific' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' ); ?></h1>
 </header>
 <?php rewind_posts(); ?>
 <?php terrific_module('AuthorInfo') ?>
 <?php while ( have_posts() ) : the_post(); ?>
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