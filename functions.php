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
add_image_size( 'genesis-singular-images', 702, 526, true );

// Removes header right widget area.
unregister_sidebar( 'header-right' );

// Removes secondary sidebar.
unregister_sidebar( 'sidebar-alt' );

// Removes site layouts.
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

// Replace site title with logo.
remove_action( 'genesis_site_title', 'genesis_seo_site_title' );
add_action( 'genesis_site_title', 'carmel_site_logo' );
/**
 * Prints the site logo wrapped in an anchor tag.
 */
function carmel_site_logo() {

	$src = get_stylesheet_directory_uri() . '/images/logo.svg';
	$img = sprintf( '<img src="%1$s" alt="%2$s" height="64" width="256">', $src, get_bloginfo( 'name', 'display' ) );
	printf( '<a href="%1$s">%2$s</a>', trailingslashit( home_url() ), $img );

}

// Repositions primary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header', 'genesis_do_nav', 12 );

// Repositions the secondary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_before_footer', 'genesis_do_subnav', 0 );

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


add_filter('excerpt_length', 'carmel_excerpt_length');
/**
 * Modifies size of the exerpt length.
 *
 * @param int $size Original length.
 */
function carmel_excerpt_length( $length ) {

	return 25;

}

add_filter( 'genesis_pre_get_option_site_layout', 'carmel_layout_override' );
/**
 * Overrides the layout to full-width-content if not singular.
 */
function carmel_layout_override( $opt ) {

	if ( ! is_singular() ) {

		$opt = 'full-width-content';
		return $opt;

	}

}

add_action('template_redirect', 'carmel_disable_author_archive');
/**
 * Returns a 404 for author pages.
 */
function carmel_disable_author_archive() {

    global $wp_query;

    if ( is_author() ) {
        $wp_query->set_404();
        status_header(404);
    }

}

// Move descriptions out of site-inner.
remove_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );
add_action( 'genesis_after_header', 'genesis_do_taxonomy_title_description' );
remove_action( 'genesis_before_loop', 'genesis_do_author_title_description', 15 );
add_action( 'genesis_after_header', 'genesis_do_author_title_description' );
remove_action( 'genesis_before_loop', 'genesis_do_cpt_archive_title_description' );
add_action( 'genesis_after_header', 'genesis_do_cpt_archive_title_description' );
remove_action( 'genesis_before_loop', 'genesis_do_date_archive_title' );
add_action( 'genesis_after_header', 'genesis_do_date_archive_title' );
remove_action( 'genesis_before_loop', 'genesis_do_posts_page_heading' );
add_action( 'genesis_after_header', 'genesis_do_posts_page_heading' );

add_action( 'genesis_loop', 'carmel_custom_loop' );
remove_action( 'genesis_loop', 'genesis_do_loop' );
/**
 * Custom loop that overrides the layout for archive and search result.
 */
function carmel_custom_loop() {

	if ( is_404() ) {

		return;

	}

	if ( is_singular() ) {

		genesis_do_loop();

	}

	else if ( have_posts() ) {

		do_action( 'genesis_before_while' );

		genesis_markup(
			[
				'open'   => '<div class="post-grid">',
			]
		);

		while ( have_posts() ) {

			the_post();


			genesis_markup(
				[
					'open'    => '<a %s>',
					'context' => 'entry',
					'atts' => [ 'href' => get_the_permalink() ]
				]
			);

			remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
			if ( genesis_get_option( 'content_archive_thumbnail' ) ) {
				echo genesis_get_image(
					[
						'format'  => 'html',
						'size'    => genesis_get_option( 'image_size' ),
						'context' => 'archive',
					]
				);
			}

			genesis_markup(
				[
					'open'   => '<div class="entry-content">',
				]
			);

			add_filter( 'genesis_link_post_title', '__return_false' );
			genesis_do_post_title();

			do_action( 'genesis_entry_content' );

			genesis_markup(
				[
					'close'   => '</div>',
				]
			);

			genesis_markup(
				[
					'close'   => '</a>',
					'context' => 'entry',
				]
			);

		}

		genesis_markup(
			[
				'close'   => '</div>',
			]
		);

		do_action( 'genesis_after_endwhile' );

	} else {

		do_action( 'genesis_loop_else' );

	}

	genesis_reset_loops();

}
