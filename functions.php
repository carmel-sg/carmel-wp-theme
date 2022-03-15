<?php
/**
 * Carmel.
 *
 * This file adds functions to the Carmel Theme.
 *
 * @package Carmel
 * @license GPL-2.0-or-later
 */

// Starts the engine.
// Enqueue Custom JS script
add_action( 'wp_enqueue_scripts', 'enqueue_custom_script' );
function enqueue_custom_script() {
    wp_enqueue_script( 'custom', get_stylesheet_directory_uri() . '/js/custom.js', array( 'jquery' ),  '1.0.0', true );
}
require_once get_template_directory() . '/lib/init.php';

// Sets up the Theme.
require_once get_stylesheet_directory() . '/lib/theme-defaults.php';

// Adds helper functions.
require_once get_stylesheet_directory() . '/lib/helper-functions.php';

// Adds image upload and color select to Customizer.
require_once get_stylesheet_directory() . '/lib/customize.php';

// Includes Customizer CSS.
require_once get_stylesheet_directory() . '/lib/output.php';

add_action( 'after_setup_theme', 'genesis_child_gutenberg_support' );
/**
 * Adds Gutenberg opt-in features and styling.
 */
function genesis_child_gutenberg_support() { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- using same in all child themes to allow action to be unhooked.
	require_once get_stylesheet_directory() . '/lib/gutenberg/init.php';
}

// Registers the responsive menus.
if ( function_exists( 'genesis_register_responsive_menus' ) ) {
	genesis_register_responsive_menus( genesis_get_config( 'responsive-menus' ) );
}

add_action( 'wp_enqueue_scripts', 'carmel_enqueue_scripts_styles' );
/**
 * Enqueues scripts and styles.
 */
function carmel_enqueue_scripts_styles() {

	$appearance = genesis_get_config( 'appearance' );

	wp_enqueue_style( // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- see https://core.trac.wordpress.org/ticket/49742
		genesis_get_theme_handle() . '-fonts',
		$appearance['fonts-url'],
		[],
		null
	);

	wp_enqueue_style( 'dashicons' );

	if ( genesis_is_amp() ) {
		wp_enqueue_style(
			genesis_get_theme_handle() . '-amp',
			get_stylesheet_directory_uri() . '/lib/amp/amp.css',
			[ genesis_get_theme_handle() ],
			genesis_get_theme_version()
		);
	}

}

add_filter( 'body_class', 'carmel_body_classes' );
/**
 * Add additional classes to the body element.
 *
 * @param array $classes Classes array.
 * @return array $classes Updated class array.
 */
function carmel_body_classes( $classes ) {

	if ( ! genesis_is_amp() ) {
		// Add 'no-js' class to the body class values.
		$classes[] = 'no-js';
	}
	return $classes;
}

add_action( 'genesis_before', 'carmel_js_nojs_script', 1 );
/**
 * Echo the script that changes 'no-js' class to 'js'.
 */
function carmel_js_nojs_script() {

	if ( genesis_is_amp() ) {
		return;
	}

	?>
	<script>
	//<![CDATA[
	(function(){
		var c = document.body.classList;
		c.remove( 'no-js' );
		c.add( 'js' );
	})();
	//]]>
	</script>
	<?php
}

add_filter( 'wp_resource_hints', 'carmel_resource_hints', 10, 2 );
/**
 * Add preconnect for Google Fonts.
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed.
 * @return array URLs to print for resource hints.
 */
function carmel_resource_hints( $urls, $relation_type ) {

	if ( wp_style_is( genesis_get_theme_handle() . '-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = [
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		];
	}

	return $urls;
}

add_action( 'after_setup_theme', 'carmel_theme_support', 9 );
/**
 * Add desired theme supports.
 *
 * See config file at `config/theme-supports.php`.
 */
function carmel_theme_support() {

	$theme_supports = genesis_get_config( 'theme-supports' );

	foreach ( $theme_supports as $feature => $args ) {
		add_theme_support( $feature, $args );
	}

}

add_action( 'after_setup_theme', 'carmel_post_type_support', 9 );
/**
 * Add desired post type supports.
 *
 * See config file at `config/post-type-supports.php`.
 */
function carmel_post_type_support() {

	$post_type_supports = genesis_get_config( 'post-type-supports' );

	foreach ( $post_type_supports as $post_type => $args ) {
		add_post_type_support( $post_type, $args );
	}

}

