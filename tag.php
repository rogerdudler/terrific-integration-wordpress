<?php

/**
 * The template used to display Tag Archive pages
 *
 * @package WordPress
 * @subpackage Terrific
 * @since Terrific 1.0
 */

get_header(); ?>
<div class="content" role="main">
<div class="content-inner">
 <?php if (have_posts()) : ?>
 <header class="page-header">
  <h1 class="page-title"><?php
  printf(__( 'Tag Archives: %s', 'terrific' ), '<span>' . single_tag_title('', false ) . '</span>');
  ?></h1>
  <?php
  $tag_description = tag_description();
  if (!empty( $tag_description))
      echo apply_filters( 'tag_archive_meta', '<div class="tag-archive-meta">' . $tag_description . '</div>');
  ?>
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
