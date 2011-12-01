<?php

/**
 * Terrific functions and definitions
 *
 * @package WordPress
 * @subpackage Terrific
 * @since Terrific 1.0
 */

// load libraries
require_once dirname(__FILE__) . '/includes/class-terrific-module.php';
require_once dirname(__FILE__) . '/includes/theme-options.php';

// register filters
add_filter('excerpt_length', 'terrific_excerpt_length');
add_filter('excerpt_more', 'terrific_auto_excerpt_more');
add_filter('get_the_excerpt', 'terrific_custom_excerpt_more');
add_filter('wp_page_menu_args', 'terrific_page_menu_args');
add_filter('image_send_to_editor', 'terrific_image_send_to_editor');
add_filter('pre_get_posts', 'terrific_pre_get_posts');

// register actions
add_action('after_setup_theme', 'terrific_setup');
add_action('widgets_init', 'terrific_widgets_init');
add_action('init', 'terrific_init');
add_action('wp_print_styles', 'terrific_print_styles');
add_action('wp_head', 'terrific_header');
add_action('admin_bar_menu', 'terrific_adminbar_menu', 1000);

// clean wp_head
remove_action('wp_head', 'wp_generator'); // WordPress Version nicht anzeigen
remove_action('wp_head', 'index_rel_link'); // Index Link deaktivieren
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev Link deaktivieren
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start Link deaktivieren
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Und noch mehr davon...
remove_action('wp_head', 'wlwmanifest_link'); // Windows Live Writer Link deaktivieren
remove_action('wp_head', 'rsd_link'); // "Really Simple Discovery"-Service Link deaktivieren
remove_action('wp_head', 'post_comments_feed_link'); // Keinen Extra Feed für einzelne Artikel/deren Kommentare anzeigen

/**
 * Redirects slugs with old separator to new permalinks.
 *
 * @param $query the query object
 * @return void
 * @since 1.0
 */
function terrific_pre_get_posts($query) {
    global $wpdb;
    $name = $query->query_vars['name'];
    if (strpos($name, SLUG_SEPARATOR_OLD) > 0) {
        $name = sanitize_title_for_query($name);
        $new_name = str_replace(SLUG_SEPARATOR_OLD, SLUG_SEPARATOR_NEW, $name);
        $posts = $wpdb->get_results("
            SELECT ID, post_title, post_name
            FROM $wpdb->posts
            WHERE
                post_name = '" . $new_name . "' AND
                post_status = 'publish' AND
                post_type = 'post'
            LIMIT 1
        ");
        foreach ($posts as $post) {
            $permalink = get_permalink($post->ID);
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . $permalink);
            exit();
        }
        wp_reset_query();
    }
}

/**
 * Adds specific classes to an image link. E.g. "fancybox"
 *
 * @since Terrific 1.0
 */
function terrific_image_send_to_editor($html, $id, $caption, $title, $align, $url, $size, $alt = '') {
	if (strpos($html, 'href=') > 0) {
		$html = str_replace('href=', 'class="fancybox" href=', $html);
	}
	return $html;
}

/**
 * Initialize Terrific.
 *
 * @since Terrific 1.0
 */
function terrific_init() {
    if (!is_admin()) {
		wp_deregister_script('l10n');
		wp_deregister_script('terrific');
        wp_register_script('terrific', 
			get_template_directory_uri() . '/terrific/js/js.php',
			array(), 
			'1.0.0',
			true
		);
		wp_enqueue_script('terrific');
		wp_localize_script('terrific', 'Terrific', array('ajaxurl' => admin_url('admin-ajax.php')));
    }

	// allow iframes and stuff
	// credits: http://wordpress.org/support/topic/bypass-sanitize_post-from-wp_insert_post
	kses_remove_filters();
}

/**
 * Load the terrific module class and register as widget.
 * 
 * @since Terrific 1.0
 */
function terrific_load_module($module) {
	$file = dirname(__FILE__) . '/terrific/modules/' . $module . '/' . $module . '.php';
	if (is_file($file)) {
		require_once $file;
		register_widget('Terrific_Module_' . $module);
	}
}

/**
 * Display a terrific module as a WP widget.
 * 
 * @since Terrific 1.0
 */
function terrific_module($module, $instance = array()) {
	return the_widget('Terrific_Module_' . $module, $instance, array());
}