// Adds image sizes.
add_image_size( 'sidebar-featured', 75, 75, true );
add_image_size( 'genesis-singular-images', 702, 526, true );

// Removes header right widget area.
unregister_sidebar( 'header-right' );

// Removes secondary sidebar.
unregister_sidebar( 'sidebar-alt' );

// Removes site layouts.
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

// Repositions primary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header', 'genesis_do_nav', 12 );

// Repositions the secondary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_footer', 'genesis_do_subnav', 10 );

add_filter( 'wp_nav_menu_args', 'carmel_secondary_menu_args' );
/**
 * Reduces secondary navigation menu to one level depth.
 *
 * @param array $args Original menu options.
 * @return array Menu options with depth set to 1.
 */
function carmel_secondary_menu_args( $args ) {

	if ( 'secondary' === $args['theme_location'] ) {
		$args['depth'] = 1;
	}

	return $args;

}

add_filter( 'genesis_author_box_gravatar_size', 'carmel_author_box_gravatar' );
/**
 * Modifies size of the Gravatar in the author box.
 *
 * @param int $size Original icon size.
 * @return int Modified icon size.
 */
function carmel_author_box_gravatar( $size ) {

	return 90;

}

add_filter( 'genesis_comment_list_args', 'carmel_comments_gravatar' );
/**
 * Modifies size of the Gravatar in the entry comments.
 *
 * @param array $args Gravatar settings.
 * @return array Gravatar settings with modified size.
 */
function carmel_comments_gravatar( $args ) {

	$args['avatar_size'] = 60;
	return $args;

}

// Enables block-based widget editor.
add_filter( 'use_widgets_block_editor', '__return_true' );
unregister_sidebar( 'header-right' );

add_action( 'genesis_header_right', 'hook_header_right' );
function hook_header_right() {
echo'<a class="button join-us" href="/mount-caramel/join-us-in-person">Join Us In Person</a>';
}


//Don't paste in the above php tag

// Enqueue scripts and styles
add_action( 'wp_enqueue_scripts', 'hello_bar_scripts_styles' );

function hello_bar_scripts_styles() {
	wp_enqueue_script( 'hello-bar', esc_url( get_stylesheet_directory_uri() ) . '/js/hello-bar.js', array( 'jquery' ), '1.0.0' );
}

//Add in new Widget areas
add_action( 'widgets_init', 'hello_bar_extra_widgets' );

function hello_bar_extra_widgets() {
	genesis_register_sidebar( array(
	'id'          => 'preheaderleft',
	'name'        => __( 'Pre-Header Left', 'themename' ),
	'description' => __( 'This is the preheader Left area', 'themename' ),
	'before_widget' => '<div class="first alignfull preheaderleft">',
    	'after_widget' => '</div>',
	) );
}

//Position the preHeader Area
add_action('genesis_before','hello_bar_preheader_widget');

function hello_bar_preheader_widget() {
	echo '<div class="preheadercontainer hello-bar "><div class="wrap">';
    	genesis_widget_area ('preheaderleft', array(
        'before' => '<div class="preheaderleftcontainer">',
        'after' => '</div>',));
    	genesis_widget_area ('preheaderright', array(
        'before' => '<div class="preheaderrightcontainer">',
        'after' => '</div>',));
    	echo '</div></div>';
}
// Force full width layout setting
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
add_action('get_header', 'remove_page_titles');
function remove_page_titles() {
if (is_page(13)) {
remove_action('genesis_entry_header', 'genesis_do_post_title');
}
}
//Extra Widget Area
function genesischild_footerwidgetheader() {
	genesis_register_sidebar( array(
	'id' => 'footerwidgetheader',
	'name' => __( 'Footer Widget Header', 'genesis' ),
	'description' => __( 'This is for the Footer Widget Headline', 'genesis' ),
	) );

}

add_action ('widgets_init','genesischild_footerwidgetheader');
//Position Widget Header
function genesischild_footerwidgetheader_position ()  {
	echo '<div class="footerwidgetheader-container"><div class="wrap">';
	genesis_widget_area ('footerwidgetheader');
	echo '</div></div>';

}

add_action ('genesis_before_footer','genesischild_footerwidgetheader_position', 5 );

