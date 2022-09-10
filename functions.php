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
add_image_size( 'genesis-singular-images', 600, 600, true );
add_image_size( 'featured-image', 600, 600, true );

add_filter( 'post_thumbnail_size', 'carmel_override_post_thumbnail_size' );
/**
 * Replaces post-thumbnail size with featured-image size.
 */
function carmel_override_post_thumbnail_size( $size ) {

	if ( $size === 'post-thumbnail' ) {
		return 'featured-image';
	}

	return $size;

}

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

	$svg = sprintf( '<svg xmlns="http://www.w3.org/2000/svg" width="256" height="64" xml:space="preserve"><title>%1$s</title><g fill="#FFF"><path d="m80.45 21.52 3.49 13.37 3.48-13.37h4.88V40h-3.75v-5l.34-7.71L85.19 40h-2.54l-3.69-12.71.34 7.71v5h-3.73V21.52h4.88zM108.3 32.42c0 2.48-.59 4.41-1.76 5.78s-2.8 2.06-4.88 2.06c-2.07 0-3.7-.68-4.89-2.04s-1.79-3.26-1.8-5.71v-3.16c0-2.54.59-4.52 1.76-5.95s2.81-2.14 4.9-2.14c2.06 0 3.68.7 4.86 2.1s1.79 3.37 1.8 5.9v3.16zm-3.74-3.1c0-1.67-.24-2.91-.71-3.72s-1.21-1.22-2.21-1.22c-.99 0-1.72.39-2.2 1.17s-.72 1.97-.74 3.57v3.29c0 1.62.24 2.81.72 3.57s1.23 1.15 2.23 1.15c.97 0 1.7-.37 2.17-1.12s.72-1.91.72-3.48v-3.21zM123 21.52v12.8c-.02 1.92-.55 3.39-1.59 4.41s-2.53 1.53-4.48 1.53c-1.98 0-3.49-.52-4.54-1.55s-1.57-2.53-1.57-4.48V21.52h3.76v12.72c0 1.05.17 1.8.51 2.25s.95.67 1.84.67 1.5-.22 1.83-.67.5-1.17.51-2.19V21.52H123zM138.76 40h-3.73l-5.46-12.12V40h-3.73V21.52h3.73l5.47 12.14V21.52h3.72V40zM153.36 24.63h-4.57V40h-3.75V24.63h-4.49v-3.11h12.81v3.11zM173.26 33.84c-.09 2.11-.69 3.7-1.78 4.79s-2.63 1.63-4.62 1.63c-2.09 0-3.69-.69-4.81-2.06s-1.67-3.34-1.67-5.88V29.2c0-2.54.58-4.5 1.73-5.87s2.75-2.06 4.8-2.06c2.01 0 3.54.56 4.59 1.69s1.64 2.74 1.78 4.85h-3.75c-.03-1.3-.23-2.2-.6-2.7s-1.04-.74-2.02-.74c-1 0-1.71.35-2.12 1.05s-.63 1.85-.66 3.45v3.49c0 1.84.21 3.1.62 3.78s1.12 1.03 2.11 1.03c.98 0 1.66-.24 2.03-.72s.58-1.34.63-2.6h3.74zM184.38 36.22h-5.09L178.3 40h-3.95l5.78-18.48h3.42L189.36 40h-3.99l-.99-3.78zm-4.28-3.11h3.45l-1.73-6.59-1.72 6.59zM196.69 33.25h-1.85V40h-3.73V21.52h5.95c1.87 0 3.32.48 4.34 1.45s1.53 2.35 1.53 4.13c0 2.45-.89 4.17-2.68 5.15l3.24 7.57V40h-4.01l-2.79-6.75zm-1.86-3.11h2.12c.74 0 1.3-.25 1.68-.74s.56-1.16.56-1.99c0-1.85-.72-2.78-2.17-2.78h-2.18v5.51zM210.36 21.52l3.49 13.37 3.48-13.37h4.88V40h-3.75v-5l.34-7.71L215.11 40h-2.54l-3.69-12.71.34 7.71v5h-3.73V21.52h4.87zM234.75 32h-5.81v4.9h6.88V40H225.2V21.52h10.59v3.11h-6.86V29h5.81v3zM241.69 36.9h6.55V40h-10.28V21.52h3.73V36.9zM7.09 52h57v2h-57zM7.09 49h57v2h-57zM7.09 46h57v2h-57zM7.09 43h57v2h-57zM7.09 40h57v2h-57zM7.09 37h57v2h-57zM7.09 34h57v2h-57zM7.09 31h57v2h-57zM7.09 28h57v2h-57zM7.09 25h57v2h-57zM59.09 12h1v13h-1z"/><path d="M55.09 16h9v1h-9z"/><g><path d="M75.16 54v-9.95H78c.91 0 1.59.22 2.05.66s.69 1.1.69 1.99c0 .46-.12.88-.35 1.23s-.55.63-.94.83c.46.14.82.42 1.08.84s.4.93.4 1.53c0 .89-.25 1.6-.73 2.11S79 54 78.09 54h-2.93zm1.25-5.71h1.61c.43 0 .78-.15 1.05-.44.27-.29.4-.68.4-1.16 0-.55-.12-.95-.36-1.2s-.61-.37-1.11-.37h-1.59v3.17zm0 1.05v3.58h1.71c.47 0 .85-.16 1.13-.47.28-.31.42-.75.42-1.32 0-1.2-.51-1.8-1.52-1.8h-1.74zM84.1 54h-1.25v-9.95h1.25V54zM86.3 54v-9.95h2.84c.91 0 1.59.22 2.05.66s.69 1.1.69 1.99c0 .46-.12.88-.35 1.23s-.55.63-.94.83c.46.14.82.42 1.08.84s.4.93.4 1.53c0 .89-.25 1.6-.73 2.11s-1.21.76-2.11.76H86.3zm1.25-5.71h1.61c.43 0 .78-.15 1.05-.44.27-.29.4-.68.4-1.16 0-.55-.12-.95-.36-1.2s-.61-.37-1.11-.37h-1.59v3.17zm0 1.05v3.58h1.71c.47 0 .85-.16 1.13-.47.28-.31.42-.75.42-1.32 0-1.2-.51-1.8-1.52-1.8h-1.74zM95.19 52.93h3.9V54h-5.16v-9.95h1.26v8.88zM105.35 49.4h-3.52v3.53h4.1V54h-5.35v-9.95h5.28v1.07h-4.03v3.21h3.52v1.07zM109.61 50.29h-2.82v-1.03h2.82v1.03zM112.26 50.1V54h-1.25v-9.95h3.17c.93 0 1.65.28 2.18.83s.79 1.29.79 2.21c0 .96-.25 1.69-.76 2.21-.5.52-1.21.79-2.13.8h-2zm0-1.07h1.92c.56 0 .98-.17 1.28-.5.3-.33.44-.81.44-1.43 0-.6-.15-1.08-.46-1.44s-.72-.54-1.26-.54h-1.93v3.91zM121.77 49.97h-1.75V54h-1.26v-9.95h2.79c.98 0 1.71.25 2.21.76s.75 1.25.75 2.23c0 .62-.14 1.15-.41 1.61-.27.46-.66.8-1.15 1.04l1.93 4.23V54h-1.35l-1.76-4.03zm-1.75-1.07h1.52c.52 0 .94-.17 1.25-.51s.47-.79.47-1.36c0-1.28-.58-1.92-1.74-1.92h-1.5v3.79zM130.97 49.4h-3.52v3.53h4.1V54h-5.35v-9.95h5.28v1.07h-4.03v3.21h3.52v1.07zM137.5 51.48c0-.5-.13-.88-.4-1.14s-.75-.52-1.46-.77-1.24-.51-1.61-.79-.65-.59-.83-.94-.28-.76-.28-1.21c0-.79.26-1.44.79-1.95s1.22-.77 2.07-.77c.58 0 1.1.13 1.56.39s.81.62 1.05 1.09.37.97.37 1.53h-1.26c0-.62-.15-1.09-.44-1.43s-.72-.51-1.28-.51c-.51 0-.9.14-1.18.42-.28.28-.42.68-.42 1.19 0 .42.15.77.45 1.05.3.28.77.54 1.39.76.98.32 1.68.72 2.1 1.2s.63 1.09.63 1.86c0 .81-.26 1.45-.79 1.94-.52.49-1.24.73-2.14.73-.58 0-1.11-.13-1.6-.38s-.88-.61-1.16-1.07c-.28-.46-.42-.98-.42-1.58h1.26c0 .62.17 1.09.52 1.44s.81.51 1.4.51c.55 0 .97-.14 1.24-.42s.44-.66.44-1.15zM140.4 54v-9.95h2.84c.91 0 1.59.22 2.05.66s.69 1.1.69 1.99c0 .46-.12.88-.35 1.23s-.55.63-.94.83c.46.14.82.42 1.08.84s.4.93.4 1.53c0 .89-.25 1.6-.73 2.11s-1.19.77-2.1.77h-2.94zm1.25-5.71h1.61c.43 0 .78-.15 1.05-.44.27-.29.4-.68.4-1.16 0-.55-.12-.95-.36-1.2s-.61-.37-1.11-.37h-1.59v3.17zm0 1.05v3.58h1.71c.47 0 .85-.16 1.13-.47.28-.31.42-.75.42-1.32 0-1.2-.51-1.8-1.52-1.8h-1.74zM150.25 49.04l2.04-5h1.42l-2.83 6.24V54h-1.25v-3.71l-2.83-6.24h1.42l2.03 4.99zM160.94 45.12h-2.62V54h-1.25v-8.88h-2.61v-1.07h6.48v1.07zM167.18 49.4h-3.52v3.53h4.1V54h-5.35v-9.95h5.28v1.07h-4.03v3.21h3.52v1.07zM172.37 49.97h-1.75V54h-1.26v-9.95h2.79c.98 0 1.71.25 2.21.76s.75 1.25.75 2.23c0 .62-.14 1.15-.41 1.61-.27.46-.66.8-1.15 1.04l1.93 4.23V54h-1.35l-1.76-4.03zm-1.75-1.07h1.52c.52 0 .94-.17 1.25-.51s.47-.79.47-1.36c0-1.28-.58-1.92-1.74-1.92h-1.5v3.79zM178.1 54h-1.25v-9.95h1.25V54zM185.05 51.4h-3.36l-.77 2.6h-1.29l3.21-9.95h1.07l3.21 9.95h-1.29l-.78-2.6zm-3.03-1.08h2.71l-1.36-4.53-1.35 4.53zM194.98 54h-1.26l-3.99-7.67V54h-1.26v-9.95h1.26l4 7.7v-7.7h1.24V54zM206.59 50.83c-.05 1.07-.35 1.88-.9 2.45-.54.57-1.31.85-2.31.85s-1.79-.38-2.38-1.14c-.59-.76-.88-1.79-.88-3.09v-1.8c0-1.29.3-2.32.91-3.07s1.43-1.13 2.47-1.13c.96 0 1.71.29 2.23.86s.81 1.4.86 2.47h-1.26c-.05-.81-.23-1.39-.51-1.74s-.72-.52-1.31-.52c-.68 0-1.2.27-1.57.8s-.55 1.31-.55 2.33v1.83c0 1.01.17 1.78.51 2.32s.83.81 1.49.81 1.12-.16 1.41-.49.46-.9.53-1.74h1.26zM214.8 54h-1.26v-4.6h-4.01V54h-1.25v-9.95h1.25v4.28h4.01v-4.28h1.26V54zM222.9 44.05v7.08c-.01.94-.28 1.68-.82 2.21s-1.29.8-2.25.8c-.98 0-1.74-.26-2.26-.78-.52-.52-.78-1.27-.79-2.23v-7.08h1.24v7.02c0 .67.14 1.17.43 1.5s.74.5 1.38.5c.64 0 1.1-.17 1.38-.5s.43-.83.43-1.5v-7.02h1.26zM227.84 49.97h-1.75V54h-1.26v-9.95h2.79c.98 0 1.71.25 2.21.76s.75 1.25.75 2.23c0 .62-.14 1.15-.41 1.61-.27.46-.66.8-1.15 1.04l1.93 4.23V54h-1.35l-1.76-4.03zm-1.75-1.07h1.52c.52 0 .94-.17 1.25-.51s.47-.79.47-1.36c0-1.28-.58-1.92-1.74-1.92h-1.5v3.79zM238.5 50.83c-.05 1.07-.35 1.88-.9 2.45-.54.57-1.31.85-2.31.85s-1.79-.38-2.38-1.14c-.59-.76-.88-1.79-.88-3.09v-1.8c0-1.29.3-2.32.91-3.07s1.43-1.13 2.47-1.13c.96 0 1.71.29 2.23.86s.81 1.4.86 2.47h-1.26c-.05-.81-.23-1.39-.51-1.74s-.72-.52-1.31-.52c-.68 0-1.2.27-1.57.8s-.55 1.31-.55 2.33v1.83c0 1.01.17 1.78.51 2.32s.83.81 1.49.81 1.12-.16 1.41-.49.46-.9.53-1.74h1.26zM246.71 54h-1.26v-4.6h-4.01V54h-1.25v-9.95h1.25v4.28h4.01v-4.28h1.26V54z"/></g></g></svg>', get_bloginfo( 'name', 'display' ) );
	printf( '<a href="%1$s">%2$s</a>', trailingslashit( home_url() ), $svg );

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

		echo '<div class="entry-content">';

		echo '<ul class="is-flex-container columns-3 wp-block-post-template">';

		while ( have_posts() ) {

			the_post();

			genesis_markup(
				[
					'open'    => '<li %s>',
					'context' => 'entry',
				]
			);

			if ( genesis_get_option( 'content_archive_thumbnail' ) ) {
				$img = genesis_get_image(
					[
						'format'  => 'html',
						'size'    => genesis_get_option( 'image_size' ),
						'context' => 'archive',
						'attr'    => genesis_parse_attr( 'entry-image', [] ),
					]
				);
		
				if ( ! empty( $img ) ) {
					echo '<figure class="wp-block-post-featured-image">';
					genesis_markup(
						[
							'open'    => '<a %s>',
							'close'   => '</a>',
							'content' => $img,
							'context' => 'entry-image-link',
						]
					);
					echo '</figure>';
				}
			}

			echo '<h2 class="wp-block-post-title">';

			genesis_markup(
				[
					'open'    => '<a %s>',
					'close'   => '</a>',
					'content' => get_the_title(),
					'context' => 'entry-title-link',
				]
			);

			echo '</h2>';

			genesis_do_post_content();

			genesis_markup(
				[
					'close'   => '</li>',
					'context' => 'entry',
				]
			);

		}

		echo '</ul>';

		genesis_posts_nav();

		echo '</div>';

	} else {

		do_action( 'genesis_loop_else' );

	}

	genesis_reset_loops();

}