/**
 * Display a terrific module as a static template
 * 
 * @since Terrific 1.0
 */
function terrific_static_module($module, $template = null) {
	if ($template == null) {
		$template = strtolower($module);
	}
	echo '<div class="mod mod' . $module . '">';
	require dirname(__FILE__) . '/terrific/modules/' . $module . '/' . $template . '.phtml';
	echo '</div>';
}

/**
 * Register stylesheets used by Terrific.
 *
 * @since Terrific 1.0
 */
function terrific_print_styles() {
    wp_register_style('terrific', 
		get_template_directory_uri() . '/terrific/css/css.php',
		array(), 
		'1.0.0'
	);
    wp_enqueue_style('terrific');
}

/**
 * Setup the Terrific theme.
 *
 * @since Terrific 1.0
 */
function terrific_setup() {

    load_theme_textdomain('terrific', TEMPLATEPATH . '/languages');

    $locale = get_locale();
    $locale_file = TEMPLATEPATH . "/languages/$locale.php";
    if (is_readable($locale_file))
        require_once($locale_file);

	// Load all terrific modules
	$modules = terrific_get_modules();
	foreach ($modules as $module) {
		terrific_load_module($module);
	}

    // Add default posts and comments RSS feed links to <head>.
    add_theme_support('automatic-feed-links');

    // This theme uses wp_nav_menu() in one location.
    register_nav_menu('primary', __('Primary Menu', 'terrific'));

    // Add support for a variety of post formats
    // add_theme_support('post-formats', array('aside', 'link', 'gallery', 'status', 'quote', 'image' ));

    // This theme uses Featured Images (also known as post thumbnails) for per-post/per-page Custom Header images
    add_theme_support('post-thumbnails');

}

/**
 * Sets the post excerpt length to 40 words.
 *
 * @since Terrific 1.0
 */
function terrific_excerpt_length( $length ) {
    return 40;
}

/**
 * Returns a "Continue Reading" link for excerpts.
 *
 * @since Terrific 1.0
 */
function terrific_continue_reading_link() {
    return ' <a href="'. esc_url( get_permalink() ) . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'terrific' ) . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) 
 * with an ellipsis and terrific_continue_reading_link().
 *
 *  @since Terrific 1.0
 */
function terrific_auto_excerpt_more( $more ) {
    return ' &hellip;' . terrific_continue_reading_link();
}

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * @since Terrific 1.0
 */
function terrific_custom_excerpt_more( $output ) {
    if (has_excerpt() && ! is_attachment()) {
        $output .= terrific_continue_reading_link();
    }
    return $output;
}

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @since Terrific 1.0
 */
function terrific_page_menu_args( $args ) {
    $args['show_home'] = true;
    return $args;
}

/**
 * Register our sidebars and widgetized areas.
 *
 * @since Terrific 1.0
 */
function terrific_widgets_init() {
    register_sidebar(array(
        'name' => __('Sidebar', 'terrific'),
        'id' => 'sidebar-1',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}

/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Terrific 1.0
 */
function terrific_comment( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    switch ( $comment->comment_type ) :
        case 'pingback' :
        case 'trackback' :
    ?>
    <li class="post pingback">
        <p><?php _e( 'Pingback:', 'terrific' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'terrific' ), '<span class="edit-link">', '</span>' ); ?></p>
    <?php
            break;
        default :
    ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
        <article id="comment-<?php comment_ID(); ?>" class="comment">
            <footer class="comment-meta">
                <div class="comment-author vcard">
                    <?php
                        $avatar_size = 39;
                        echo get_avatar( $comment, $avatar_size );
						echo '<div class="comment-author-info">';
                        /* translators: 1: comment author, 2: date and time */
                        printf( __( '%1$s%2$s', 'terrific' ),
                            sprintf( '<div class="fn">%s</div>', get_comment_author_link() ),
                            sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
                                esc_url( get_comment_link( $comment->comment_ID ) ),
                                get_comment_time('c'),
                                /* translators: 1: date, 2: time */
                                sprintf( __( '%1$s at %2$s', 'terrific' ), get_comment_date(), get_comment_time() )
                            )
                        );
                    ?>
                    <?php edit_comment_link( __( 'Edit', 'terrific' ), '<span class="edit-link">', '</span>' ); ?>
					</div>
                </div><!-- .comment-author .vcard -->
                <?php if ( $comment->comment_approved == '0' ) : ?>
                    <em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'terrific' ); ?></em>
                <?php endif; ?>
            </footer>
            <div class="comment-content"><?php comment_text(); ?></div>
            <div class="reply">
                <?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'terrific' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
            </div><!-- .reply -->
        </article><!-- #comment-## -->
    <?php
            break;
    endswitch;
}

