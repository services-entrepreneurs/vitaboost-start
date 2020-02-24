<?php
/**
 * Segenvita.
 *
 * This file adds the Customizer additions to the Segenvita Theme.
 *
 * @package Segenvita
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://www.studiopress.com/
 */

add_action( 'customize_register', 'segenvita_customizer_register' );
/**
 * Registers settings and controls with the Customizer.
 *
 * @since 2.2.3
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function segenvita_customizer_register( $wp_customize ) {

	$appearance = genesis_get_config( 'appearance' );

	$wp_customize->add_setting(
		'segenvita_link_color',
		[
			'default'           => $appearance['default-colors']['link'],
			'sanitize_callback' => 'sanitize_hex_color',
		]
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'segenvita_link_color',
			[
				'description' => __( 'Change the color of post info links and button blocks, the hover color of linked titles and menu items, and more.', 'segenvita' ),
				'label'       => __( 'Link Color', 'segenvita' ),
				'section'     => 'colors',
				'settings'    => 'segenvita_link_color',
			]
		)
	);

	$wp_customize->add_setting(
		'segenvita_accent_color',
		[
			'default'           => $appearance['default-colors']['accent'],
			'sanitize_callback' => 'sanitize_hex_color',
		]
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'segenvita_accent_color',
			[
				'description' => __( 'Change the default hover color for button links, menu buttons, and submit buttons. The button block uses the Link Color.', 'segenvita' ),
				'label'       => __( 'Accent Color', 'segenvita' ),
				'section'     => 'colors',
				'settings'    => 'segenvita_accent_color',
			]
		)
	);

	$wp_customize->add_setting(
		'segenvita_logo_width',
		[
			'default'           => 350,
			'sanitize_callback' => 'absint',
			'validate_callback' => 'segenvita_validate_logo_width',
		]
	);

	// Add a control for the logo size.
	$wp_customize->add_control(
		'segenvita_logo_width',
		[
			'label'       => __( 'Logo Width', 'segenvita' ),
			'description' => __( 'The maximum width of the logo in pixels.', 'segenvita' ),
			'priority'    => 9,
			'section'     => 'title_tagline',
			'settings'    => 'segenvita_logo_width',
			'type'        => 'number',
			'input_attrs' => [
				'min' => 100,
			],

		]
	);

}

/**
 * Displays a message if the entered width is not numeric or greater than 100.
 *
 * @param object $validity The validity status.
 * @param int    $width The width entered by the user.
 * @return int The new width.
 */
function segenvita_validate_logo_width( $validity, $width ) {

	if ( empty( $width ) || ! is_numeric( $width ) ) {
		$validity->add( 'required', __( 'You must supply a valid number.', 'segenvita' ) );
	} elseif ( $width < 100 ) {
		$validity->add( 'logo_too_small', __( 'The logo width cannot be less than 100.', 'segenvita' ) );
	}

	return $validity;

}
