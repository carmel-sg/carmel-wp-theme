<?php
/**
 * Gutenberg theme support.
 *
 * @package Carmel
 * @license GPL-2.0-or-later
 */

add_action( 'wp_enqueue_scripts', 'carmel_enqueue_gutenberg_frontend_styles' );
/**
 * Enqueues Gutenberg front-end styles.
 */
function carmel_enqueue_gutenberg_frontend_styles() {

	wp_enqueue_style(
		genesis_get_theme_handle() . '-gutenberg',
		get_stylesheet_directory_uri() . '/lib/gutenberg/front-end.css',
		[ genesis_get_theme_handle() ],
		genesis_get_theme_version()
	);

}

add_action( 'enqueue_block_editor_assets', 'carmel_block_editor_styles' );
/**
 * Enqueues Gutenberg admin editor fonts and styles.
 */
function carmel_block_editor_styles() {

	$appearance = genesis_get_config( 'appearance' );

	wp_enqueue_style(
		genesis_get_theme_handle() . '-gutenberg-fonts',
		$appearance['fonts-url'],
		[],
		genesis_get_theme_version()
	);

}

add_filter( 'body_class', 'carmel_blocks_body_classes' );
/**
 * Adds body classes to help with block styling.
 *
 * - `has-no-blocks` if content contains no blocks.
 * - `first-block-[block-name]` to allow changes based on the first block (such as removing padding above a Cover block).
 * - `first-block-align-[alignment]` to allow styling adjustment if the first block is wide or full-width.
 *
 * @param array $classes The original classes.
 * @return array The modified classes.
 */
function carmel_blocks_body_classes( $classes ) {

	if ( ! is_singular() || ! function_exists( 'has_blocks' ) || ! function_exists( 'parse_blocks' ) ) {
		return $classes;
	}

	if ( ! has_blocks() ) {
		$classes[] = 'has-no-blocks';
		return $classes;
	}

	$post_object = get_post( get_the_ID() );
	$blocks      = (array) parse_blocks( $post_object->post_content );

	if ( isset( $blocks[0]['blockName'] ) ) {
		$classes[] = 'first-block-' . str_replace( '/', '-', $blocks[0]['blockName'] );
	}

	if ( isset( $blocks[0]['attrs']['align'] ) ) {
		$classes[] = 'first-block-align-' . $blocks[0]['attrs']['align'];
	}

	return $classes;

}

// Add support for editor styles.
add_theme_support( 'editor-styles' );

// Enqueue editor styles.
add_editor_style( '/lib/gutenberg/style-editor.css' );

// Adds support for block alignments.
add_theme_support( 'align-wide' );

// Make media embeds responsive.
add_theme_support( 'responsive-embeds' );

// Add support for custom line heights.
add_theme_support( 'custom-line-height' );

// Add support for custom units.
add_theme_support( 'custom-units' );

$carmel_appearance = genesis_get_config( 'appearance' );

// Adds support for editor font sizes.
add_theme_support(
	'editor-font-sizes',
	$carmel_appearance['editor-font-sizes']
);

// Adds support for editor color palette.
add_theme_support(
	'editor-color-palette',
	$carmel_appearance['editor-color-palette']
);

require_once get_stylesheet_directory() . '/lib/gutenberg/inline-styles.php';

add_action( 'after_setup_theme', 'carmel_content_width', 0 );
/**
 * Set content width to match the “wide” Gutenberg block width.
 */
function carmel_content_width() {

	$appearance = genesis_get_config( 'appearance' );

	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- See https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/924
	$GLOBALS['content_width'] = apply_filters( 'carmel_content_width', $appearance['content-width'] );

}