/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since Terrific 1.0
 */
function terrific_posted_on() {
	// TODO: Translation
    printf(__( '<span class="sep">Veröffentlicht am </span><time class="entry-date" datetime="%3$s" pubdate>%4$s</time><span class="by-author"> <span class="sep">von</span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'terrific'),
        esc_url(get_permalink()),
        esc_attr(get_the_time()),
        esc_attr(get_the_date('c')),
        esc_html(get_the_date()),
        esc_url(get_author_posts_url( get_the_author_meta('ID'))),
        sprintf(esc_attr__( 'View all posts by %s', 'terrific'), get_the_author()),
        esc_html(get_the_author())
    );
}

/**
 * Get the page title.
 *
 * @since Terrific 1.0
 */
function terrific_page_title() {
	global $page, $paged;
	$title = '';
	$title .= wp_title('-', false, 'right');
	$title .= get_bloginfo('name');
	$site_description = get_bloginfo('description', 'display');
	if ($site_description && (is_home() || is_front_page()))
		$title .= " - $site_description";
	if ($paged >= 2 || $page >= 2)
		$title .= ' - ' . sprintf(__('Page %s', 'terrific'), max($paged, $page));
	return $title;
}

/**
 * Get current skin identifier. Reads skin setting from theme settings. This
 * is configurable via admin interface.
 *
 * @since Terrific 1.0
 */
function terrific_get_skin() {
	$options = get_option('terrific_theme_options');
	if (isset($options['skin']) && $options['skin'] != '') {
		$skinname = strtoupper(substr($options['skin'],0,1)) . substr($options['skin'], 1, -4);
		return $skinname;
	}
	return null;
}

function terrific_header() {
	
	// add google+ explicit render js
	$script = '<script type="text/javascript" src="https://apis.google.com/js/plusone.js">' . "\n";
	$script .= '  {"parsetags": "explicit"}' . "\n";
	$script .= '</script>' . "\n";
	echo $script;
	
}

function terrific_adminbar_menu() {
	global $wp_admin_bar;
	$wp_admin_bar->add_menu(
		array(	'id' => 'terrific-menu',
				'title' => __( 'Terrific' ),
				'href' => '#'
		)
	);
	
	// PROJECT MENU
	$wp_admin_bar->add_menu(
		array(	'id' => 'terrific-project-menu',
				'parent' => 'terrific-menu',
				'title' => __( 'Project' ),
				'href' => '#'
		)
	);
	
	// MODULES MENU
	$wp_admin_bar->add_menu(
		array(	'id' => 'terrific-modules-menu',
				'parent' => 'terrific-menu',
				'title' => __( 'Modules' ),
				'href' => '#'
		)
	);
	
	// FLUSH
	$wp_admin_bar->add_menu(
		array(	'parent' => 'terrific-menu',
				'title' => __( 'Flush' ),
				'href' => get_template_directory_uri() . '/terrific/flush.php'
		)
	);
	
	// add modules to dropdown menu
	$modules = terrific_get_modules();
	foreach ($modules as $module) {
		$wp_admin_bar->add_menu(
			array(
				'parent' => 'terrific-modules-menu',
				'title' => __($module),
				'href' => '/module/?id=' . $module
			)
		);
	}
	
}

/**
 * Get list of terrific modules.
 * 
 * @since Terrific 1.0
 */
function terrific_get_modules() {
	$modules = array();
    foreach (glob(dirname(__FILE__) . '/terrific/modules/*', GLOB_ONLYDIR) as $dir) {
        $modules[] = basename($dir);
	}
	return $modules;
}

function terrific_get_favicon() {
	$skin = strtolower(terrific_get_skin());
	if (is_file(dirname(__FILE__) . '/terrific/img/favicon-' . $skin .'.ico')) {
 		$path = get_template_directory_uri() . '/terrific/img/favicon-' . $skin .'.ico';
	} else {
		$path = get_template_directory_uri() . '/terrific/img/favicon.ico';
	}
	echo $path;
}
