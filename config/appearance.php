<?php
/**
 * Carmel appearance settings.
 *
 * @package Carmel
 * @license GPL-2.0-or-later
 */

$carmel_default_colors = [
	'link'   => '#8d0101',
	'accent' => '#f19e04',
];

$carmel_link_color = get_theme_mod(
	'carmel_link_color',
	$carmel_default_colors['link']
);

$carmel_accent_color = get_theme_mod(
	'carmel_accent_color',
	$carmel_default_colors['accent']
);

$carmel_link_color_contrast   = carmel_color_contrast( $carmel_link_color );
$carmel_link_color_brightness = carmel_color_brightness( $carmel_link_color, 35 );

return [
	'fonts-url'            => 'https://fonts.googleapis.com/css2?family=Caveat&family=Lato:ital,wght@0,400;0,700;1,400&display=swap',
	'content-width'        => 1062,
	'button-bg'            => $carmel_link_color,
	'button-color'         => $carmel_link_color_contrast,
	'button-outline-hover' => $carmel_link_color_brightness,
	'link-color'           => $carmel_link_color,
	'default-colors'       => $carmel_default_colors,
	'editor-color-palette' => [
		[
			'name'  => __( 'Custom color', 'carmel' ), // Called “Link Color” in the Customizer options. Renamed because “Link Color” implies it can only be used for links.
			'slug'  => 'theme-primary',
			'color' => $carmel_link_color,
		],
		[
			'name'  => __( 'Accent color', 'carmel' ),
			'slug'  => 'theme-secondary',
			'color' => $carmel_accent_color,
		],
	],
	'editor-font-sizes'    => [
		[
			'name' => __( 'Small', 'carmel' ),
			'size' => 12,
			'slug' => 'small',
		],
		[
			'name' => __( 'Normal', 'carmel' ),
			'size' => 18,
			'slug' => 'normal',
		],
		[
			'name' => __( 'Large', 'carmel' ),
			'size' => 20,
			'slug' => 'large',
		],
		[
			'name' => __( 'Larger', 'carmel' ),
			'size' => 24,
			'slug' => 'larger',
		],
	],
];
