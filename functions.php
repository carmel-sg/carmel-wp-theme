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
add_image_size( 'genesis-singular-images', 960, 540, true );

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

	$svg = sprintf( '<svg width="256" height="64" xml:space="preserve"><title>%s</title><path fill="#FFF" d="m80.45 21.52 3.49 13.371 3.48-13.371h4.88V40h-3.75v-5l.34-7.71L85.19 40h-2.54l-3.69-12.71.34 7.71v5h-3.73V21.52h4.88zm27.85 10.9c0 2.48-.59 4.41-1.76 5.78s-2.8 2.06-4.88 2.06c-2.07 0-3.7-.68-4.89-2.039-1.19-1.361-1.79-3.261-1.8-5.711v-3.16c0-2.54.59-4.52 1.76-5.95 1.17-1.43 2.81-2.14 4.9-2.14 2.06 0 3.68.7 4.86 2.1 1.18 1.4 1.79 3.37 1.8 5.9v3.16h.01zm-3.74-3.1c0-1.67-.24-2.91-.71-3.72s-1.21-1.22-2.21-1.22c-.99 0-1.72.39-2.2 1.17-.48.78-.72 1.97-.74 3.57v3.29c0 1.619.24 2.811.72 3.57s1.23 1.149 2.23 1.149c.97 0 1.7-.37 2.17-1.12.47-.75.72-1.91.72-3.48V29.32h.02zm18.44-7.8v12.8c-.02 1.92-.55 3.39-1.59 4.41s-2.53 1.529-4.48 1.529c-1.98 0-3.49-.52-4.54-1.55-1.05-1.03-1.57-2.53-1.57-4.479V21.52h3.76v12.72c0 1.05.17 1.8.51 2.25.34.449.95.67 1.84.67.89 0 1.5-.221 1.83-.67.33-.45.5-1.17.51-2.19V21.52H123zM138.76 40h-3.73l-5.459-12.12V40h-3.73V21.52h3.73l5.47 12.14V21.52h3.72V40zm14.599-15.37h-4.569V40h-3.75V24.63h-4.49v-3.11h12.81v3.11h-.001zm19.901 9.21c-.09 2.11-.689 3.7-1.779 4.79-1.09 1.091-2.631 1.63-4.621 1.63-2.09 0-3.689-.689-4.81-2.06s-1.67-3.341-1.67-5.88V29.2c0-2.54.58-4.5 1.729-5.87 1.15-1.37 2.75-2.06 4.801-2.06 2.01 0 3.54.56 4.59 1.69s1.641 2.74 1.779 4.85h-3.75c-.029-1.3-.229-2.2-.6-2.7-.369-.5-1.039-.74-2.02-.74-1 0-1.71.35-2.12 1.05-.41.7-.63 1.85-.66 3.45v3.49c0 1.84.21 3.1.62 3.781.41.68 1.12 1.029 2.109 1.029.98 0 1.66-.24 2.031-.72.369-.479.58-1.341.629-2.601h3.74v-.009zm11.12 2.381h-5.09L178.3 40h-3.95l5.78-18.48h3.42L189.36 40h-3.989l-.991-3.779zm-4.28-3.112h3.45l-1.729-6.589-1.721 6.589zm16.589.141h-1.85V40h-3.73V21.52h5.951c1.869 0 3.319.48 4.34 1.45 1.02.97 1.529 2.35 1.529 4.13 0 2.45-.89 4.17-2.68 5.15l3.24 7.57V40h-4.01l-2.79-6.75zm-1.859-3.11h2.12c.739 0 1.3-.25 1.68-.74.38-.49.56-1.16.56-1.99 0-1.85-.719-2.78-2.17-2.78h-2.18v5.51h-.01zm15.529-8.62 3.49 13.371 3.48-13.371h4.88V40h-3.75v-5l.34-7.71-3.69 12.71h-2.539l-3.69-12.71.341 7.71v5h-3.73V21.52h4.868zM234.75 32h-5.811v4.9h6.881V40H225.2V21.52h10.59v3.11h-6.86V29h5.811v3h.009zm6.939 4.9h6.551V40h-10.28V21.52h3.729V36.9zM7.09 52h57v2h-57v-2zm0-3h57v2h-57v-2zm0-3h57v2h-57v-2zm0-3h57v2h-57v-2zm0-3h57v2h-57v-2zm0-3h57v2h-57v-2zm0-3h57v2h-57v-2zm0-3h57v2h-57v-2zm0-3h57v2h-57v-2zm0-3h57v2h-57v-2zm52-13h1v13h-1V12z"/><path fill="#FFF" d="M55.09 16h9v1h-9v-1zM75.16 54v-9.95H78c.91 0 1.59.22 2.05.66s.69 1.101.69 1.99c0 .46-.12.88-.35 1.229s-.55.631-.94.83c.46.141.82.42 1.08.84.26.42.4.93.4 1.53 0 .89-.25 1.601-.73 2.11S79 54 78.09 54h-2.93zm1.25-5.71h1.61c.43 0 .78-.149 1.05-.44.27-.289.4-.68.4-1.16 0-.549-.12-.949-.36-1.199-.24-.25-.61-.37-1.11-.37h-1.59v3.169zm0 1.05v3.58h1.71c.47 0 .85-.16 1.13-.47s.42-.75.42-1.32c0-1.2-.51-1.8-1.52-1.8h-1.74v.01zM84.1 54h-1.25v-9.95h1.25V54zm2.2 0v-9.95h2.84c.91 0 1.59.22 2.05.66s.69 1.101.69 1.99c0 .46-.12.88-.35 1.229s-.55.631-.94.83c.46.141.82.42 1.08.84.26.42.4.93.4 1.53 0 .89-.25 1.601-.73 2.11S90.13 54 89.23 54H86.3zm1.25-5.71h1.61c.43 0 .78-.149 1.05-.44.27-.289.4-.68.4-1.16 0-.549-.12-.949-.36-1.199s-.61-.37-1.11-.37h-1.59v3.169zm0 1.05v3.58h1.71c.47 0 .85-.16 1.13-.47.28-.31.42-.75.42-1.32 0-1.2-.51-1.8-1.52-1.8h-1.74v.01zm7.64 3.59h3.9V54h-5.16v-9.95h1.26v8.88zm10.16-3.53h-3.52v3.529h4.1V54h-5.35v-9.95h5.28v1.07h-4.03v3.21h3.52v1.07zm4.26.89h-2.82v-1.03h2.82v1.03zm2.65-.19V54h-1.25v-9.95h3.17c.93 0 1.65.28 2.18.83.53.55.79 1.29.79 2.21 0 .96-.25 1.689-.76 2.21-.5.521-1.21.79-2.13.8h-2zm0-1.071h1.92c.56 0 .98-.17 1.28-.5.3-.329.44-.809.44-1.43 0-.6-.15-1.08-.46-1.439-.31-.36-.72-.54-1.26-.54h-1.93v3.909h.01zm9.51.942h-1.75V54h-1.26v-9.95h2.79c.98 0 1.71.25 2.21.761.5.51.75 1.25.75 2.229 0 .62-.14 1.149-.41 1.61-.27.459-.66.8-1.15 1.039l1.93 4.23V54h-1.35l-1.76-4.029zm-1.75-1.071h1.52c.52 0 .94-.17 1.25-.51.31-.341.47-.791.47-1.361 0-1.279-.58-1.92-1.74-1.92h-1.5V48.9zm10.951.5h-3.521v3.529h4.1V54h-5.35v-9.95h5.28v1.07h-4.03v3.21h3.521v1.07zm6.529 2.08c0-.5-.13-.881-.4-1.141s-.75-.52-1.459-.77c-.711-.25-1.24-.51-1.611-.791-.369-.279-.649-.59-.829-.939-.181-.35-.28-.76-.28-1.21 0-.79.26-1.44.79-1.95s1.22-.77 2.069-.77c.58 0 1.101.13 1.561.39.46.261.811.62 1.051 1.091.239.469.369.969.369 1.529h-1.26c0-.62-.15-1.09-.439-1.43-.291-.34-.721-.51-1.281-.51-.51 0-.899.14-1.18.42-.279.279-.42.68-.42 1.189 0 .42.15.77.45 1.051.3.279.771.539 1.39.76.98.32 1.681.72 2.101 1.199.42.48.63 1.09.63 1.86 0 .81-.26 1.45-.79 1.94-.521.49-1.239.729-2.14.729-.58 0-1.11-.13-1.6-.38s-.881-.609-1.16-1.07c-.281-.459-.42-.979-.42-1.58h1.26c0 .621.17 1.09.52 1.44s.811.51 1.4.51c.55 0 .97-.14 1.24-.42.268-.277.438-.656.438-1.147zm2.9 2.52v-9.95h2.84c.91 0 1.59.22 2.05.66s.69 1.101.69 1.99c0 .46-.121.88-.351 1.229s-.55.631-.94.83c.461.141.82.42 1.08.84s.4.93.4 1.53c0 .89-.25 1.601-.73 2.11-.479.51-1.189.77-2.1.77H140.4V54zm1.25-5.71h1.609c.43 0 .78-.149 1.051-.44.27-.289.399-.68.399-1.16 0-.549-.12-.949-.36-1.199s-.609-.37-1.109-.37h-1.59v3.169zm0 1.05v3.58h1.709c.471 0 .851-.16 1.131-.47.279-.31.42-.75.42-1.32 0-1.2-.51-1.8-1.52-1.8h-1.74v.01zm8.6-.3 2.04-5h1.42l-2.83 6.239V54h-1.25v-3.71l-2.83-6.24h1.421l2.029 4.99zm10.689-3.92h-2.619V54h-1.25v-8.88h-2.61v-1.07h6.479v1.07zm6.241 4.28h-3.52v3.529h4.1V54h-5.35v-9.95h5.279v1.07h-4.029v3.21h3.52v1.07zm5.19.571h-1.75V54h-1.261v-9.95h2.791c.979 0 1.709.25 2.209.761.5.51.75 1.25.75 2.229 0 .62-.139 1.149-.409 1.61-.271.459-.66.8-1.15 1.039l1.931 4.23V54h-1.351l-1.76-4.029zm-1.75-1.071h1.521c.52 0 .939-.17 1.25-.51.31-.341.469-.791.469-1.361 0-1.279-.58-1.92-1.739-1.92h-1.5V48.9zm7.48 5.1h-1.25v-9.95h1.25V54zm6.95-2.6h-3.36l-.77 2.6h-1.29l3.21-9.95h1.07l3.21 9.95h-1.29l-.78-2.6zm-3.03-1.08h2.711l-1.36-4.53-1.351 4.53zM194.98 54h-1.26l-3.99-7.67V54h-1.26v-9.95h1.26l4 7.7v-7.7h1.24V54h.01zm11.61-3.17c-.05 1.07-.35 1.88-.9 2.449-.539.57-1.31.851-2.31.851s-1.79-.38-2.38-1.14-.88-1.79-.88-3.09v-1.8c0-1.289.3-2.32.909-3.07.611-.75 1.431-1.129 2.471-1.129.96 0 1.71.289 2.23.859.52.57.81 1.4.859 2.471h-1.26c-.051-.811-.23-1.391-.51-1.74-.28-.35-.721-.52-1.311-.52-.68 0-1.199.27-1.57.799-.369.53-.549 1.311-.549 2.33v1.83c0 1.01.17 1.78.51 2.32s.83.811 1.49.811c.659 0 1.119-.16 1.409-.49s.46-.9.53-1.74h1.262v-.001zM214.8 54h-1.26v-4.6h-4.011V54h-1.25v-9.95h1.25v4.28h4.011v-4.28h1.26V54zm8.1-9.95v7.08c-.01.94-.28 1.681-.82 2.21-.54.53-1.29.801-2.25.801-.98 0-1.74-.261-2.26-.781-.521-.52-.78-1.27-.791-2.229v-7.08h1.24v7.021c0 .67.141 1.17.431 1.5s.739.5 1.38.5 1.1-.17 1.38-.5.431-.83.431-1.5V44.05h1.259zm4.94 5.921h-1.75V54h-1.26v-9.95h2.79c.979 0 1.71.25 2.21.761.5.51.75 1.25.75 2.229 0 .62-.141 1.149-.41 1.61-.27.459-.66.8-1.15 1.039l1.931 4.23V54H229.6l-1.76-4.029zm-1.75-1.071h1.52c.521 0 .94-.17 1.25-.51.311-.341.471-.791.471-1.361 0-1.279-.58-1.92-1.74-1.92h-1.5V48.9zm12.41 1.93c-.05 1.07-.35 1.88-.9 2.449-.539.57-1.31.851-2.31.851s-1.79-.38-2.38-1.14-.881-1.79-.881-3.09v-1.8c0-1.289.301-2.32.91-3.07.61-.75 1.431-1.129 2.471-1.129.96 0 1.71.289 2.23.859.52.57.81 1.4.859 2.471h-1.26c-.051-.811-.23-1.391-.51-1.74-.28-.35-.721-.52-1.311-.52-.68 0-1.199.27-1.57.799-.369.53-.55 1.311-.55 2.33v1.83c0 1.01.171 1.78.511 2.32s.83.811 1.489.811c.66 0 1.12-.16 1.41-.49s.46-.9.53-1.74h1.262v-.001zm8.21 3.17h-1.26v-4.6h-4.011V54h-1.25v-9.95h1.25v4.28h4.011v-4.28h1.26V54z"/><g fill="#FFF"><path d="M7.374 56.306h.492v2.282h1.107V59H7.374v-2.694zM11.58 57.625c0 .903-.547 1.419-1.291 1.419-.76 0-1.239-.58-1.239-1.371 0-.828.52-1.411 1.279-1.411.788 0 1.251.595 1.251 1.363zm-2.011.04c0 .548.276.983.748.983.476 0 .744-.44.744-1.004 0-.508-.252-.987-.744-.987-.488 0-.748.452-.748 1.008zM14.396 57.625c0 .903-.547 1.419-1.291 1.419-.76 0-1.239-.58-1.239-1.371 0-.828.52-1.411 1.279-1.411.788 0 1.251.595 1.251 1.363zm-2.011.04c0 .548.276.983.748.983.476 0 .744-.44.744-1.004 0-.508-.252-.987-.744-.987-.488 0-.748.452-.748 1.008zM14.822 56.306h.488v1.239h.012c.064-.104.132-.2.196-.292l.704-.947h.607l-.927 1.147.987 1.547h-.576l-.764-1.235-.24.288V59h-.488v-2.694zM17.641 56.306V59h-.492v-2.694h.492zM18.206 59v-2.694h.56l.695 1.155c.18.3.336.611.46.903h.008c-.032-.359-.044-.708-.044-1.119v-.939h.456V59h-.507l-.704-1.184c-.172-.296-.352-.627-.484-.932l-.012.004c.02.349.024.704.024 1.147V59h-.452zM23.069 58.876c-.172.064-.508.152-.868.152-.456 0-.8-.116-1.056-.36-.24-.228-.379-.583-.375-.991 0-.852.607-1.399 1.499-1.399.332 0 .592.068.716.128l-.108.396c-.148-.063-.328-.115-.612-.115-.576 0-.979.34-.979.967 0 .612.376.976.936.976.176 0 .308-.023.372-.056V57.9h-.476v-.388h.952v1.364zM24.889 56.306v1.571c0 .527.22.771.552.771.356 0 .568-.244.568-.771v-1.571h.491v1.539c0 .831-.428 1.199-1.076 1.199-.624 0-1.027-.348-1.027-1.195v-1.543h.492zM27.065 59v-2.694h.56l.695 1.155c.18.3.336.611.46.903h.008c-.032-.359-.044-.708-.044-1.119v-.939h.456V59h-.507l-.704-1.184c-.172-.296-.352-.627-.484-.932l-.012.004c.02.349.024.704.024 1.147V59h-.452zM30.285 56.717h-.768v-.411h2.035v.411h-.775V59h-.492v-2.283zM34.148 57.625c0 .903-.547 1.419-1.291 1.419-.76 0-1.239-.58-1.239-1.371 0-.828.52-1.411 1.279-1.411.788 0 1.251.595 1.251 1.363zm-2.011.04c0 .548.276.983.748.983.476 0 .744-.44.744-1.004 0-.508-.252-.987-.744-.987-.488 0-.748.452-.748 1.008zM35.937 56.306h.492v1.747c0 .743-.36.991-.896.991-.136 0-.308-.024-.412-.063l.06-.396c.08.024.188.048.304.048.28 0 .452-.128.452-.596v-1.731zM38.477 57.805h-1.016v.792h1.136V59H36.97v-2.694h1.567v.403h-1.076v.696h1.016v.4zM39.018 58.464c.16.093.399.172.651.172.316 0 .492-.147.492-.367 0-.204-.136-.324-.479-.448-.444-.159-.728-.396-.728-.783 0-.439.368-.775.951-.775.292 0 .508.063.648.136l-.12.396c-.096-.052-.284-.128-.536-.128-.312 0-.448.168-.448.324 0 .208.156.304.516.443.468.176.696.412.696.8 0 .432-.328.808-1.023.808-.284 0-.58-.08-.728-.168l.108-.41zM41.593 56.306v1.571c0 .527.22.771.552.771.356 0 .568-.244.568-.771v-1.571h.492v1.539c0 .831-.428 1.199-1.076 1.199-.624 0-1.027-.348-1.027-1.195v-1.543h.491zM43.757 58.464c.16.093.4.172.651.172.316 0 .492-.147.492-.367 0-.204-.136-.324-.479-.448-.444-.159-.728-.396-.728-.783 0-.439.368-.775.951-.775.292 0 .508.063.648.136l-.12.396c-.096-.052-.284-.128-.536-.128-.312 0-.448.168-.448.324 0 .208.156.304.516.443.468.176.696.412.696.8 0 .432-.328.808-1.023.808-.284 0-.58-.08-.728-.168l.108-.41z"/></g><g fill="#FFF"><path d="M49.532 56.306v1.079h1.14v-1.079h.492V59h-.492v-1.188h-1.14V59h-.492v-2.694h.492zM53.235 57.805H52.22v.792h1.136V59h-1.627v-2.694h1.567v.403H52.22v.696h1.016v.4zM53.788 56.342c.156-.032.44-.057.716-.057.364 0 .587.044.771.172.168.101.28.276.28.504 0 .248-.156.476-.448.584v.008c.284.072.544.296.544.668 0 .239-.104.428-.26.556-.191.168-.507.252-.999.252-.272 0-.48-.021-.604-.036v-2.651zm.488 1.063h.252c.34 0 .532-.16.532-.388 0-.252-.192-.368-.504-.368-.144 0-.228.008-.28.02v.736zm0 1.24c.064.008.148.012.26.012.316 0 .596-.12.596-.452 0-.312-.271-.439-.611-.439h-.244v.879zM57.58 56.845h-.008l-.476.24-.084-.372.631-.312h.412V59h-.475v-2.155zM58.952 59v-.3l.308-.284c.611-.567.899-.879.903-1.223 0-.232-.124-.437-.464-.437-.228 0-.424.116-.556.216L59 56.625c.188-.151.472-.268.8-.268.576 0 .855.364.855.787 0 .456-.328.824-.78 1.244l-.228.195v.008h1.067V59h-1.762zM61.128 57.385c0-.184.128-.315.304-.315.18 0 .3.132.304.315 0 .18-.12.312-.304.312-.18-.001-.304-.133-.304-.312zm0 1.347c0-.185.128-.316.304-.316.18 0 .3.128.304.316 0 .176-.12.312-.304.312-.18 0-.304-.136-.304-.312zM62.04 59v-.3l.308-.284c.611-.567.899-.879.903-1.223 0-.232-.124-.437-.464-.437-.228 0-.424.116-.556.216l-.144-.348c.188-.151.472-.268.8-.268.576 0 .855.364.855.787 0 .456-.328.824-.78 1.244l-.228.195v.008h1.067V59H62.04z"/></g></svg>', get_bloginfo( 'name', 'display' ) );
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
