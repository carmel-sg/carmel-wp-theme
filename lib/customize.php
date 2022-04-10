<?php
/**
 * Carmel.
 *
 * This file adds the Customizer additions to the Carmel Theme.
 *
 * @package Carmel
 * @license GPL-2.0-or-later
 */

add_action( 'customize_register', 'carmel_customizer_register' );
/**
 * Registers settings and controls with the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function carmel_customizer_register( $wp_customize ) {

	$appearance = genesis_get_config( 'appearance' );

	$wp_customize->add_setting(
		'carmel_link_color',
		[
			'default'           => $appearance['default-colors']['link'],
			'sanitize_callback' => 'sanitize_hex_color',
		]
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'carmel_link_color',
			[
				'description' => __( 'Change the color of post info links and button blocks, the hover color of linked titles and menu items, and more.', 'carmel' ),
				'label'       => __( 'Link Color', 'carmel' ),
				'section'     => 'colors',
				'settings'    => 'carmel_link_color',
			]
		)
	);

	$wp_customize->add_setting(
		'carmel_accent_color',
		[
			'default'           => $appearance['default-colors']['accent'],
			'sanitize_callback' => 'sanitize_hex_color',
		]
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'carmel_accent_color',
			[
				'description' => __( 'Change the default hover color for button links, menu buttons, and submit buttons. The button block uses the Link Color.', 'carmel' ),
				'label'       => __( 'Accent Color', 'carmel' ),
				'section'     => 'colors',
				'settings'    => 'carmel_accent_color',
			]
		)
	);

}
