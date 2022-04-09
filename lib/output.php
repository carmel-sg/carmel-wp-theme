<?php
/**
 * Carmel.
 *
 * This file adds the required CSS to the front end to the Carmel Theme.
 *
 * @package Carmel
 * @license GPL-2.0-or-later
 */

add_action( 'wp_enqueue_scripts', 'carmel_css' );
/**
 * Checks the settings for the link color, and accent color.
 * If any of these value are set the appropriate CSS is output.
 */
function carmel_css() {

	$appearance = genesis_get_config( 'appearance' );

	$color_link   = get_theme_mod( 'carmel_link_color', $appearance['default-colors']['link'] );
	$color_accent = get_theme_mod( 'carmel_accent_color', $appearance['default-colors']['accent'] );

	$css = '';

	$css .= ( $appearance['default-colors']['link'] !== $color_link ) ? sprintf(
		'body {
			--carmel-link-color: %1$s;
			--carmel-link-color-darker: %2$s;
			--carmel-link-color-brighter: %3$s;
		}',
		$color_link,
		carmel_color_brightness( $color_link, -20 ),
		carmel_color_brightness( $color_link, 20 )
	) : '';

	$css .= ( $appearance['default-colors']['accent'] !== $color_accent || true) ? sprintf(
		'body {
			--carmel-accent-color: %1$s;
			--carmel-accent-color-darker: %2$s;
			--carmel-accent-color-brighter: %3$s;
		}',
		$color_accent,
		carmel_color_brightness( $color_accent, -50 ),
		carmel_color_brightness( $color_accent, 210 )
	) : '';

	if ( $css ) {
		wp_add_inline_style( genesis_get_theme_handle(), $css );
	}

}