// //Remove Existing Footer
// remove_action( 'genesis_footer', 'genesis_do_footer' );
// /**
//  * Add Footer Widget to Genesis
//  *
//  * @link http://wpbeaches.com/create-footer-widget-area-genesis-child-theme/
//  */
//  add_action( 'widgets_init', 'yourtheme_extra_widgets' );
// // Add in new Widget area
// function yourtheme_extra_widgets() {	
// 	genesis_register_sidebar( array(
// 		'id'            => 'footercontent',
// 		'name'          => __( 'Footer', 'yourtheme' ),
// 		'description'   => __( 'This is the general footer area', 'yourtheme' ),
// 		'before_widget' => '<div class="footercontent">',
// 	    	'after_widget'  => '</div>',
// 	));
// }
	
// add_action('genesis_footer','yourtheme_footer_widget');	
// // Position the Footer Area
// function yourtheme_footer_widget() {
// 	genesis_widget_area ('footercontent', array(
// 		'before' => '<div class ="footercontainer">',
// 		'after'  => '</div>',
// 	));
// }
// Register before-header widget area.

add_action( 'init', 'create_custom_post_type' );

function create_custom_post_type() {

   $labels = array(
    'name' => __( 'Courses' ),
    'singular_name' => __( 'Courses' )
    );

    $args = array(
    'labels' => $labels,
    'public' => true,
    'has_archive' => false,
    'rewrite' => array('slug' => 'courses'),
    'supports' => array( 'title', 'editor', 'custom-fields','thumbnail' )
    );

  register_post_type( 'courses', $args);
}
if(is_page( 22 ))
{

function custom_loop_with_pagination() {
    global $post;
 
    $args = array(
        'post_type'      => 'courses',  //---YOUR CUSTOM POST TYPE
        'posts_per_page' => 2,        //---SET HOW MANY POSTS MUST BE SHOWN ON ONE PAGE
        'paged'          => get_query_var( 'paged' )
    );
 
    global $wp_query;
    $wp_query = new WP_Query( $args );
 
    if ( have_posts() ) : 
        while ( have_posts() ) : the_post(); ?>
             
            <article>
            	<?php the_post_thumbnail(); ?>
                <h2><?php the_title(); ?></h2>
                <?php the_excerpt(); ?>
            </article>
 
        <?php endwhile;
        do_action( 'genesis_after_endwhile' );
    endif;
 
    wp_reset_query();
}
add_action( 'genesis_loop', 'custom_loop_with_pagination' );
remove_action( 'genesis_loop', 'genesis_do_loop' );
//add_shortcode( 'courses-list', 'custom_loop_with_pagination' );
}

add_action( 'init', 'create_custom_post_type_news' );

function create_custom_post_type_news() {

   $labels_news = array(
    'name' => __( 'News' ),
    'singular_name' => __( 'News' )
    );

    $args_news = array(
    'labels' => $labels_news,
    'public' => true,
    'has_archive' => false,
    'rewrite' => array('slug' => 'news'),
    'supports' => array( 'title', 'editor', 'custom-fields','full' )
    );

  register_post_type( 'news', $args_news);
}
if(is_page( 1209 ))
{

function custom_loop_with_pagination_news() {
    global $post;
 
    $args_news = array(
        'post_type'      => 'news',  //---YOUR CUSTOM POST TYPE
        'posts_per_page' => 2,        //---SET HOW MANY POSTS MUST BE SHOWN ON ONE PAGE
        'paged'          => get_query_var( 'paged' )
    );
 
    global $wp_query;
    $wp_query = new WP_Query( $args_news );
 
    if ( have_posts() ) : 
        while ( have_posts() ) : the_post(); ?>
             
            <article>
            	<?php the_post_thumbnail(); ?>
                <h2><?php the_title(); ?></h2>
                <?php the_excerpt(); ?>
            </article>
 
        <?php endwhile;
        do_action( 'genesis_after_endwhile' );
    endif;
 
    wp_reset_query();
}
add_action( 'genesis_loop', 'custom_loop_with_pagination_news' );
remove_action( 'genesis_loop', 'genesis_do_loop' );
//add_shortcode( 'courses-list', 'custom_loop_with_pagination' );
}
//* Do NOT include the opening php tag shown above. Copy the code shown below.
 
//* Modify the Genesis content limit read more link
add_filter( 'get_the_content_more_link', 'sp_read_more_link' );
function sp_read_more_link() {
    return '... <a class="more-link" href="' . get_permalink() . '">[Continue Reading]</a>';
}
?>
