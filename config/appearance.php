<?php
/**
 * Segenvita appearance settings.
 *
 * @package Segenvita
 * @author  Services-Entrepreneurs
 * @license GPL-2.0-or-later
 * @link    https://www.services-entrepreneurs.ch/
 */

$segenvita_default_colors = [
	'link'   => '#0073e5',
	'accent' => '#0073e5',
];

$segenvita_link_color = get_theme_mod(
	'segenvita_link_color',
	$segenvita_default_colors['link']
);

$segenvita_accent_color = get_theme_mod(
	'segenvita_accent_color',
	$segenvita_default_colors['accent']
);

$segenvita_link_color_contrast   = segenvita_color_contrast( $segenvita_link_color );
$segenvita_link_color_brightness = segenvita_color_brightness( $segenvita_link_color, 35 );

return [
	'fonts-url'            => 'https://fonts.googleapis.com/css?family=Poppins:300,400,600|Raleway:400,700&display=swap',
	'content-width'        => 1062,
	'button-bg'            => $segenvita_link_color,
	'button-color'         => $segenvita_link_color_contrast,
	'button-outline-hover' => $segenvita_link_color_brightness,
	'link-color'           => $segenvita_link_color,
	'default-colors'       => $segenvita_default_colors,
	'editor-color-palette' => [
		[
			'name'  => __( 'Custom color', 'segenvita' ), // Called “Link Color” in the Customizer options. Renamed because “Link Color” implies it can only be used for links.
			'slug'  => 'theme-primary',
			'color' => $segenvita_link_color,
		],
		[
			'name'  => __( 'Accent color', 'segenvita' ),
			'slug'  => 'theme-secondary',
			'color' => $segenvita_accent_color,
		],
	],
	'editor-font-sizes'    => [
		[
			'name' => __( 'Small', 'segenvita' ),
			'size' => 12,
			'slug' => 'small',
		],
		[
			'name' => __( 'Normal', 'segenvita' ),
			'size' => 18,
			'slug' => 'normal',
		],
		[
			'name' => __( 'Large', 'segenvita' ),
			'size' => 20,
			'slug' => 'large',
		],
		[
			'name' => __( 'Larger', 'segenvita' ),
			'size' => 24,
			'slug' => 'larger',
		],
	],
];
